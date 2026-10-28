<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationContributionController;
use App\Http\Controllers\DonationManualPaymentController;
use App\Http\Controllers\NewsfeedController;
use App\Http\Controllers\FeedActionController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\StripeDonationController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\AlumniWorkExperienceController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VerificationController;

use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\VerifiedUserController as SuperAdminVerifiedUserController;
use App\Http\Controllers\SuperAdmin\SuperAdminManagementController;

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboardController;
use App\Http\Controllers\AlumniConversionController;
use App\Http\Controllers\SecureFileController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check() && in_array(auth()->user()->role, ['student', 'alumni', 'admin', 'super_admin'], true)) {
        return redirect()->route('dashboard')->header('Cache-Control', 'private, no-store');
    }

    $homeStats = [

        'students' => User::where('role', 'student')->count(),

        'alumni' => User::where('role', 'alumni')->count(),

        'jobs' => Schema::hasTable('job_postings')
            ? DB::table('job_postings')->count()
            : (
                Schema::hasTable('jobs')
                    ? DB::table('jobs')->count()
                    : 0
            ),

        'events' => Schema::hasTable('events')
            ? DB::table('events')->count()
            : 0,

        'admins' => User::whereIn(
            'role',
            ['admin', 'super_admin']
        )->count(),

        'verified_users' => User::where('is_active', true)
            ->where('is_blocked', false)
            ->count(),
    ];

    return view(
        'welcome',
        compact('homeStats')
    );

})->name('home');

