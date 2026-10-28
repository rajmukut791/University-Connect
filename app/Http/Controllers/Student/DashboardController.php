<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JobPosting;
use App\Models\Mentorship;
use App\Models\Message;
use App\Services\ProfileStrengthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Student Dashboard
     */
    public function index()
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->role === 'student',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total_jobs' => $this->getTotalJobs(),
            'total_events' => $this->getTotalEvents(),
            'mentorship_requests' => $this->getMentorshipRequests($user->id),
            'unread_messages' => $this->getUnreadMessages($user->id),
        ];

        /*
        |--------------------------------------------------------------------------
        | Recommended Content
        |--------------------------------------------------------------------------
        */

        $recommendedJobs = $this->getRecommendedJobs();

        $recommendedEvents = $this->getRecommendedEvents();

        /*
        |--------------------------------------------------------------------------
        | Profile Strength
        |--------------------------------------------------------------------------
        */

        $profileStrength = app(ProfileStrengthService::class)
            ->analyze($user);

        $profileScore = (int) ($profileStrength['score'] ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Dashboard information
        |--------------------------------------------------------------------------
        */



        return view('student.dashboard', compact(
            'user',
            'stats',
            'recommendedJobs',
            'recommendedEvents',
            'profileStrength',
            'profileScore'
        ));
    }


    /**
     * Student information
     */



    /*
    |--------------------------------------------------------------------------
    | Approved Jobs
    |--------------------------------------------------------------------------
    */

    private function getTotalJobs(): int
    {
        if (!Schema::hasTable('job_postings')) {
            return 0;
        }

        $query = JobPosting::whereNull('archived_at');

        if (Schema::hasColumn('job_postings', 'status')) {
            $query->where('status', 'approved');
        }

        return $query->count();
    }


    private function getRecommendedJobs()
    {
        if (!Schema::hasTable('job_postings')) {
            return collect();
        }

        $query = JobPosting::whereNull('archived_at');

        if (Schema::hasColumn('job_postings', 'status')) {
            $query->where('status', 'approved');
        }

        /*
        |--------------------------------------------------------------------------
        | Do not show expired jobs
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('job_postings', 'deadline')) {
            $query->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhereDate('deadline', '>=', now()->toDateString());
            });
        }

        return $query
            ->latest()
            ->take(3)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    private function getTotalEvents(): int
    {
        return \App\Services\DashboardActivityService::events(auth()->user())->count();
    }


    private function getRecommendedEvents()
    {
        if (!Schema::hasTable('events')) {
            return collect();
        }

        $query = Event::whereNull('archived_at');

        if (Schema::hasColumn('events', 'status')) {
            $query->whereIn(
                'status',
                ['approved', 'active', 'published']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prefer upcoming events when event_date exists
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('events', 'event_date')) {
            $query->whereDate(
                'event_date',
                '>=',
                now()->toDateString()
            );

            $query->orderBy('event_date');
        } elseif (Schema::hasColumn('events', 'start_date')) {
            $query->whereDate(
                'start_date',
                '>=',
                now()->toDateString()
            );

            $query->orderBy('start_date');
        } else {
            $query->latest();
        }

        return $query
            ->take(3)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Mentorship
    |--------------------------------------------------------------------------
    */

    private function getMentorshipRequests(int $userId): int
    {
        return \App\Services\DashboardActivityService::connections(auth()->user())->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    private function getUnreadMessages(int $userId): int
    {
        return \App\Services\DashboardActivityService::unread(auth()->user())->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Dashboard helpers
    |--------------------------------------------------------------------------
    */


}