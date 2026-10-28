<x-app-layout>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl
                    bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950
                    p-5 shadow-2xl border border-white/10">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]">
            </div>

            <div class="absolute -top-24 -right-24 h-72 w-72
                        rounded-full bg-fuchsia-500/20 blur-3xl">
            </div>

            <div class="absolute -bottom-24 -left-24 h-72 w-72
                        rounded-full bg-cyan-500/20 blur-3xl">
            </div>


            <div class="relative z-10 flex flex-col lg:flex-row
                        lg:items-center lg:justify-between gap-6">

                <div>

                    <p class="text-sm uppercase tracking-[0.35em]
                              text-cyan-300 font-bold">

                        {{ auth()->user()->role === 'super_admin'
                            ? 'Super Admin Control'
                            : 'Administration'
                        }}

                    </p>


                    <h2 class="mt-3 text-2xl lg:text-2xl
                               font-black text-white">

                        {{ auth()->user()->role === 'super_admin'
                            ? 'Super Admin Command Center'
                            : 'Administrative Command Center'
                        }}

                    </h2>


                    <p class="mt-3 text-slate-300 max-w-2xl">

                        Manage users, jobs, events, verification
                        and university activities from one place.

                    </p>

                </div>



            </div>

        </div>

    </x-slot>


    {{-- ========================================================= --}}
    {{-- CUSTOM STYLE --}}
    {{-- ========================================================= --}}

    <style>

        @keyframes ucFloat {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

        }


        .uc-card {

            position: relative;

            overflow: hidden;

            border-radius: 1.5rem;

            border: 1px solid rgba(255,255,255,.16);

            background:
                linear-gradient(
                    135deg,
                    rgba(255,255,255,.16),
                    rgba(255,255,255,.06)
                );

            backdrop-filter: blur(22px);

            box-shadow:
                0 24px 70px rgba(15,23,42,.18);

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;

        }


        .uc-card:hover {

            transform: translateY(-4px);

            border-color: rgba(99,102,241,.35);

            box-shadow:
                0 28px 80px rgba(15,23,42,.25);

        }


        .uc-float {

            animation:
                ucFloat 5s ease-in-out infinite;

        }


        .uc-action {

            position: relative;

            overflow: hidden;

            min-height: 145px;

            border-radius: 1.4rem;

            border: 1px solid rgba(255,255,255,.10);

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;

        }


        .uc-action:hover {

            transform: translateY(-5px);

            border-color: rgba(255,255,255,.25);

            box-shadow:
                0 20px 45px rgba(15,23,42,.25);

        }


        /* Dashboard-only compact composition; all tool links remain available. */
        /* Include the sticky navbar's 12px offset, leaving a visible 12px card gap. */
        .uc-main-area:has(.uc-management-dashboard) { padding-top: 24px; padding-bottom: 20px; min-height: auto; }
        .uc-main-area:has(.uc-management-dashboard) .uc-page-hero { margin-bottom: 12px !important; }
        .uc-main-area:has(.uc-management-dashboard) .uc-page-hero > div { min-height: 56px; padding-block: 12px !important; }
        .uc-management-dashboard { display: grid; gap: 12px; }
        .uc-management-dashboard.space-y-8 > :not([hidden]) ~ :not([hidden]) { margin-top: 0 !important; }
        .uc-management-dashboard > * { margin-top: 0 !important; }
        .uc-management-dashboard .uc-management-stats { gap: 12px !important; }
        .uc-management-dashboard .uc-management-stats .uc-card { padding: 12px 14px !important; }
        .uc-management-stats h3 { margin-top: 5px !important; font-size: 24px !important; line-height: 1.1; }
        .uc-management-stats p { font-size: 12px; }
        .uc-management-stats .h-14 { width: 34px !important; height: 34px !important; animation: none; border-radius: 11px; }
        .uc-management-stats .h-14 i { font-size: 15px; }
        .uc-management-stats .h-1\.5 { height: 3px; margin-top: 10px !important; }
        .uc-management-dashboard .uc-management-tools { padding: 16px !important; }
        .uc-management-tools > div > .flex { flex-direction: row; align-items: center; margin-bottom: 12px !important; }
        .uc-management-tools > div > .flex h3 { font-size: 18px !important; line-height: 1.3; margin-top: 3px; }
        .uc-management-tools > div > .flex p { font-size: 11px; margin-top: 3px; }
        .uc-management-tools > div > .flex .h-14 { display: none; }
        .uc-management-tools > div > .grid { gap: 12px !important; }
        .uc-management-dashboard .uc-action {
            display: grid; grid-template-columns: 34px minmax(0,1fr); column-gap: 12px;
            align-content: center; min-height: 94px; padding: 14px !important;
            border-radius: 14px; box-shadow: inset 0 1px 0 #ffffff08;
        }
        .uc-management-dashboard .uc-action > .flex { grid-row: 1 / 3; align-self: center; }
        .uc-management-dashboard .uc-action > .flex > .fa-arrow-right { display: none; }
        .uc-management-dashboard .uc-action .h-12 { width: 34px !important; height: 34px !important; border-radius: 11px; }
        .uc-management-dashboard .uc-action .h-12 i { font-size: 15px; }
        .uc-management-dashboard .uc-action h4 { margin: 0 !important; font-size: 14px; line-height: 1.4; }
        .uc-management-dashboard .uc-action p { margin-top: 4px !important; font-size: 11px; line-height: 1.5; }
        @media (min-width: 1000px) {
            .uc-management-dashboard .uc-management-stats { grid-template-columns: repeat(6,minmax(0,1fr)); }
            .uc-management-tools > div > .grid { grid-template-columns: repeat(3,minmax(0,1fr)); }
        }
        @media (max-width: 639px) {
            .uc-management-dashboard .uc-management-stats { grid-template-columns: repeat(2,minmax(0,1fr)); }
        }
    </style>


    {{-- ========================================================= --}}
    {{-- ROLE / ROUTES --}}
    {{-- ========================================================= --}}

    @php

        $isSuperAdmin =
            auth()->user()->role === 'super_admin';


        $verificationRoute = $isSuperAdmin
            ? route('superadmin.verification.index')
            : route('admin.verification.index');


        $usersRoute = $isSuperAdmin
            ? route('superadmin.users.index')
            : route('admin.users.index');


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistic Cards
        |--------------------------------------------------------------------------
        */

        $cards = [

            [
                'title' => 'Total Users',
                'value' => $stats['total_users'] ?? 0,
                'icon' => 'fa-users',
                'from' => 'from-cyan-400',
                'to' => 'to-blue-600',
            ],

            [
                'title' => 'Students',
                'value' => $stats['students'] ?? 0,
                'icon' => 'fa-user-graduate',
                'from' => 'from-emerald-400',
                'to' => 'to-green-600',
            ],

            [
                'title' => 'Alumni',
                'value' => $stats['alumni'] ?? 0,
                'icon' => 'fa-award',
                'from' => 'from-amber-400',
                'to' => 'to-orange-600',
            ],

            [
                'title' => 'Jobs',
                'value' => $stats['job_postings'] ?? 0,
                'icon' => 'fa-briefcase',
                'from' => 'from-pink-400',
                'to' => 'to-rose-600',
            ],

            [
                'title' => 'Events',
                'value' => $stats['events'] ?? 0,
                'icon' => 'fa-calendar-check',
                'from' => 'from-violet-400',
                'to' => 'to-purple-700',
            ],

            [
                'title' => 'Mentorships',
                'value' => $stats['mentorships'] ?? 0,
                'icon' => 'fa-handshake-angle',
                'from' => 'from-indigo-400',
                'to' => 'to-purple-600',
            ],

        ];

    @endphp



    <div class="uc-management-dashboard space-y-8">


        {{-- ========================================================= --}}
        {{-- STATISTIC CARDS --}}
        {{-- ========================================================= --}}

        <div class="uc-management-stats grid grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-3
                    xl:grid-cols-6
                    gap-5">


            @foreach($cards as $index => $card)

                <div class="uc-card p-5">

                    <div class="relative z-10">


                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-sm font-bold
                                          text-slate-500
                                          dark:text-slate-300">

                                    {{ $card['title'] }}

                                </p>


                                <h3 class="mt-3 text-3xl font-black
                                           bg-gradient-to-r
                                           {{ $card['from'] }}
                                           {{ $card['to'] }}
                                           bg-clip-text
                                           text-transparent">

                                    {{ $card['value'] }}

                                </h3>

                            </div>


                            <div class="h-14 w-14 shrink-0
                                        rounded-2xl
                                        bg-gradient-to-br
                                        {{ $card['from'] }}
                                        {{ $card['to'] }}
                                        flex items-center
                                        justify-center
                                        shadow-xl
                                        uc-float">

                                <i class="fas {{ $card['icon'] }}
                                          text-xl text-white">
                                </i>

                            </div>

                        </div>


                        <div class="mt-5 h-1.5 rounded-full
                                    bg-white/20 overflow-hidden">

                            <div
                                class="h-full rounded-full
                                       bg-gradient-to-r
                                       {{ $card['from'] }}
                                       {{ $card['to'] }}"
                                style="width:
                                    {{ min(100, 35 + ($index * 10)) }}%">
                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>



        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="uc-management-tools uc-card p-7 lg:p-8">


            <div class="relative z-10">


                <div class="flex flex-col
                            lg:flex-row
                            lg:items-end
                            lg:justify-between
                            gap-4
                            mb-7">


                    <div>

                        <p class="text-sm uppercase
                                  tracking-[0.25em]
                                  text-fuchsia-500
                                  font-black">

                            Quick Actions

                        </p>


                        <h3 class="mt-2 text-3xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            {{ $isSuperAdmin
                                ? 'Super Admin Tools'
                                : 'Admin Tools'
                            }}

                        </h3>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Access the most important management tools
                            directly from your dashboard.

                        </p>

                    </div>


                    <div class="hidden lg:flex
                                h-14 w-14
                                rounded-2xl
                                bg-gradient-to-br
                                from-indigo-500
                                to-fuchsia-500
                                items-center
                                justify-center
                                text-white
                                shadow-xl">

                        <i class="fas fa-bolt text-xl"></i>

                    </div>

                </div>



                <div class="grid grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-3
                            gap-5">


                    {{-- User Management --}}

                    <a
                        href="{{ $usersRoute }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-violet-500/20
                               to-fuchsia-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-violet-500/20
                                        text-violet-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-users-cog text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            User Management

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Manage students, alumni and user accounts.

                        </p>

                    </a>



                    {{-- Alumni Conversion --}}

                    <a
                        href="{{ route('alumni-conversion.index') }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-cyan-500/20
                               to-emerald-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-cyan-500/20
                                        text-cyan-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-user-graduate text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Alumni Conversion

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Review student to alumni conversion requests.

                        </p>

                    </a>



                    {{-- Manage Jobs --}}

                    <a
                        href="{{ route('jobs.index') }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-pink-500/20
                               to-rose-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-pink-500/20
                                        text-pink-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-briefcase text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Manage Jobs

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Review, approve and manage job posts.

                        </p>

                    </a>



                    {{-- Manage Events --}}

                    <a
                        href="{{ route('events.index') }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-amber-500/20
                               to-orange-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-amber-500/20
                                        text-amber-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-calendar-days text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Manage Events

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Create and manage university events.

                        </p>

                    </a>



                    {{-- ================================================= --}}
                    {{-- SUPER ADMIN ONLY --}}
                    {{-- ================================================= --}}

                    @if($isSuperAdmin)


                        {{-- Verified Users DB --}}

                        <a
                            href="{{ route('superadmin.verified-users.index') }}"
                            class="uc-action
                                   bg-gradient-to-br
                                   from-blue-500/20
                                   to-cyan-500/10
                                   p-5 group"
                        >

                            <div class="flex items-start
                                        justify-between gap-4">

                                <div class="h-12 w-12
                                            rounded-2xl
                                            bg-blue-500/20
                                            text-blue-500
                                            flex items-center
                                            justify-center">

                                    <i class="fas fa-database text-xl"></i>

                                </div>


                                <i class="fas fa-arrow-right
                                          text-slate-400
                                          group-hover:translate-x-1
                                          transition-transform">
                                </i>

                            </div>


                            <h4 class="mt-5 text-lg font-black
                                       text-slate-900
                                       dark:text-white">

                                Verified Users DB

                            </h4>


                            <p class="mt-2 text-sm
                                      text-slate-500
                                      dark:text-slate-400">

                                Manage the official verified users database.

                            </p>

                        </a>



                        {{-- Create Admin --}}

                        <a
                            href="{{ route('superadmin.admins.create') }}"
                            class="uc-action
                                   bg-gradient-to-br
                                   from-orange-500/20
                                   to-amber-500/10
                                   p-5 group"
                        >

                            <div class="flex items-start
                                        justify-between gap-4">

                                <div class="h-12 w-12
                                            rounded-2xl
                                            bg-orange-500/20
                                            text-orange-500
                                            flex items-center
                                            justify-center">

                                    <i class="fas fa-user-shield text-xl"></i>

                                </div>


                                <i class="fas fa-arrow-right
                                          text-slate-400
                                          group-hover:translate-x-1
                                          transition-transform">
                                </i>

                            </div>


                            <h4 class="mt-5 text-lg font-black
                                       text-slate-900
                                       dark:text-white">

                                Create Admin

                            </h4>


                            <p class="mt-2 text-sm
                                      text-slate-500
                                      dark:text-slate-400">

                                Create and manage administrative accounts.

                            </p>

                        </a>


                    @endif


                </div>

            </div>

        </div>



    </div>
    <section class="mt-3 rounded-2xl border border-white/10 bg-slate-900/60 p-3">
        <h2 class="text-sm font-bold text-white">Pending Reviews</h2>
        <div class="mt-2 grid grid-cols-2 gap-2 lg:grid-cols-6">
            @foreach($pendingOverview as $review)
                <a href="{{ $review['url'] }}" class="flex items-center justify-between gap-2 rounded-xl border border-indigo-400/20 bg-indigo-500/5 px-3 py-2 text-[11px] text-slate-300"><span>{{ $review['label'] }}</span><strong class="text-sm text-indigo-300">{{ $review['count'] }}</strong></a>
            @endforeach
        </div>
    </section>
</x-app-layout>