// Helpful information for commonly typed addresses; existing feature routes stay intact.
foreach (array_keys(\App\Http\Controllers\PageInformationController::TOPICS) as $informationPath) {
    Route::get('/'.$informationPath, \App\Http\Controllers\PageInformationController::class)->name('page.information.'.$informationPath);
}


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {



     /*
|--------------------------------------------------------------------------
| Secure Encrypted Files
|--------------------------------------------------------------------------
*/

Route::get(
    '/secure/profile/{user}/image',
    [SecureFileController::class, 'profileImage']
)->name('secure.profile.image');


Route::get(
    '/secure/donations/{donation}/image',
    [SecureFileController::class, 'donationImage']
)->name('secure.donations.image');

Route::get(
    '/secure/events/{event}/cover',
    [SecureFileController::class, 'eventCover']
)->name('secure.events.cover');


Route::get(
    '/secure/donation-payments/{payment}/screenshot',
    [SecureFileController::class, 'paymentScreenshot']
)->name('secure.donation-payments.screenshot');


Route::get(
    '/secure/messages/{message}/attachment',
    [SecureFileController::class, 'messageAttachment']
)->name('secure.messages.attachment');
    /*
    |--------------------------------------------------------------------------
    | Stripe Donation Routes
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/donations/{donation}/stripe/checkout',
        [StripeDonationController::class, 'checkout']
    )->name('donations.stripe.checkout');


    Route::get(
        '/donations/{donation}/stripe/success',
        [StripeDonationController::class, 'success']
    )->name('donations.stripe.success');


    Route::get(
        '/donations/{donation}/stripe/cancel',
        [StripeDonationController::class, 'cancel']
    )->name('donations.stripe.cancel');


    /*
    |--------------------------------------------------------------------------
    | Public Profiles
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profiles/alumni/{user}',
        [PublicProfileController::class, 'alumni']
    )->name('profiles.alumni.show');


    Route::get(
        '/profiles/student/{user}',
        [PublicProfileController::class, 'student']
    )->name('profiles.student.show');


    /*
    |--------------------------------------------------------------------------
    | Main Dashboard Redirect
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        $user = auth()->user();

        if ($user->is_blocked) {

            auth()->guard()->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account has been blocked.',
                ]);
        }

        if (!$user->is_active) {

            auth()->guard()->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account is not active yet.',
                ]);
        }

        return match ($user->role) {

            'super_admin' => redirect()
                ->route('superadmin.dashboard'),

            'admin' => redirect()
                ->route('admin.dashboard'),

            'student' => redirect()
                ->route('student.dashboard'),

            'alumni' => redirect()
                ->route('alumni.dashboard'),

            default => redirect()
                ->route('home'),
        };

    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Alumni Conversion System
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:student'])
        ->group(function () {

            Route::get(
                '/alumni-conversion/apply',
                [AlumniConversionController::class, 'create']
            )->name('alumni-conversion.create');


            Route::post(
                '/alumni-conversion/apply',
                [AlumniConversionController::class, 'store']
            )->name('alumni-conversion.store');

        });


    Route::middleware(['role:admin,super_admin'])
        ->group(function () {

            Route::get(
                '/alumni-conversion/requests',
                [AlumniConversionController::class, 'index']
            )->name('alumni-conversion.index');


            Route::patch(
                '/alumni-conversion/{conversionRequest}/approve',
                [AlumniConversionController::class, 'approve']
            )->name('alumni-conversion.approve');


            Route::patch(
                '/alumni-conversion/{conversionRequest}/reject',
                [AlumniConversionController::class, 'reject']
            )->name('alumni-conversion.reject');

        });


    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Super Admin Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [AdminDashboardController::class, 'index']
            )->name('dashboard');


            Route::post(
                '/generate-ai-report',
                [AdminDashboardController::class, 'generateAiReport']
            )->name('generate-ai-report');

 /*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/users',
    [UserManagementController::class, 'index']
)->name('users.index');


Route::get(
    '/users/{user}/edit',
    [UserManagementController::class, 'edit']
)->name('users.edit');


Route::patch(
    '/users/{user}',
    [UserManagementController::class, 'update']
)->name('users.update');


Route::patch(
    '/users/{user}/block',
    [UserManagementController::class, 'block']
)->name('users.block');


Route::patch(
    '/users/{user}/unblock',
    [UserManagementController::class, 'unblock']
)->name('users.unblock');


Route::delete(
    '/users/{user}',
    [UserManagementController::class, 'destroy']
)->name('users.destroy');
            /*
            |--------------------------------------------------------------------------
            | Verified Users
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/verified-users',
                [SuperAdminVerifiedUserController::class, 'index']
            )->name('verified-users.index');


            Route::post(
                '/verified-users',
                [SuperAdminVerifiedUserController::class, 'store']
            )->name('verified-users.store');


            Route::post(
                '/verified-users/bulk-store',
                [SuperAdminVerifiedUserController::class, 'bulkStore']
            )->name('verified-users.bulk-store');


            Route::post(
                '/verified-users/bulk-preview',
                [SuperAdminVerifiedUserController::class, 'bulkPreview']
            )->name('verified-users.bulk-preview');


            Route::post(
                '/verified-users/bulk-confirm',
                [SuperAdminVerifiedUserController::class, 'bulkConfirm']
            )->name('verified-users.bulk-confirm');


            Route::delete(
                '/verified-users/{verifiedUser}',
                [SuperAdminVerifiedUserController::class, 'destroy']
            )->name('verified-users.destroy');


            /*
            |--------------------------------------------------------------------------
            | Create General Admin
            |--------------------------------------------------------------------------
            */

          Route::middleware(['role:super_admin'])
    ->group(function () {

        Route::get(
            '/admins/create',
            [AdminController::class, 'create']
        )->name('admins.create');

        Route::post(
            '/admins',
            [AdminController::class, 'store']
        )->name('admins.store');

    });


            /*
            |--------------------------------------------------------------------------
            | User Management
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/users',
                [UserManagementController::class, 'index']
            )->name('users.index');


            Route::patch(
                '/users/{user}/block',
                [UserManagementController::class, 'block']
            )->name('users.block');


            Route::patch(
                '/users/{user}/unblock',
                [UserManagementController::class, 'unblock']
            )->name('users.unblock');


            Route::delete(
                '/users/{user}',
                [UserManagementController::class, 'destroy']
            )->name('users.destroy');


            /*
            |--------------------------------------------------------------------------
            | Verification
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/verification',
                [VerificationController::class, 'index']
            )->name('verification.index');


            Route::patch(
                '/verification/{user}/approve',
                [VerificationController::class, 'approve']
            )->name('verification.approve');


            Route::patch(
                '/verification/{user}/reject',
                [VerificationController::class, 'reject']
            )->name('verification.reject');


            /*
            |--------------------------------------------------------------------------
            | OWNER SUPER ADMIN ONLY
            |--------------------------------------------------------------------------
            |
            | Only the current Owner Super Admin can:
            |
            | - View all Super Admins
            | - Add another Super Admin
            | - Transfer ownership
            | - Remove a normal Super Admin
            |
            */

            Route::middleware(['owner'])
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Super Admin Management
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/super-admins',
                        [SuperAdminManagementController::class, 'index']
                    )->name('super-admins.index');


                    Route::get(
                        '/super-admins/create',
                        [SuperAdminManagementController::class, 'create']
                    )->name('super-admins.create');


                    Route::post(
                        '/super-admins',
                        [SuperAdminManagementController::class, 'store']
                    )->name('super-admins.store');


                    /*
                    |--------------------------------------------------------------------------
                    | Transfer Ownership
                    |--------------------------------------------------------------------------
                    */

                    Route::patch(
                        '/super-admins/{user}/transfer-ownership',
                        [SuperAdminManagementController::class, 'transferOwnership']
                    )->name('super-admins.transfer-ownership');


                    /*
                    |--------------------------------------------------------------------------
                    | Remove Super Admin
                    |--------------------------------------------------------------------------
                    */

                    Route::delete(
                        '/super-admins/{user}',
                        [SuperAdminManagementController::class, 'destroy']
                    )->name('super-admins.destroy');

                });

        });


    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| General Admin Routes
