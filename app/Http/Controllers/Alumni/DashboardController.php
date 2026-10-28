<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Mentorship;
use App\Models\Message;
use App\Models\Notification;
use App\Services\ProfileStrengthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Alumni Job Statistics
        |--------------------------------------------------------------------------
        */

        $myJobsCount = 0;
        $approvedJobsCount = 0;
        $pendingJobsCount = 0;
        $rejectedJobsCount = 0;
        $myJobs = collect();

        if (
            Schema::hasTable('job_postings') &&
            Schema::hasColumn('job_postings', 'posted_by')
        ) {
            $jobQuery = DB::table('job_postings')
                ->where('posted_by', $user->id);

            $myJobsCount = (clone $jobQuery)->count();

            if (Schema::hasColumn('job_postings', 'status')) {
                $approvedJobsCount = (clone $jobQuery)
                    ->where('status', 'approved')
                    ->count();

                $pendingJobsCount = (clone $jobQuery)
                    ->where('status', 'pending')
                    ->count();

                $rejectedJobsCount = (clone $jobQuery)
                    ->where('status', 'rejected')
                    ->count();
            }

            $orderColumn = Schema::hasColumn(
                'job_postings',
                'created_at'
            )
                ? 'created_at'
                : 'id';

            $myJobs = (clone $jobQuery)
                ->orderByDesc($orderColumn)
                ->take(5)
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Mentorship Statistics
        |--------------------------------------------------------------------------
        */

        $mentorshipRequestsCount = 0;
        $pendingMentorshipsCount = 0;
        $acceptedMentorshipsCount = 0;
        $mentorshipRequests = collect();

        if (
            Schema::hasTable('mentorships') &&
            Schema::hasColumn('mentorships', 'mentor_id')
        ) {
            $mentorshipQuery = Mentorship::where(
                'mentor_id',
                $user->id
            );

            $mentorshipRequestsCount =
                \App\Services\DashboardActivityService::connections($user)->count();

            if (Schema::hasColumn('mentorships', 'status')) {
                $pendingMentorshipsCount =
                    (clone $mentorshipQuery)
                        ->where('status', 'pending')
                        ->count();

                $acceptedMentorshipsCount =
                    (clone $mentorshipQuery)
                        ->where('status', 'accepted')
                        ->count();
            }

            /*
            |--------------------------------------------------------------
            | We do not assume a student() relationship exists.
            | Student names are loaded safely using the users table.
            |--------------------------------------------------------------
            */

            $mentorshipRequests = DB::table('mentorships')
                ->leftJoin(
                    'users',
                    'mentorships.student_id',
                    '=',
                    'users.id'
                )
                ->where(
                    'mentorships.mentor_id',
                    $user->id
                )
                ->select(
                    'mentorships.*',
                    'users.name as student_name',
                    'users.department as student_department',
                    'users.batch as student_batch'
                )
                ->orderByDesc('mentorships.created_at')
                ->take(5)
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'my_jobs' => $myJobsCount,

            'approved_jobs' => $approvedJobsCount,

            'pending_jobs' => $pendingJobsCount,

            'rejected_jobs' => $rejectedJobsCount,

            'mentorship_requests' => $mentorshipRequestsCount,

            'pending_mentorships' => $pendingMentorshipsCount,

            'accepted_mentorships' => $acceptedMentorshipsCount,

            'unread_messages' => $this->getUnreadMessages(
                $user->id
            ),

            'unread_notifications' => $this->getUnreadNotifications(
                $user->id
            ),
        ];


        /*
        |--------------------------------------------------------------------------
        | Profile Strength
        |--------------------------------------------------------------------------
        */

        $profileStrength = app(
            ProfileStrengthService::class
        )->analyze($user);

        $profileScore =
            (int) ($profileStrength['score'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Dashboard information
        |--------------------------------------------------------------------------
        */




        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'alumni.dashboard',
            compact(
                'user',
                'stats',
                'myJobs',
                'mentorshipRequests',
                'profileStrength',
                'profileScore'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Unread Messages
    |--------------------------------------------------------------------------
    */

    private function getUnreadMessages(int $userId): int
    {
        return \App\Services\DashboardActivityService::unread(auth()->user())->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Unread Notifications
    |--------------------------------------------------------------------------
    */

    private function getUnreadNotifications(
        int $userId
    ): int {
        if (!Schema::hasTable('notifications')) {
            return 0;
        }

        if (!Schema::hasColumn(
            'notifications',
            'user_id'
        )) {
            return 0;
        }

        return Notification::where(
            'user_id',
            $userId
        )
            ->when(
                Schema::hasColumn(
                    'notifications',
                    'read_at'
                ),
                fn ($query) =>
                    $query->whereNull('read_at')
            )
            ->count();
    }
}