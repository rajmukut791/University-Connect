<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display Admin / Super Admin dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total_users' => User::count(),

            'students' => User::where('role', 'student')->count(),

            'alumni' => User::where('role', 'alumni')->count(),

            'job_postings' => Schema::hasTable('job_postings')
                ? DB::table('job_postings')->count()
                : 0,

            'events' => Schema::hasTable('events')
                ? DB::table('events')->count()
                : 0,

            'mentorships' => Schema::hasTable('mentorships')
                ? DB::table('mentorships')->count()
                : 0,
        ];


        /*
        |--------------------------------------------------------------------------
        | Recent Users
        |--------------------------------------------------------------------------
        */

        $countPending = fn (string $table) => Schema::hasTable($table)
            ? DB::table($table)->where('status', 'pending')->count() : 0;
        $pendingOverview = [
            ['label'=>'Alumni Conversion', 'count'=>$countPending('alumni_conversion_requests'), 'url'=>route('alumni-conversion.index')],
            ['label'=>'Job Approvals', 'count'=>$countPending('job_postings'), 'url'=>route('jobs.index', ['status'=>'pending'])],
            ['label'=>'Campaign Approvals', 'count'=>$countPending('donations'), 'url'=>route('donations.index', ['status'=>'pending'])],
        ];
        foreach (['job'=>'Job', 'event'=>'Event', 'donation'=>'Donation'] as $type=>$label) {
            $pendingOverview[] = ['label'=>$label.' Extensions', 'count'=>Schema::hasTable('post_extension_requests') ? DB::table('post_extension_requests')->where('status','pending')->where('post_type',$type)->count() : 0, 'url'=>route('posts.archive',['type'=>$type])];
        }

        return view('admin.dashboard', compact(
            'stats',
            'pendingOverview'
        ));
    }
}