|--------------------------------------------------------------------------
|
| General Admin can use the main management features available to
| Super Admin, except:
|
| - Create General Admin
| - Create Super Admin
| - Manage Super Admins
| - Transfer Ownership
|
*/

Route::middleware(['role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');


        Route::post(
            '/generate-ai-report',
            [AdminDashboardController::class, 'generateAiReport']
        )->name('generate-ai-report');


        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users',
            [UserManagementController::class, 'index']
        )->name('users.index');


        Route::get(
            '/users/{user}/edit',
            [UserManagementController::class, 'edit']
        )->name('users.edit');


        Route::patch(
            '/users/{user}',
            [UserManagementController::class, 'update']
        )->name('users.update');


        Route::patch(
            '/users/{user}/block',
            [UserManagementController::class, 'block']
        )->name('users.block');


        Route::patch(
            '/users/{user}/unblock',
            [UserManagementController::class, 'unblock']
        )->name('users.unblock');


        Route::delete(
            '/users/{user}',
            [UserManagementController::class, 'destroy']
        )->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | User Verification
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verification',
            [VerificationController::class, 'index']
        )->name('verification.index');


        Route::patch(
            '/verification/{user}/approve',
            [VerificationController::class, 'approve']
        )->name('verification.approve');


        Route::patch(
            '/verification/{user}/reject',
            [VerificationController::class, 'reject']
        )->name('verification.reject');


        /*
        |--------------------------------------------------------------------------
        | Verified Student / Alumni Database
        |--------------------------------------------------------------------------
        |
        | General Admin can manage verified Student and Alumni records.
        | They cannot create Admin or Super Admin accounts.
        |
        */

        Route::get(
            '/verified-users',
            [SuperAdminVerifiedUserController::class, 'index']
        )->name('verified-users.index');


        Route::post(
            '/verified-users',
            [SuperAdminVerifiedUserController::class, 'store']
        )->name('verified-users.store');


        Route::post(
            '/verified-users/bulk-store',
            [SuperAdminVerifiedUserController::class, 'bulkStore']
        )->name('verified-users.bulk-store');


        Route::post(
            '/verified-users/bulk-preview',
            [SuperAdminVerifiedUserController::class, 'bulkPreview']
        )->name('verified-users.bulk-preview');


        Route::post(
            '/verified-users/bulk-confirm',
            [SuperAdminVerifiedUserController::class, 'bulkConfirm']
        )->name('verified-users.bulk-confirm');


        Route::delete(
            '/verified-users/{verifiedUser}',
            [SuperAdminVerifiedUserController::class, 'destroy']
        )->name('verified-users.destroy');

    });
     /*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
|
| Student Dashboard
|
*/

