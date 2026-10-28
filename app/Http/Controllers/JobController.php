<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Job List
    |--------------------------------------------------------------------------
    |
    | Admin/Super Admin:
    | - See all jobs
    |
    | Student/Alumni:
    | - See approved jobs only
    |
    */

    public function index(Request $request)
    {
        $user = $request->user();

        $query = JobPosting::with('postedBy');
        if (\Illuminate\Support\Facades\Schema::hasColumn('job_postings', 'archived_at')) $query->whereNull('archived_at');

        /*
        |--------------------------------------------------------------------------
        | Public User View
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $user->role,
            ['admin', 'super_admin'],
            true
        )) {
            $query->where(
                'status',
                'approved'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($inner) use ($search) {

                $inner
                    ->where(
                        'title',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'company_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'location',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'requirements',
                        'like',
                        "%{$search}%"
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {

            $query->where(
                'type',
                $request->type
            );
        }


        if (in_array($user->role, ['admin','super_admin'], true) && in_array($request->input('status'), ['pending','approved','rejected'], true)) {
            $query->where('status', $request->input('status'));
        }

        $jobs = $query
            ->latest()
            ->orderByDesc('id')
            ->paginate(6)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        if (in_array(
            $user->role,
            ['admin', 'super_admin'],
            true
        )) {

            $stats = [

                'total_jobs' =>
                    JobPosting::count(),

                'approved_jobs' =>
                    JobPosting::where(
                        'status',
                        'approved'
                    )->count(),

                'pending_jobs' =>
                    JobPosting::where(
                        'status',
                        'pending'
                    )->count(),

                'rejected_jobs' =>
                    JobPosting::where(
                        'status',
                        'rejected'
                    )->count(),
            ];

        } else {

            $stats = [

                'total_jobs' =>
                    JobPosting::where(
                        'status',
                        'approved'
                    )->count(),
            ];
        }


        return view(
            'jobs.index',
            compact(
                'jobs',
                'stats'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Job
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = auth()->user();

        abort_unless(
            in_array(
                $user->role,
                [
                    'alumni',
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403,
            'You are not allowed to post jobs.'
        );

        return view(
            'jobs.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Job
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = $request->user();

        abort_unless(
            in_array(
                $user->role,
                [
                    'alumni',
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'string',
                'in:full_time,part_time,internship,contract,temporary,remote,hybrid',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'salary_range' => [
                'nullable',
                'string',
                'max:255',
            ],

            'positions_available' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],

            'contact_email' => [
                'nullable',
                'email',
                'max:255',
                'required_without:contact_phone',
            ],

            'contact_phone' => [
                'nullable',
                'string',
                'max:30',
                'required_without:contact_email',
            ],

            'application_url' => [
                'nullable',
                'url',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
                'max:5000',
            ],

            'requirements' => [
                'required',
                'string',
                'max:5000',
            ],

            'deadline' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Default Existing Database Fields
        |--------------------------------------------------------------------------
        */

        $validated['experience_level'] =
            'entry';

        $validated['posted_by'] =
            $user->id;


        /*
        |--------------------------------------------------------------------------
        | Role Based Publishing
        |--------------------------------------------------------------------------
        |
        | Alumni      -> Pending
        |
        | Admin       -> Approved
        | Super Admin -> Approved
        |
        */

        $validated['status'] =
            in_array(
                $user->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            )
                ? 'approved'
                : 'pending';


        $job = JobPosting::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Alumni Submission
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'alumni') {

            NotificationService::management(
                'job_submitted',
                'New Job Submitted',
                $user->name .
                    ' submitted "' .
                    $job->title .
                    '" for approval.',
                route(
                    'jobs.show',
                    $job
                ),
                'high',
                $job,
                $user
            );


            return redirect()
                ->route(
                    'jobs.my'
                )
                ->with(
                    'success',
                    'Job submitted successfully. It is waiting for Admin approval.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Admin / Super Admin Submission
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'jobs.index'
            )
            ->with(
                'success',
                'Job added and published successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Job
    |--------------------------------------------------------------------------
    */

    public function show(
        JobPosting $job
    ) {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Student / Alumni Can Only View Approved Jobs
        |--------------------------------------------------------------------------
        |
        | Alumni poster can still view their own pending/rejected job.
        |
        */

        if (!in_array(
            $user->role,
            ['admin', 'super_admin'],
            true
        )) {

            $isOwner =
                $user->role === 'alumni'
                && (int) $job->posted_by ===
                    (int) $user->id;


            if (
                $job->status !== 'approved'
                && !$isOwner
            ) {
                abort(
                    404
                );
            }
        }


        $job->load([
            'postedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Compatibility
        |--------------------------------------------------------------------------
        |
        | Existing Blade may still expect this variable.
        |
        */

        $alreadyApplied = false;


        return view(
            'jobs.show',
            compact(
                'job',
                'alreadyApplied'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve Job
    |--------------------------------------------------------------------------
    */

    public function approve(
        JobPosting $job
    ) {
        $admin = auth()->user();

        abort_unless(
            in_array(
                $admin->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403
        );


        if (
            $job->status === 'approved'
        ) {
            return back()->with(
                'success',
                'Job is already approved.'
            );
        }


        $job->update([
            'status' => 'approved',
        ]);


        $job->loadMissing(
            'postedBy'
        );


        /*
        |--------------------------------------------------------------------------
        | Notify Alumni Poster
        |--------------------------------------------------------------------------
        */

        if (
            $job->postedBy
            && $job->postedBy->role ===
                'alumni'
        ) {

            NotificationService::personal(
                $job->postedBy,
                'job_posting_approved',
                'Job Approved',
                'Your job post "' .
                    $job->title .
                    '" has been approved and published.',
                route(
                    'jobs.show',
                    $job
                ),
                'high',
                $job,
                $admin
            );
        }


        NotificationService::management(
            'job_approved',
            'Job Approved',
            $admin->name .
                ' approved "' .
                $job->title .
                '".',
            route(
                'jobs.show',
                $job
            ),
            'medium',
            $job,
            $admin
        );


        return back()->with(
            'success',
            'Job approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Job
    |--------------------------------------------------------------------------
    */

    public function reject(
        JobPosting $job
    ) {
        $admin = auth()->user();

        abort_unless(
            in_array(
                $admin->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403
        );


        if (
            $job->status === 'rejected'
        ) {
            return back()->with(
                'success',
                'Job is already rejected.'
            );
        }


        $job->update([
            'status' => 'rejected',
        ]);


        $job->loadMissing(
            'postedBy'
        );


        if (
            $job->postedBy
            && $job->postedBy->role ===
                'alumni'
        ) {

            NotificationService::personal(
                $job->postedBy,
                'job_posting_rejected',
                'Job Rejected',
                'Your job post "' .
                    $job->title .
                    '" was not approved.',
                route(
                    'jobs.show',
                    $job
                ),
                'high',
                $job,
                $admin
            );
        }


        NotificationService::management(
            'job_rejected',
            'Job Rejected',
            $admin->name .
                ' rejected "' .
                $job->title .
                '".',
            null,
            'medium',
            $job,
            $admin
        );


        return back()->with(
            'success',
            'Job rejected successfully.'
        );
    }

     public function destroy(JobPosting $job)
{
        return app(PostManagementController::class)->destroy('job', $job->id);
    }
    /*
    |--------------------------------------------------------------------------
    | My Posted Jobs
    |--------------------------------------------------------------------------
    */

    public function myJobs()
    {
        $user = auth()->user();


        abort_unless(
            in_array(
                $user->role,
                [
                    'alumni',
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403
        );


        $jobs = JobPosting::where(
            'posted_by',
            $user->id
        )
            ->latest()
            ->paginate(10);


        return view(
            'jobs.my-jobs',
            compact(
                'jobs'
            )
        );
    }
}