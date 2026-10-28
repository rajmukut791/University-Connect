<?php

namespace App\Http\Controllers;

use App\Models\AlumniConversionRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumniConversionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Student - Show Alumni Conversion Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(
                403,
                'Only students can apply for alumni conversion.'
            );
        }

        $existingRequest = AlumniConversionRequest::where(
            'user_id',
            $user->id
        )
            ->where('status', 'pending')
            ->first();

        return view(
            'alumni-conversion.create',
            ['existingRequest'=>$existingRequest, 'latestApplication'=>AlumniConversionRequest::where('user_id',$user->id)->latest('id')->first()]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student - Submit Alumni Conversion Request
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(
                403,
                'Only students can apply for alumni conversion.'
            );
        }

        $alreadyPending = AlumniConversionRequest::where(
            'user_id',
            $user->id
        )
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with(
                'error',
                'You already have a pending alumni conversion request.'
            );
        }

        $validated = $request->validate([
            'graduation_year' => [
                'required',
                'digits:4',
            ],

            'current_company' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'student_note' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'supporting_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:5120',
            ],
        ]);

        $documentPath = null;

        if ($request->hasFile('supporting_document')) {
            $documentPath = $request
                ->file('supporting_document')
                ->store(
                    'alumni-conversion-documents',
                    'public'
                );
        }

        $conversionRequest = AlumniConversionRequest::create([
            'user_id' => $user->id,

            'student_id' => $user->student_id,

            'graduation_year' =>
                $validated['graduation_year'],

            'current_company' =>
                $validated['current_company'] ?? null,

            'designation' =>
                $validated['designation'] ?? null,

            'student_note' =>
                $validated['student_note'] ?? null,

            'supporting_document' =>
                $documentPath,

            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Student Confirmation Notification
        |--------------------------------------------------------------------------
        */
        NotificationService::personal(
            $user,
            'alumni_conversion_submitted',
            'Alumni Conversion Request Submitted',
            'Your alumni conversion request has been submitted and is waiting for review.',
            route('dashboard'),
            'medium',
            $conversionRequest,
            $user
        );

        /*
        |--------------------------------------------------------------------------
        | Admin + Super Admin Notification
        |--------------------------------------------------------------------------
        */
        NotificationService::management(
            'alumni_conversion_request',
            'New Alumni Conversion Request',
            $user->name .
                ' submitted an alumni conversion request.',
            null,
            'high',
            $conversionRequest,
            $user
        );

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Your alumni conversion request has been submitted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Admin + Super Admin - Conversion Request List
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (
            !in_array(
                $user->role,
                ['admin', 'super_admin'],
                true
            )
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Values
        |--------------------------------------------------------------------------
        */

        $allowedStatuses = [
            'pending',
            'approved',
            'rejected',
            'all',
        ];

        $status = $request->get(
            'status',
            'pending'
        );

        if (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            $status = 'pending';
        }

        $search = trim(
            (string) $request->get('search', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Request Counts
        |--------------------------------------------------------------------------
        */

        $counts = [
            'all' => AlumniConversionRequest::count(),

            'pending' =>
                AlumniConversionRequest::where(
                    'status',
                    'pending'
                )->count(),

            'approved' =>
                AlumniConversionRequest::where(
                    'status',
                    'approved'
                )->count(),

            'rejected' =>
                AlumniConversionRequest::where(
                    'status',
                    'rejected'
                )->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Main Query
        |--------------------------------------------------------------------------
        */

        $query = AlumniConversionRequest::with([
            'user',
            'approvedBy',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status !== 'all') {
            $query->where(
                'status',
                $status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query
                    ->where(
                        'student_id',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'graduation_year',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'current_company',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'designation',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'user',
                        function ($userQuery) use ($search) {
                            $userQuery
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'official_id',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        |
        | Pending requests:
        | Oldest first so earlier applications are handled first.
        |
        | Processed requests:
        | Latest first.
        |
        */

        if ($status === 'pending') {
            $query->oldest();
        } else {
            $query->latest();
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $requests = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'alumni-conversion.index',
            compact(
                'requests',
                'status',
                'search',
                'counts'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admin + Super Admin - Approve Conversion Request
    |--------------------------------------------------------------------------
    */
    public function approve(
        Request $request,
        AlumniConversionRequest $conversionRequest
    ) {
        $admin = auth()->user();

        if (
            !in_array(
                $admin->role,
                ['admin', 'super_admin'],
                true
            )
        ) {
            abort(403);
        }

        return DB::transaction(function () use ($request, $conversionRequest, $admin) {
            $conversionRequest = AlumniConversionRequest::whereKey($conversionRequest->id)->lockForUpdate()->firstOrFail();
            if (
                $conversionRequest->status !==
                'pending'
            ) {
                return back()->with(
                    'error',
                    'This request has already been processed.'
                );
            }

            $request->validate([
                'admin_notes' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ]);

            $conversionRequest
                ->loadMissing('user');

            $user = \App\Models\User::whereKey($conversionRequest->user_id)->lockForUpdate()->first();

            if (
                !$user ||
                $user->role !== 'student' || !$user->is_active || $user->is_blocked
            ) {
                return back()->with(
                    'error',
                    'Only active student accounts can be converted to alumni.'
                );
            }

            $alumniId =
                $this->generateAlumniId();

            /*
            |--------------------------------------------------------------------------
            | Convert User
            |--------------------------------------------------------------------------
            */

            $user->update([
                'role' =>
                    'alumni',

                'alumni_id' =>
                    $alumniId,

                'alumni_since' =>
                    now(),

                'converted_from_student_at' =>
                    now(),

                'converted_by' =>
                    $admin->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Conversion Request
            |--------------------------------------------------------------------------
            */

            $conversionRequest->update([
                'status' =>
                    'approved',

                'admin_notes' =>
                    $request->admin_notes,

                'approved_by' =>
                    $admin->id,

                'approved_at' =>
                    now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Alumni Notification
            |--------------------------------------------------------------------------
            */

            NotificationService::personal(
                $user,
                'alumni_conversion_approved',
                'Alumni Conversion Approved',
                'Congratulations! Your account has been successfully converted to an alumni account.',
                route('dashboard'),
                'high',
                $conversionRequest,
                $admin
            );

            /*
            |--------------------------------------------------------------------------
            | Management Activity
            |--------------------------------------------------------------------------
            */

            NotificationService::management(
                'alumni_conversion_approved_management',
                'Alumni Conversion Approved',
                $admin->name .
                    ' converted ' .
                    $user->name .
                    ' to alumni.',
                null,
                'high',
                $conversionRequest,
                $admin
            );

            return back()->with(
                'success',
                'Student account converted to alumni successfully.'
            );
        }, 3);
    }

    /*
    |--------------------------------------------------------------------------
    | Admin + Super Admin - Reject Conversion Request
    |--------------------------------------------------------------------------
    */
    public function reject(
        Request $request,
        AlumniConversionRequest $conversionRequest
    ) {
        $admin = auth()->user();

        if (
            !in_array(
                $admin->role,
                ['admin', 'super_admin'],
                true
            )
        ) {
            abort(403);
        }

        return DB::transaction(function () use ($request, $conversionRequest, $admin) {
            $conversionRequest = AlumniConversionRequest::whereKey($conversionRequest->id)->lockForUpdate()->firstOrFail();
            if (
                $conversionRequest->status !==
                'pending'
            ) {
                return back()->with(
                    'error',
                    'This request has already been processed.'
                );
            }

            $validated = $request->validate([
                'admin_notes' => [
                    'required',
                    'string',
                    'max:2000',
                ],
            ]);

            $conversionRequest
                ->loadMissing('user');

            $conversionRequest->update([
                'status' =>
                    'rejected',

                'admin_notes' =>
                    $validated['admin_notes'],

                'approved_by' =>
                    $admin->id,

                'approved_at' =>
                    now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Student Notification
            |--------------------------------------------------------------------------
            */

            if ($conversionRequest->user) {
                NotificationService::personal(
                    $conversionRequest->user,
                    'alumni_conversion_rejected',
                    'Alumni Conversion Request Rejected',
                    'Your alumni conversion request was not approved. Please review the administrator feedback.',
                    route('dashboard'),
                    'high',
                    $conversionRequest,
                    $admin
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Management Activity
            |--------------------------------------------------------------------------
            */

            NotificationService::management(
                'alumni_conversion_rejected_management',
                'Alumni Conversion Rejected',
                $admin->name .
                    ' rejected the alumni conversion request from ' .
                    ($conversionRequest->user?->name ?? 'a student') .
                    '.',
                null,
                'medium',
                $conversionRequest,
                $admin
            );

            return back()->with(
                'success',
                'Alumni conversion request rejected.'
            );
        }, 3);
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Alumni ID
    |--------------------------------------------------------------------------
    */
    private function generateAlumniId(): string
    {
        $year =
            now()->format('Y');

        $count =
            AlumniConversionRequest::where(
                'status',
                'approved'
            )
                ->whereYear(
                    'approved_at',
                    $year
                )
                ->count() + 1;

        return
            'ALU' .
            $year .
            '-' .
            str_pad(
                $count,
                4,
                '0',
                STR_PAD_LEFT
            );
    }
}