Route::middleware(['role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Student Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [StudentDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Student Dashboard
        |--------------------------------------------------------------------------
        */

    });

    /*
    |--------------------------------------------------------------------------
    | Alumni Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:alumni'])
        ->prefix('alumni')
        ->name('alumni.')
        ->group(function () {

            Route::get(
                '/dashboard',
                [AlumniDashboardController::class, 'index']
            )->name('dashboard');

        });


    /*
    |--------------------------------------------------------------------------
    | Community pages
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Newsfeed
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/newsfeed',
        [NewsfeedController::class, 'index']
    )->name('newsfeed.index');

    Route::get('/dashboard/activity/{section}', [\App\Http\Controllers\DashboardActivityController::class, 'index'])->whereIn('section', ['events','mentorships','unread'])->middleware('role:student,alumni')->name('dashboard.activity');

    Route::get('/donations/{donation}/management', [DonationController::class, 'management'])->name('donations.management');

    Route::patch('/newsfeed/interests', [\App\Http\Controllers\FeedInterestController::class, 'update'])
        ->middleware('role:student,alumni')->name('newsfeed.interests.update');

    Route::get('/posts/archive', [\App\Http\Controllers\PostLifecycleController::class, 'index'])->name('posts.archive');
    Route::patch('/posts/extensions/{extension}/review', [\App\Http\Controllers\PostLifecycleController::class, 'review'])->middleware('role:admin,super_admin')->name('posts.extension.review');
    Route::patch('/posts/{type}/{id}/archive', [\App\Http\Controllers\PostLifecycleController::class, 'archive'])->whereIn('type', ['job','event','donation'])->whereNumber('id')->name('posts.archive.store');
    Route::get('/posts/{type}/{id}/extension', [\App\Http\Controllers\PostLifecycleController::class, 'form'])->whereIn('type', ['job','event','donation'])->whereNumber('id')->name('posts.extension.form');
    Route::post('/posts/{type}/{id}/extension', [\App\Http\Controllers\PostLifecycleController::class, 'apply'])->whereIn('type', ['job','event','donation'])->whereNumber('id')->name('posts.extension.apply');

    Route::get('/posts/{type}/{id}/edit', [\App\Http\Controllers\PostManagementController::class, 'edit'])
        ->whereIn('type', ['job', 'event', 'donation'])->whereNumber('id')->name('posts.edit');
    Route::patch('/posts/{type}/{id}', [\App\Http\Controllers\PostManagementController::class, 'update'])
        ->whereIn('type', ['job', 'event', 'donation'])->whereNumber('id')->name('posts.update');
    Route::delete('/posts/{type}/{id}', [\App\Http\Controllers\PostManagementController::class, 'destroy'])
        ->whereIn('type', ['job', 'event', 'donation'])->whereNumber('id')->name('posts.destroy');


    Route::post(
        '/newsfeed/like',
        [FeedActionController::class, 'like']
    )->name('newsfeed.like');


    Route::post(
        '/newsfeed/comment',
        [FeedActionController::class, 'comment']
    )->name('newsfeed.comment');


    Route::post(
        '/newsfeed/share',
        [FeedActionController::class, 'share']
    )->name('newsfeed.share');


    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/messages',
        [MessageController::class, 'index']
    )->name('messages.index');


    Route::get(
        '/messages/{user}',
        [MessageController::class, 'show']
    )->name('messages.show');


    Route::post(
        '/messages/{user}',
        [MessageController::class, 'store']
    )->name('messages.store');


    Route::patch(
        '/messages/message/{message}',
        [MessageController::class, 'update']
    )->name('messages.update');


    Route::delete(
        '/messages/message/{message}',
        [MessageController::class, 'destroy']
    )->name('messages.destroy');


    /*
    |--------------------------------------------------------------------------
    | Calls
    |--------------------------------------------------------------------------
    */

    Route::get('/calls/incoming', [CallController::class, 'incoming'])->middleware('role:student,alumni')->name('calls.incoming');

    Route::post(
        '/calls/start/{user}',
        [CallController::class, 'start']
    )->name('calls.start');


    Route::get(
        '/calls/{call}',
        [CallController::class, 'show']
    )->name('calls.show');


    Route::post(
        '/calls/{call}/offer',
        [CallController::class, 'storeOffer']
    )->name('calls.offer');


    Route::post(
        '/calls/{call}/answer',
        [CallController::class, 'storeAnswer']
    )->name('calls.answer');


    Route::post(
        '/calls/{call}/candidate',
        [CallController::class, 'storeCandidate']
    )->name('calls.candidate');


    Route::get(
        '/calls/{call}/poll',
        [CallController::class, 'poll']
    )->name('calls.poll');


    Route::post(
        '/calls/{call}/accept',
        [CallController::class, 'accept']
    )->name('calls.accept');


    Route::post(
        '/calls/{call}/reject',
        [CallController::class, 'reject']
    )->name('calls.reject');


    Route::post(
        '/calls/{call}/end',
        [CallController::class, 'end']
    )->name('calls.end');


    /*
    |--------------------------------------------------------------------------
    | Donations
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/donations',
        [DonationController::class, 'index']
    )->name('donations.index');


    Route::get(
        '/donations/create',
        [DonationController::class, 'create']
    )->name('donations.create');


    Route::post(
        '/donations',
        [DonationController::class, 'store']
    )->name('donations.store');


    Route::get(
        '/donations/{donation}',
        [DonationController::class, 'show']
    )->name('donations.show')->missing(function (\Illuminate\Http\Request $request) {
        return redirect()->route($request->user() ? 'newsfeed.index' : 'login')->with('success', 'This post is no longer available. It may have been deleted.');
    });


    Route::patch(
        '/donations/{donation}/approve',
        [DonationController::class, 'approve']
    )->name('donations.approve');


    Route::patch(
        '/donations/{donation}/reject',
        [DonationController::class, 'reject']
    )->name('donations.reject');


    Route::delete(
        '/donations/{donation}',
        [DonationController::class, 'destroy']
    )->name('donations.destroy');


    Route::post(
        '/donations/{donation}/contribute',
        [DonationContributionController::class, 'store']
    )->name('donations.contribute');


    Route::post(
        '/donations/{donation}/manual-payment',
        [DonationManualPaymentController::class, 'store']
    )->name('donations.manual-payment');
/*
|--------------------------------------------------------------------------
| Donation Payment Verification
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
])->group(function () {

    Route::get(
        '/donation-payments/pending',
        [DonationManualPaymentController::class, 'pending']
    )->name('donation-payments.pending');


    Route::patch(
        '/donation-payments/{payment}/approve',
        [DonationManualPaymentController::class, 'approve']
    )->name('donation-payments.approve');


    Route::patch(
        '/donation-payments/{payment}/reject',
        [DonationManualPaymentController::class, 'reject']
    )->name('donation-payments.reject');

});

    /*
    |--------------------------------------------------------------------------
    | Resume Analyzer
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:student,alumni'])
        ->group(function () {

        });

/*
|--------------------------------------------------------------------------
| Jobs
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Job List
|--------------------------------------------------------------------------
|
| All authenticated users can view jobs.
|
*/

