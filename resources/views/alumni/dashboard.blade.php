<x-app-layout>

    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <x-slot name="header">

        <div class="relative overflow-hidden
                    rounded-3xl
                    bg-gradient-to-r
                    from-slate-950
                    via-purple-950
                    to-amber-950
                    p-5
                    shadow-2xl
                    border border-white/10">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.40),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(245,158,11,.30),transparent_35%)]">
            </div>

            <div class="relative z-10
                        flex flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-6">

                <div>

                    <p class="text-sm
                              uppercase
                              tracking-[0.35em]
                              text-yellow-300
                              font-black">

                        Alumni Career Hub

                    </p>

                    <h2 class="mt-3
                               text-2xl
                               lg:text-2xl
                               font-black
                               text-white">

                        Welcome Back,
                        {{ $user->name }}

                    </h2>

                    <p class="mt-3
                              text-slate-300
                              max-w-2xl">

                        Share opportunities, support students,
                        manage mentorship requests and stay
                        connected with your university community.

                    </p>

                </div>


                <div class="rounded-2xl
                            border border-white/10
                            bg-white/10
                            backdrop-blur-xl
                            px-6 py-4">

                    <p class="text-xs text-slate-300">
                        Profile Completion
                    </p>

                    <p class="uc-header-context mt-1
                              text-2xl
                              font-black
                              text-emerald-300">

                        {{ $profileScore ?? 0 }}%

                    </p>

                </div>

            </div>

        </div>

    </x-slot>


    {{-- ============================================================= --}}
    {{-- STYLE --}}
    {{-- ============================================================= --}}

    <style>

        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.14);
            background:
                linear-gradient(
                    135deg,
                    rgba(255,255,255,.14),
                    rgba(255,255,255,.05)
                );
            backdrop-filter: blur(20px);
            box-shadow:
                0 20px 60px rgba(15,23,42,.18);
            transition:
                transform .3s ease,
                border-color .3s ease;
        }

        .uc-card:hover {
            transform: translateY(-4px);
            border-color: rgba(168,85,247,.35);
        }

        .quick-action {
            transition:
                transform .25s ease,
                border-color .25s ease;
        }

        .quick-action:hover {
            transform: translateY(-3px);
        }

    </style>


    <div class="uc-alumni-dashboard space-y-8">


        {{-- ========================================================= --}}
        {{-- TOP STATISTICS --}}
        {{-- ========================================================= --}}

        <div class="grid
                    grid-cols-1
                    sm:grid-cols-2
                    xl:grid-cols-4
                    gap-6">


            {{-- MY JOBS --}}

            <div class="uc-card p-6">

                <div class="flex
                            items-center
                            justify-between">

                    <div>

                        <p class="text-sm
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-300">

                            My Job Posts

                        </p>

                        <h3 class="mt-3
                                   text-4xl
                                   font-black
                                   text-purple-500">

                            {{ $stats['my_jobs'] ?? 0 }}

                        </h3>

                        <p class="mt-2
                                  text-xs
                                  text-slate-500">

                            {{ $stats['approved_jobs'] ?? 0 }}
                            approved

                        </p>

                    </div>


                    <div class="h-14 w-14
                                rounded-2xl
                                bg-purple-500/15
                                flex items-center
                                justify-center
                                text-purple-500
                                text-xl">

                        <i class="fas fa-briefcase"></i>

                    </div>

                </div>

            </div>


            {{-- MENTORSHIP --}}

            <a href="{{ route('dashboard.activity', ['section'=>'mentorships']) }}" class="uc-card block p-6">

                <div class="flex
                            items-center
                            justify-between">

                    <div>

                        <p class="text-sm
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-300">

                            My Mentees

                        </p>

                        <h3 class="mt-3
                                   text-4xl
                                   font-black
                                   text-amber-500">

                            {{ $stats['mentorship_requests'] ?? 0 }}

                        </h3>

                        <p class="mt-2
                                  text-xs
                                  text-slate-500">

                            {{ $stats['pending_mentorships'] ?? 0 }}
                            pending

                        </p>

                    </div>


                    <div class="h-14 w-14
                                rounded-2xl
                                bg-amber-500/15
                                flex items-center
                                justify-center
                                text-amber-500
                                text-xl">

                        <i class="fas fa-handshake"></i>

                    </div>

                </div>

            </a>


            {{-- MESSAGES --}}

            <a href="{{ route('dashboard.activity', ['section'=>'unread']) }}" class="uc-card block p-6">

                <div class="flex
                            items-center
                            justify-between">

                    <div>

                        <p class="text-sm
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-300">

                            Unread Messages

                        </p>

                        <h3 class="mt-3
                                   text-4xl
                                   font-black
                                   text-cyan-500">

                            {{ $stats['unread_messages'] ?? 0 }}

                        </h3>

                        <p class="mt-2
                                  text-xs
                                  text-slate-500">

                            New conversations

                        </p>

                    </div>


                    <div class="h-14 w-14
                                rounded-2xl
                                bg-cyan-500/15
                                flex items-center
                                justify-center
                                text-cyan-500
                                text-xl">

                        <i class="fas fa-message"></i>

                    </div>

                </div>

            </a>


            {{-- NOTIFICATIONS --}}

            <div class="uc-card p-6">

                <div class="flex
                            items-center
                            justify-between">

                    <div>

                        <p class="text-sm
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-300">

                            Notifications

                        </p>

                        <h3 class="mt-3
                                   text-4xl
                                   font-black
                                   text-pink-500">

                            {{ $stats['unread_notifications'] ?? 0 }}

                        </h3>

                        <p class="mt-2
                                  text-xs
                                  text-slate-500">

                            Unread updates

                        </p>

                    </div>


                    <div class="h-14 w-14
                                rounded-2xl
                                bg-pink-500/15
                                flex items-center
                                justify-center
                                text-pink-500
                                text-xl">

                        <i class="fas fa-bell"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="uc-card p-7">

            <div class="mb-6">

                <p class="text-sm
                          uppercase
                          tracking-[0.25em]
                          text-purple-500
                          font-black">

                    Quick Actions

                </p>

                <h3 class="mt-1
                           text-2xl
                           font-black
                           text-slate-900
                           dark:text-white">

                    Alumni Tools

                </h3>

                <p class="mt-2
                          text-sm
                          text-slate-500">

                    Access your most important alumni features.

                </p>

            </div>


            <div class="grid
                        grid-cols-1
                        sm:grid-cols-2
                        xl:grid-cols-4
                        gap-4">


                <a href="{{ route('jobs.create') }}"
                   class="quick-action
                          rounded-2xl
                          border border-purple-500/20
                          bg-purple-500/10
                          p-5">

                    <div class="h-11 w-11
                                rounded-xl
                                bg-purple-500
                                text-white
                                flex items-center
                                justify-center">

                        <i class="fas fa-plus"></i>

                    </div>

                    <h4 class="mt-4
                               font-black
                               text-slate-900
                               dark:text-white">

                        Post a Job

                    </h4>

                    <p class="mt-1
                              text-xs
                              text-slate-500">

                        Share career opportunities.

                    </p>

                </a>


                <a href="{{ route('mentors.requests') }}"
                   class="quick-action
                          rounded-2xl
                          border border-amber-500/20
                          bg-amber-500/10
                          p-5">

                    <div class="h-11 w-11
                                rounded-xl
                                bg-amber-500
                                text-white
                                flex items-center
                                justify-center">

                        <i class="fas fa-handshake"></i>

                    </div>

                    <h4 class="mt-4
                               font-black
                               text-slate-900
                               dark:text-white">

                        Mentorship Requests

                    </h4>

                    <p class="mt-1
                              text-xs
                              text-slate-500">

                        Review student requests.

                    </p>

                </a>


                <a href="{{ route('events.index') }}"
                   class="quick-action
                          rounded-2xl
                          border border-pink-500/20
                          bg-pink-500/10
                          p-5">

                    <div class="h-11 w-11
                                rounded-xl
                                bg-pink-500
                                text-white
                                flex items-center
                                justify-center">

                        <i class="fas fa-calendar-days"></i>

                    </div>

                    <h4 class="mt-4
                               font-black
                               text-slate-900
                               dark:text-white">

                        Events

                    </h4>

                    <p class="mt-1
                              text-xs
                              text-slate-500">

                        Explore university events.

                    </p>

                </a>


                <a href="{{ route('donations.index') }}"
                   class="quick-action
                          rounded-2xl
                          border border-emerald-500/20
                          bg-emerald-500/10
                          p-5">

                    <div class="h-11 w-11
                                rounded-xl
                                bg-emerald-500
                                text-white
                                flex items-center
                                justify-center">

                        <i class="fas fa-hand-holding-heart"></i>

                    </div>

                    <h4 class="mt-4
                               font-black
                               text-slate-900
                               dark:text-white">

                        Donations

                    </h4>

                    <p class="mt-1
                              text-xs
                              text-slate-500">

                        Support university campaigns.

                    </p>

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MAIN CONTENT --}}
        {{-- ========================================================= --}}

        <div class="grid
                    grid-cols-1
                    xl:grid-cols-3
                    gap-8">


            {{-- ===================================================== --}}
            {{-- LEFT --}}
            {{-- ===================================================== --}}

            <div class="xl:col-span-2
                        space-y-8">


                {{-- ================================================= --}}
                {{-- MY JOB POSTS --}}
                {{-- ================================================= --}}

                <div class="uc-card p-7">

                    <div class="flex
                                flex-col
                                md:flex-row
                                md:items-center
                                md:justify-between
                                gap-4
                                mb-7">

                        <div>

                            <p class="text-sm
                                      uppercase
                                      tracking-[0.25em]
                                      text-purple-500
                                      font-black">

                                Career Opportunities

                            </p>

                            <h3 class="mt-1
                                       text-2xl
                                       font-black
                                       text-slate-900
                                       dark:text-white">

                                My Recent Job Posts

                            </h3>

                            <p class="mt-2
                                      text-sm
                                      text-slate-500">

                                Track the status of your latest
                                job opportunities.

                            </p>

                        </div>


                        <a href="{{ route('jobs.create') }}"
                           class="inline-flex
                                  items-center
                                  justify-center
                                  gap-2
                                  px-5 py-3
                                  rounded-2xl
                                  bg-gradient-to-r
                                  from-purple-600
                                  to-pink-500
                                  text-white
                                  font-black
                                  shadow-xl
                                  hover:scale-105
                                  transition">

                            <i class="fas fa-plus"></i>

                            Post New Job

                        </a>

                    </div>


                    {{-- Job Summary --}}

                    <div class="grid
                                grid-cols-3
                                gap-3
                                mb-6">

                        <div class="rounded-2xl
                                    bg-emerald-500/10
                                    p-4
                                    text-center">

                            <p class="text-2xl
                                      font-black
                                      text-emerald-500">

                                {{ $stats['approved_jobs'] ?? 0 }}

                            </p>

                            <p class="text-xs
                                      text-slate-500
                                      mt-1">

                                Approved

                            </p>

                        </div>


                        <div class="rounded-2xl
                                    bg-amber-500/10
                                    p-4
                                    text-center">

                            <p class="text-2xl
                                      font-black
                                      text-amber-500">

                                {{ $stats['pending_jobs'] ?? 0 }}

                            </p>

                            <p class="text-xs
                                      text-slate-500
                                      mt-1">

                                Pending

                            </p>

                        </div>


                        <div class="rounded-2xl
                                    bg-red-500/10
                                    p-4
                                    text-center">

                            <p class="text-2xl
                                      font-black
                                      text-red-500">

                                {{ $stats['rejected_jobs'] ?? 0 }}

                            </p>

                            <p class="text-xs
                                      text-slate-500
                                      mt-1">

                                Rejected

                            </p>

                        </div>

                    </div>


                    <div class="space-y-4">

                        @forelse($myJobs as $job)

                            @php

                                $status =
                                    strtolower(
                                        $job->status ?? 'pending'
                                    );

                                $statusClasses =
                                    match($status) {
                                        'approved' =>
                                            'bg-emerald-500/15 text-emerald-500',

                                        'rejected' =>
                                            'bg-red-500/15 text-red-500',

                                        'closed' =>
                                            'bg-slate-500/15 text-slate-500',

                                        'filled' =>
                                            'bg-blue-500/15 text-blue-500',

                                        default =>
                                            'bg-amber-500/15 text-amber-500',
                                    };

                            @endphp


                            <div class="rounded-2xl
                                        border border-white/10
                                        bg-white/5
                                        p-5">

                                <div class="flex
                                            flex-col
                                            md:flex-row
                                            md:items-center
                                            md:justify-between
                                            gap-4">

                                    <div class="min-w-0">

                                        <div class="flex
                                                    flex-wrap
                                                    items-center
                                                    gap-2">

                                            <h4 class="text-lg
                                                       font-black
                                                       text-slate-900
                                                       dark:text-white">

                                                {{ $job->title
                                                    ?? 'Untitled Job'
                                                }}

                                            </h4>


                                            <span class="px-3 py-1
                                                         rounded-full
                                                         text-xs
                                                         font-black
                                                         {{ $statusClasses }}">

                                                {{ ucfirst($status) }}

                                            </span>

                                        </div>


                                        @if(!empty($job->company_name))

                                            <p class="mt-2
                                                      text-sm
                                                      font-semibold
                                                      text-slate-600
                                                      dark:text-slate-300">

                                                <i class="fas fa-building
                                                          mr-2
                                                          text-purple-500">
                                                </i>

                                                {{ $job->company_name }}

                                            </p>

                                        @endif


                                        <div class="mt-2
                                                    flex
                                                    flex-wrap
                                                    gap-x-4
                                                    gap-y-2
                                                    text-sm
                                                    text-slate-500">

                                            @if(!empty($job->location))

                                                <span>

                                                    <i class="fas fa-location-dot
                                                              mr-1">
                                                    </i>

                                                    {{ $job->location }}

                                                </span>

                                            @endif


                                            @if(!empty($job->type))

                                                <span>

                                                    <i class="fas fa-briefcase
                                                              mr-1">
                                                    </i>

                                                    {{ ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $job->type
                                                        )
                                                    ) }}

                                                </span>

                                            @endif


                                            @if(!empty($job->positions_available))

                                                <span>

                                                    <i class="fas fa-users
                                                              mr-1">
                                                    </i>

                                                    {{ $job->positions_available }}
                                                    vacancies

                                                </span>

                                            @endif

                                        </div>

                                    </div>


                                    <a href="{{ route(
                                                'jobs.show',
                                                $job->id
                                            ) }}"
                                       class="h-11 w-11
                                              shrink-0
                                              rounded-xl
                                              bg-slate-950
                                              text-white
                                              hover:bg-purple-600
                                              transition
                                              flex
                                              items-center
                                              justify-center">

                                        <i class="fas fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>


                        @empty

                            <div class="rounded-3xl
                                        border
                                        border-dashed
                                        border-slate-300
                                        dark:border-white/10
                                        p-10
                                        text-center">

                                <div class="mx-auto
                                            h-14 w-14
                                            rounded-2xl
                                            bg-purple-500/10
                                            flex
                                            items-center
                                            justify-center
                                            text-purple-500">

                                    <i class="fas fa-briefcase"></i>

                                </div>

                                <h4 class="mt-4
                                           font-black
                                           text-slate-900
                                           dark:text-white">

                                    No Job Posts Yet

                                </h4>

                                <p class="mt-2
                                          text-sm
                                          text-slate-500">

                                    Share a job or internship
                                    opportunity with students.

                                </p>

                                <a href="{{ route('jobs.create') }}"
                                   class="mt-5
                                          inline-flex
                                          items-center
                                          gap-2
                                          px-5 py-3
                                          rounded-2xl
                                          bg-gradient-to-r
                                          from-purple-600
                                          to-pink-500
                                          text-white
                                          font-black">

                                    <i class="fas fa-plus"></i>

                                    Post First Job

                                </a>

                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MENTORSHIP --}}
                {{-- ================================================= --}}

                <div class="uc-card p-7">

                    <div class="flex
                                flex-col
                                md:flex-row
                                md:items-center
                                md:justify-between
                                gap-4
                                mb-6">

                        <div>

                            <p class="text-sm
                                      uppercase
                                      tracking-[0.25em]
                                      text-amber-500
                                      font-black">

                                Mentorship

                            </p>

                            <h3 class="mt-1
                                       text-2xl
                                       font-black
                                       text-slate-900
                                       dark:text-white">

                                Recent Student Requests

                            </h3>

                            <p class="mt-2
                                      text-sm
                                      text-slate-500">

                                Students who requested
                                mentorship support from you.

                            </p>

                        </div>


                        <a href="{{ route('mentors.requests') }}"
                           class="px-4 py-2
                                  rounded-xl
                                  bg-amber-500/15
                                  text-amber-500
                                  font-black">

                            View All

                        </a>

                    </div>


                    <div class="grid
                                grid-cols-1
                                md:grid-cols-2
                                gap-4">

                        @forelse($mentorshipRequests as $request)

                            @php

                                $mentorStatus =
                                    strtolower(
                                        $request->status
                                        ?? 'pending'
                                    );

                                $mentorStatusClass =
                                    match($mentorStatus) {
                                        'accepted' =>
                                            'bg-emerald-500/15 text-emerald-500',

                                        'rejected' =>
                                            'bg-red-500/15 text-red-500',

                                        'completed' =>
                                            'bg-blue-500/15 text-blue-500',

                                        default =>
                                            'bg-amber-500/15 text-amber-500',
                                    };

                            @endphp


                            <div class="rounded-2xl
                                        border border-white/10
                                        bg-white/5
                                        p-5">

                                <div class="flex
                                            items-center
                                            gap-4">

                                    <div class="h-12 w-12
                                                shrink-0
                                                rounded-xl
                                                bg-gradient-to-br
                                                from-amber-500
                                                to-orange-600
                                                flex
                                                items-center
                                                justify-center
                                                text-white
                                                font-black">

                                        {{ strtoupper(
                                            substr(
                                                $request->student_name
                                                ?? 'S',
                                                0,
                                                1
                                            )
                                        ) }}

                                    </div>


                                    <div class="min-w-0">

                                        <h4 class="font-black
                                                   text-slate-900
                                                   dark:text-white
                                                   truncate">

                                            {{ $request->student_name
                                                ?? 'Student'
                                            }}

                                        </h4>


                                        <p class="text-xs
                                                  text-slate-500">

                                            {{ $request->student_department
                                                ?? 'Department not added'
                                            }}

                                            @if(!empty(
                                                $request->student_batch
                                            ))

                                                • Batch
                                                {{ $request->student_batch }}

                                            @endif

                                        </p>

                                    </div>

                                </div>


                                <div class="mt-4
                                            flex
                                            items-center
                                            justify-between
                                            gap-3">

                                    <span class="px-3 py-1
                                                 rounded-full
                                                 text-xs
                                                 font-black
                                                 {{ $mentorStatusClass }}">

                                        {{ ucfirst(
                                            $mentorStatus
                                        ) }}

                                    </span>


                                    <a href="{{ route(
                                                'mentors.requests'
                                            ) }}"
                                       class="text-sm
                                              font-black
                                              text-purple-500">

                                        Manage

                                        <i class="fas fa-arrow-right
                                                  ml-1">
                                        </i>

                                    </a>

                                </div>

                            </div>


                        @empty

                            <div class="md:col-span-2
                                        rounded-3xl
                                        border
                                        border-dashed
                                        border-slate-300
                                        dark:border-white/10
                                        p-8
                                        text-center">

                                <div class="mx-auto
                                            h-14 w-14
                                            rounded-2xl
                                            bg-amber-500/10
                                            flex
                                            items-center
                                            justify-center
                                            text-amber-500">

                                    <i class="fas fa-handshake"></i>

                                </div>

                                <p class="mt-4
                                          text-slate-500
                                          font-semibold">

                                    No mentorship requests yet.

                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT --}}
            {{-- ===================================================== --}}

            <div class="space-y-8">


                {{-- ================================================= --}}
                {{-- PROFILE STRENGTH --}}
                {{-- ================================================= --}}

                @php

                    $realProfileScore =
                        (int) (
                            $profileStrength['score']
                            ?? $profileScore
                            ?? 0
                        );

                    $realProfileScore =
                        min(
                            max(
                                $realProfileScore,
                                0
                            ),
                            100
                        );

                    $profileLevel =
                        $profileStrength['level']
                        ?? 'Improve';

                    $profileMessage =
                        $profileStrength['message']
                        ?? 'Complete your profile to improve your university network presence.';

                    $missingItems =
                        $profileStrength['missing']
                        ?? [];

                @endphp


                <div class="uc-card p-7">

                    <p class="text-sm
                              uppercase
                              tracking-[0.25em]
                              text-emerald-500
                              font-black">

                        Profile

                    </p>

                    <h3 class="mt-1
                               text-2xl
                               font-black
                               text-slate-900
                               dark:text-white">

                        Profile Strength

                    </h3>


                    <div class="mt-6
                                flex
                                items-center
                                gap-5">

                        <div class="h-24 w-24
                                    shrink-0
                                    rounded-full
                                    bg-gradient-to-br
                                    from-emerald-400
                                    via-cyan-500
                                    to-purple-500
                                    p-1.5">

                            <div class="h-full w-full
                                        rounded-full
                                        bg-slate-950
                                        flex
                                        flex-col
                                        items-center
                                        justify-center
                                        text-white">

                                <p class="text-2xl
                                          font-black">

                                    {{ $realProfileScore }}%

                                </p>

                                <p class="text-[10px]
                                          text-slate-400">

                                    Complete

                                </p>

                            </div>

                        </div>


                        <div>

                            <p class="font-black
                                      text-slate-900
                                      dark:text-white">

                                {{ $profileLevel }}

                            </p>

                            <p class="mt-2
                                      text-sm
                                      text-slate-500
                                      leading-relaxed">

                                {{ $profileMessage }}

                            </p>

                        </div>

                    </div>


                    <div class="mt-6
                                h-2.5
                                w-full
                                rounded-full
                                bg-slate-200
                                dark:bg-slate-800
                                overflow-hidden">

                        <div class="h-full
                                    rounded-full
                                    bg-gradient-to-r
                                    from-emerald-400
                                    to-purple-500"
                             style="width: {{ $realProfileScore }}%">
                        </div>

                    </div>


                    @if(!empty($missingItems))

                        <div class="mt-5
                                    rounded-2xl
                                    bg-amber-500/10
                                    p-4">

                            <p class="text-xs
                                      uppercase
                                      tracking-[0.15em]
                                      text-amber-500
                                      font-black">

                                Profile Information Missing

                            </p>


                            <div class="mt-3
                                        flex
                                        flex-wrap
                                        gap-2">

                                @foreach(
                                    array_slice(
                                        $missingItems,
                                        0,
                                        5
                                    )
                                    as $item
                                )

                                    <span class="px-3 py-1
                                                 rounded-full
                                                 bg-amber-500/15
                                                 text-amber-500
                                                 text-xs
                                                 font-bold">

                                        {{ $item }}

                                    </span>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    <a href="{{ route('profile.edit') }}"
                       class="mt-6
                              w-full
                              inline-flex
                              items-center
                              justify-center
                              gap-2
                              rounded-2xl
                              bg-gradient-to-r
                              from-emerald-500
                              to-cyan-500
                              py-3
                              text-white
                              font-black
                              shadow-xl
                              hover:scale-[1.02]
                              transition">

                        <i class="fas fa-user-pen"></i>

                        Update Profile

                    </a>

                </div>


        </div>

    </div>

</x-app-layout>