Route::get(
    '/jobs',
    [JobController::class, 'index']
)->name('jobs.index');


/*
|--------------------------------------------------------------------------
| Create / Store / Manage Own Jobs
|--------------------------------------------------------------------------
|
| Alumni:
| - Can submit jobs
| - Submitted jobs go for approval
|
| Admin / Super Admin:
| - Can manually add jobs
| - Admin-created jobs can be published directly
|
*/

Route::middleware([
    'role:alumni,admin,super_admin',
])->group(function () {

    Route::get(
        '/jobs/create',
        [JobController::class, 'create']
    )->name('jobs.create');


    Route::post(
        '/jobs',
        [JobController::class, 'store']
    )->name('jobs.store');


    Route::get(
        '/jobs/my-posts',
        [JobController::class, 'myJobs']
    )->name('jobs.my');

});




/*
|--------------------------------------------------------------------------
| Admin / Super Admin Job Moderation
|--------------------------------------------------------------------------
*/

Route::middleware([
    'role:admin,super_admin',
])->group(function () {

    Route::patch(
        '/jobs/{job}/approve',
        [JobController::class, 'approve']
    )->name('jobs.approve');


    Route::patch(
        '/jobs/{job}/reject',
        [JobController::class, 'reject']
    )->name('jobs.reject');

    Route::delete(
        '/jobs/{job}',
        [JobController::class, 'destroy']
    )->name('jobs.destroy');

});


/*
|--------------------------------------------------------------------------
| Job Details
|--------------------------------------------------------------------------
|
| Important:
| Keep this route after /jobs/create and /jobs/my-posts.
|
*/

Route::get(
    '/jobs/{job}',
    [JobController::class, 'show']
)->name('jobs.show')->missing(function (\Illuminate\Http\Request $request) {
        return redirect()->route($request->user() ? 'newsfeed.index' : 'login')->with('success', 'This post is no longer available. It may have been deleted.');
    });


/*
|--------------------------------------------------------------------------
| Mentorship
|--------------------------------------------------------------------------
*/

Route::middleware(['role:student,alumni'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Mentor List
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/alumni-mentors',
            [MentorshipController::class, 'index']
        )->name('mentors.index');


        /*
        |--------------------------------------------------------------------------
        | Request Mentorship
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/alumni-mentors/{mentor}/request',
            [MentorshipController::class, 'requestMentor']
        )->name('mentors.request');


        /*
        |--------------------------------------------------------------------------
        | Cancel Pending Request
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/mentors/{mentor}/cancel',
            [MentorshipController::class, 'cancelRequest']
        )->name('mentors.cancel');

    });


Route::middleware(['role:alumni'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Student Requests
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/mentorship-requests',
            [MentorshipController::class, 'myRequests']
        )->name('mentors.requests');


        /*
        |--------------------------------------------------------------------------
        | Accept
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/mentorships/{mentorship}/accept',
            [MentorshipController::class, 'accept']
        )->name('mentors.accept');


        /*
        |--------------------------------------------------------------------------
        | Reject
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/mentorships/{mentorship}/reject',
            [MentorshipController::class, 'reject']
        )->name('mentors.reject');


        /*
        |--------------------------------------------------------------------------
        | Complete
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/mentorships/{mentorship}/complete',
            [MentorshipController::class, 'complete']
        )->name('mentors.complete');

    });

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/events',
        [EventController::class, 'index']
    )->name('events.index');


    Route::middleware(['role:alumni,admin,super_admin'])
        ->group(function () {

            Route::get(
                '/events/create',
                [EventController::class, 'create']
            )->name('events.create');


            Route::post(
                '/events',
                [EventController::class, 'store']
            )->name('events.store');

        });


    Route::get('/events/{event}/participants', [EventController::class, 'participants'])->name('events.participants');
    Route::delete('/events/{event}/participants/{participant}', [EventController::class, 'cancelRegisteredParticipant'])->whereNumber('participant')->name('events.participants.cancel');
    Route::delete('/events/{event}/participation', [EventController::class, 'cancelParticipation'])->middleware('role:student,alumni')->name('events.participation.cancel');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show')->missing(function (\Illuminate\Http\Request $request) {
        return redirect()->route($request->user() ? 'newsfeed.index' : 'login')->with('success', 'This post is no longer available. It may have been deleted.');
    });

    Route::middleware(['role:student,alumni'])
        ->group(function () {

            Route::post(
                '/events/{event}/register',
                [EventController::class, 'register']
            )->name('events.register');

        });


    Route::middleware(['role:admin,super_admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Event Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/events/{event}/edit',
            [EventController::class, 'edit']
        )->name('events.edit');


        Route::patch(
            '/events/{event}',
            [EventController::class, 'update']
        )->name('events.update');


        Route::delete(
            '/events/{event}',
            [EventController::class, 'destroy']
        )->name('events.destroy');


        /*
        |--------------------------------------------------------------------------
        | Event Participant Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/admin/event-participants/pending',
            [EventController::class, 'pendingParticipants']
        )->name('event.participants.pending');


        Route::patch(
            '/event-participants/{participant}/approve',
            [EventController::class, 'approveParticipant']
        )->name('event.participants.approve');


        Route::patch(
            '/event-participants/{participant}/reject',
            [EventController::class, 'rejectParticipant']
        )->name('event.participants.reject');

    });


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');


    Route::patch(
        '/notifications/{notification}/read',
        [NotificationController::class, 'markAsRead']
    )->name('notifications.read');


    Route::patch(
        '/notifications/read-all',
        [NotificationController::class, 'markAllAsRead']
    )->name('notifications.readAll');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});
/*
|--------------------------------------------------------------------------
| Alumni Work Experience
|--------------------------------------------------------------------------
*/

Route::middleware([
    'role:alumni',
])->group(function () {

    Route::post(
        '/profile/work-experiences',
        [
            AlumniWorkExperienceController::class,
            'store',
        ]
    )->name(
        'profile.work-experiences.store'
    );

    Route::patch(
        '/profile/work-experiences/{experience}',
        [
            AlumniWorkExperienceController::class,
            'update',
        ]
    )->name(
        'profile.work-experiences.update'
    );

    Route::delete(
        '/profile/work-experiences/{experience}',
        [
            AlumniWorkExperienceController::class,
            'destroy',
        ]
    )->name(
        'profile.work-experiences.destroy'
    );
});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

Route::post('/payments/stripe/webhook', \App\Http\Controllers\DonationWebhookController::class)->name('donations.stripe.webhook');
