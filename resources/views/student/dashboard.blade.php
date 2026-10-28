<x-app-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | Safe Dashboard Values
        |--------------------------------------------------------------------------
        */

        $score = max(0, min(100, (int) ($profileScore ?? 0)));

        $missingItems = collect(
            $profileStrength['missing'] ?? []
        )->take(5);


    @endphp


    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <x-slot name="header">

        <div class="relative overflow-hidden
                    rounded-[30px]
                    border border-white/10
                    bg-gradient-to-r
                    from-slate-950
                    via-indigo-950
                    to-purple-950
                    px-6 py-8
                    md:px-8
                    shadow-2xl">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.18),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.18),transparent_35%)]">
            </div>


            <div class="relative z-10
                        flex flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-6">

                <div>


                    <h1 class="mt-3
                               text-2xl
                               md:text-2xl
                               font-black
                               text-white">

                        Welcome, {{ $user->name }}

                    </h1>


                    <p class="mt-3
                              max-w-2xl
                              text-sm
                              md:text-base
                              text-slate-300">

                        Explore opportunities, strengthen your profile,
                        connect with alumni mentors, and plan your next
                        career step.

                    </p>

                </div>

            </div>

        </div>

    </x-slot>



    {{-- ============================================================= --}}
    {{-- PAGE --}}
    {{-- ============================================================= --}}

    <div class="uc-student-dashboard max-w-7xl mx-auto space-y-7">


        {{-- ========================================================= --}}
        {{-- FLASH MESSAGES --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="rounded-2xl
                        border border-emerald-500/30
                        bg-emerald-500/10
                        p-4
                        text-emerald-300
                        font-bold">

                {{ session('success') }}

            </div>

        @endif


        @if($errors->any())

            <div class="rounded-2xl
                        border border-red-500/30
                        bg-red-500/10
                        p-4">

                <ul class="space-y-1
                           text-sm
                           font-bold
                           text-red-300">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- CLEAN STAT CARDS --}}
        {{-- Notifications removed --}}
        {{-- ========================================================= --}}

        <div class="uc-student-stats grid
                    grid-cols-2
                    lg:grid-cols-4
                    gap-4">


            {{-- Jobs --}}

            <a href="{{ route('jobs.index') }}"
               class="group
                      rounded-[24px]
                      border border-white/10
                      bg-white/10
                      dark:bg-slate-900/70
                      p-5
                      shadow-lg
                      backdrop-blur-xl
                      transition
                      hover:-translate-y-1
                      hover:border-cyan-400/30">

                <div class="flex items-center
                            justify-between
                            gap-4">

                    <div>

                        <p class="text-xs
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-400">

                            Approved Jobs

                        </p>

                        <p class="mt-2
                                  text-3xl
                                  font-black
                                  text-cyan-500">

                            {{ $stats['total_jobs'] ?? 0 }}

                        </p>

                    </div>


                    <div class="h-12 w-12
                                rounded-2xl
                                bg-gradient-to-br
                                from-blue-500
                                to-cyan-500
                                flex items-center
                                justify-center
                                text-white
                                shadow-lg
                                group-hover:scale-110
                                transition">

                        <i class="fas fa-briefcase"></i>

                    </div>

                </div>

            </a>



            {{-- Events --}}

            <a href="{{ route('dashboard.activity', ['section'=>'events']) }}"
               class="group
                      rounded-[24px]
                      border border-white/10
                      bg-white/10
                      dark:bg-slate-900/70
                      p-5
                      shadow-lg
                      backdrop-blur-xl
                      transition
                      hover:-translate-y-1
                      hover:border-pink-400/30">

                <div class="flex items-center
                            justify-between
                            gap-4">

                    <div>

                        <p class="text-xs
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-400">

                            My Events

                        </p>

                        <p class="mt-2
                                  text-3xl
                                  font-black
                                  text-pink-500">

                            {{ $stats['total_events'] ?? 0 }}

                        </p>

                    </div>


                    <div class="h-12 w-12
                                rounded-2xl
                                bg-gradient-to-br
                                from-pink-500
                                to-rose-500
                                flex items-center
                                justify-center
                                text-white
                                shadow-lg
                                group-hover:scale-110
                                transition">

                        <i class="fas fa-calendar-days"></i>

                    </div>

                </div>

            </a>



            {{-- Mentorship --}}

            <a href="{{ route('dashboard.activity', ['section'=>'mentorships']) }}"
               class="group
                      rounded-[24px]
                      border border-white/10
                      bg-white/10
                      dark:bg-slate-900/70
                      p-5
                      shadow-lg
                      backdrop-blur-xl
                      transition
                      hover:-translate-y-1
                      hover:border-purple-400/30">

                <div class="flex items-center
                            justify-between
                            gap-4">

                    <div>

                        <p class="text-xs
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-400">

                            My Mentorships

                        </p>

                        <p class="mt-2
                                  text-3xl
                                  font-black
                                  text-purple-500">

                            {{ $stats['mentorship_requests'] ?? 0 }}

                        </p>

                    </div>


                    <div class="h-12 w-12
                                rounded-2xl
                                bg-gradient-to-br
                                from-violet-500
                                to-purple-600
                                flex items-center
                                justify-center
                                text-white
                                shadow-lg
                                group-hover:scale-110
                                transition">

                        <i class="fas fa-handshake"></i>

                    </div>

                </div>

            </a>



            {{-- Messages --}}

            <a href="{{ route('dashboard.activity', ['section'=>'unread']) }}"
               class="group
                      rounded-[24px]
                      border border-white/10
                      bg-white/10
                      dark:bg-slate-900/70
                      p-5
                      shadow-lg
                      backdrop-blur-xl
                      transition
                      hover:-translate-y-1
                      hover:border-emerald-400/30">

                <div class="flex items-center
                            justify-between
                            gap-4">

                    <div>

                        <p class="text-xs
                                  font-bold
                                  text-slate-500
                                  dark:text-slate-400">

                            Unread Messages

                        </p>

                        <p class="mt-2
                                  text-3xl
                                  font-black
                                  text-emerald-500">

                            {{ $stats['unread_messages'] ?? 0 }}

                        </p>

                    </div>


                    <div class="h-12 w-12
                                rounded-2xl
                                bg-gradient-to-br
                                from-emerald-500
                                to-teal-500
                                flex items-center
                                justify-center
                                text-white
                                shadow-lg
                                group-hover:scale-110
                                transition">

                        <i class="fas fa-comment"></i>

                    </div>

                </div>

            </a>

        </div>



        {{-- ========================================================= --}}
        {{-- PROFILE STRENGTH --}}
        {{-- ========================================================= --}}

        <div class="uc-student-strength">


            {{-- ===================================================== --}}
            {{-- PROFILE STRENGTH --}}
            {{-- ===================================================== --}}

            <div class="rounded-[30px]
                        border border-white/10
                        bg-gradient-to-br
                        from-slate-900
                        via-slate-900
                        to-purple-950
                        p-6
                        shadow-2xl">

                <p class="text-xs
                          font-black
                          uppercase
                          tracking-[0.25em]
                          text-emerald-400">

                    Career Profile

                </p>


                <h2 class="mt-2
                           text-2xl
                           font-black
                           text-white">

                    Profile Strength

                </h2>



                {{-- Score --}}

                <div class="mt-6
                            flex
                            items-center
                            gap-5">

                    <div
                        class="relative
                               h-28 w-28
                               shrink-0
                               rounded-full
                               flex
                               items-center
                               justify-center"
                        style="
                            background:
                            conic-gradient(
                                #22d3ee {{ $score * 3.6 }}deg,
                                rgba(255,255,255,.08) 0deg
                            );
                        "
                    >

                        <div class="h-[88px]
                                    w-[88px]
                                    rounded-full
                                    bg-slate-950
                                    flex
                                    items-center
                                    justify-center">

                            <span class="text-2xl
                                         font-black
                                         text-white">

                                {{ $score }}%

                            </span>

                        </div>

                    </div>


                    <div>

                        @if($score >= 80)

                            <p class="font-black
                                      text-emerald-400">

                                Strong Profile

                            </p>

                            <p class="mt-1
                                      text-xs
                                      leading-5
                                      text-slate-400">

                                Your profile is in good shape.
                                Keep your information updated.

                            </p>

                        @elseif($score >= 50)

                            <p class="font-black
                                      text-cyan-300">

                                Good Progress

                            </p>

                            <p class="mt-1
                                      text-xs
                                      leading-5
                                      text-slate-400">

                                Add a few more professional details
                                to strengthen your profile.

                            </p>

                        @else

                            <p class="font-black
                                      text-amber-400">

                                Needs Improvement

                            </p>

                            <p class="mt-1
                                      text-xs
                                      leading-5
                                      text-slate-400">

                                Complete the important profile fields
                                to improve your visibility.

                            </p>

                        @endif

                    </div>

                </div>



                {{-- Missing items compact version --}}

                @if($missingItems->isNotEmpty())

                    <div class="mt-6">

                        <p class="text-[10px]
                                  font-black
                                  uppercase
                                  tracking-[0.18em]
                                  text-slate-500">

                            Complete Next

                        </p>


                        <div class="mt-3
                                    flex
                                    flex-wrap
                                    gap-2">

                            @foreach($missingItems as $item)

                                <span class="rounded-full
                                             border border-amber-400/20
                                             bg-amber-400/10
                                             px-3 py-1.5
                                             text-[10px]
                                             font-black
                                             text-amber-300">

                                    {{ is_array($item)
                                        ? ($item['label'] ?? $item['name'] ?? 'Profile Item')
                                        : $item
                                    }}

                                </span>

                            @endforeach

                        </div>

                    </div>

                @endif



                <a href="{{ route('profile.edit') }}"
                   class="mt-6
                          flex
                          w-full
                          items-center
                          justify-center
                          gap-2
                          rounded-2xl
                          bg-gradient-to-r
                          from-emerald-500
                          to-cyan-500
                          px-5 py-3.5
                          text-sm
                          font-black
                          text-white
                          shadow-lg
                          transition
                          hover:scale-[1.02]">

                    <i class="fas fa-user-pen"></i>

                    Improve Profile

                </a>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- JOBS + EVENTS --}}
        {{-- ========================================================= --}}

        <div class="uc-student-opportunities grid
                    grid-cols-1
                    xl:grid-cols-3
                    gap-6">


            {{-- ===================================================== --}}
            {{-- RECOMMENDED JOBS --}}
            {{-- ===================================================== --}}

            <div class="xl:col-span-2
                        rounded-[30px]
                        border border-white/10
                        bg-white/10
                        dark:bg-slate-900/70
                        p-6
                        shadow-xl
                        backdrop-blur-xl">

                <div class="flex
                            flex-col
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-4">

                    <div>

                        <p class="text-xs
                                  font-black
                                  uppercase
                                  tracking-[0.25em]
                                  text-blue-400">

                            Opportunities

                        </p>

                        <h2 class="mt-2
                                   text-2xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            Recommended Jobs

                        </h2>

                        <p class="mt-1
                                  text-sm
                                  text-slate-500">

                            Latest approved opportunities for students.

                        </p>

                    </div>


                    <a href="{{ route('jobs.index') }}"
                       class="inline-flex
                              items-center
                              justify-center
                              gap-2
                              rounded-2xl
                              bg-gradient-to-r
                              from-blue-500
                              to-cyan-500
                              px-5 py-3
                              text-sm
                              font-black
                              text-white
                              shadow-lg
                              transition
                              hover:scale-[1.02]">

                        <i class="fas fa-briefcase"></i>

                        Browse Jobs

                    </a>

                </div>



                <div class="mt-6 space-y-3">

                    @forelse($recommendedJobs as $job)

                        <a href="{{ route('jobs.show', $job) }}"
                           class="group
                                  block
                                  rounded-2xl
                                  border border-white/10
                                  bg-slate-100
                                  dark:bg-slate-950/70
                                  p-5
                                  transition
                                  hover:border-cyan-400/30
                                  hover:-translate-y-0.5">

                            <div class="flex
                                        flex-col
                                        md:flex-row
                                        md:items-center
                                        md:justify-between
                                        gap-4">

                                <div class="flex
                                            items-start
                                            gap-4">

                                    <div class="h-12 w-12
                                                shrink-0
                                                rounded-2xl
                                                bg-blue-500/15
                                                flex
                                                items-center
                                                justify-center
                                                text-blue-500">

                                        <i class="fas fa-briefcase"></i>

                                    </div>


                                    <div>

                                        <h3 class="font-black
                                                   text-slate-900
                                                   dark:text-white
                                                   group-hover:text-cyan-400
                                                   transition">

                                            {{ $job->title }}

                                        </h3>


                                        <p class="mt-1
                                                  text-sm
                                                  text-slate-500">

                                            {{ $job->company_name ?? 'Company not specified' }}

                                            @if(!empty($job->location))
                                                • {{ $job->location }}
                                            @endif

                                        </p>


                                        <div class="mt-3
                                                    flex
                                                    flex-wrap
                                                    gap-2">

                                            @if(!empty($job->type))

                                                <span class="rounded-full
                                                             bg-cyan-500/10
                                                             px-2.5 py-1
                                                             text-[10px]
                                                             font-black
                                                             text-cyan-500">

                                                    {{ ucwords(
                                                        str_replace(
                                                            ['_', '-'],
                                                            ' ',
                                                            $job->type
                                                        )
                                                    ) }}

                                                </span>

                                            @endif


                                            @if(!empty($job->salary_range))

                                                <span class="rounded-full
                                                             bg-emerald-500/10
                                                             px-2.5 py-1
                                                             text-[10px]
                                                             font-black
                                                             text-emerald-500">

                                                    {{ $job->salary_range }}

                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                                <div class="hidden
                                            md:flex
                                            h-10 w-10
                                            rounded-xl
                                            bg-white/5
                                            items-center
                                            justify-center
                                            text-slate-400
                                            group-hover:text-cyan-400
                                            transition">

                                    <i class="fas fa-arrow-right"></i>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="rounded-2xl
                                    border
                                    border-dashed
                                    border-white/10
                                    bg-slate-950/20
                                    px-5 py-10
                                    text-center">

                            <div class="mx-auto
                                        h-12 w-12
                                        rounded-2xl
                                        bg-blue-500/10
                                        flex items-center
                                        justify-center
                                        text-blue-400">

                                <i class="fas fa-briefcase"></i>

                            </div>

                            <p class="mt-4
                                      font-bold
                                      text-slate-500">

                                No approved jobs are available yet.

                            </p>

                        </div>

                    @endforelse

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- EVENTS --}}
            {{-- ===================================================== --}}

            <div class="rounded-[30px]
                        border border-white/10
                        bg-gradient-to-br
                        from-slate-900
                        to-purple-950
                        p-6
                        shadow-xl">

                <p class="text-xs
                          font-black
                          uppercase
                          tracking-[0.25em]
                          text-pink-400">

                    Smart Events

                </p>


                <h2 class="mt-2
                           text-2xl
                           font-black
                           text-white">

                    Upcoming Events

                </h2>


                <div class="mt-5 space-y-3">

                    @forelse($recommendedEvents as $event)

                        <a href="{{ route('events.show', $event) }}"
                           class="block
                                  rounded-2xl
                                  border border-white/10
                                  bg-slate-950/60
                                  p-4
                                  transition
                                  hover:border-pink-400/30">

                            <div class="flex
                                        items-start
                                        gap-3">

                                <div class="h-10 w-10
                                            shrink-0
                                            rounded-xl
                                            bg-pink-500/15
                                            flex
                                            items-center
                                            justify-center
                                            text-pink-400">

                                    <i class="fas fa-calendar-days"></i>

                                </div>


                                <div class="min-w-0">

                                    <p class="font-black
                                              text-white
                                              truncate">

                                        {{ $event->title }}

                                    </p>


                                    @if(!empty($event->location))

                                        <p class="mt-1
                                                  text-xs
                                                  text-slate-500
                                                  truncate">

                                            {{ $event->location }}

                                        </p>

                                    @endif


                                    @if(!empty($event->event_date))

                                        <p class="mt-2
                                                  text-[10px]
                                                  font-black
                                                  text-pink-300">

                                            {{ \Carbon\Carbon::parse(
                                                $event->event_date
                                            )->format('d M Y') }}

                                        </p>

                                    @elseif(!empty($event->start_date))

                                        <p class="mt-2
                                                  text-[10px]
                                                  font-black
                                                  text-pink-300">

                                            {{ \Carbon\Carbon::parse(
                                                $event->start_date
                                            )->format('d M Y') }}

                                        </p>

                                    @endif

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="rounded-2xl
                                    border
                                    border-dashed
                                    border-white/10
                                    p-7
                                    text-center">

                            <i class="fas fa-calendar
                                      text-xl
                                      text-slate-600">
                            </i>

                            <p class="mt-3
                                      text-sm
                                      text-slate-500">

                                No upcoming events found.

                            </p>

                        </div>

                    @endforelse

                </div>


                <a href="{{ route('events.index') }}"
                   class="mt-5
                          flex
                          w-full
                          items-center
                          justify-center
                          rounded-2xl
                          bg-gradient-to-r
                          from-pink-500
                          to-rose-500
                          px-5 py-3
                          text-sm
                          font-black
                          text-white
                          shadow-lg
                          transition
                          hover:scale-[1.02]">

                    View Events

                </a>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- MENTORSHIP --}}
        {{-- Fake mentor cards removed --}}
        {{-- ========================================================= --}}

        <div class="uc-student-mentor-panel rounded-[30px]
                    border border-white/10
                    bg-gradient-to-r
                    from-slate-900
                    via-indigo-950
                    to-purple-950
                    p-6
                    md:p-7
                    shadow-xl">

            <div class="flex
                        flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-6">

                <div class="flex
                            items-start
                            gap-4">

                    <div class="h-14 w-14
                                shrink-0
                                rounded-2xl
                                bg-gradient-to-br
                                from-purple-500
                                to-pink-500
                                flex items-center
                                justify-center
                                text-white
                                text-xl
                                shadow-xl">

                        <i class="fas fa-user-graduate"></i>

                    </div>


                    <div>

                        <p class="text-xs
                                  font-black
                                  uppercase
                                  tracking-[0.25em]
                                  text-purple-300">

                            Alumni Mentorship

                        </p>

                        <h2 class="mt-2
                                   text-2xl
                                   font-black
                                   text-white">

                            Connect with an Alumni Mentor

                        </h2>

                        <p class="mt-2
                                  max-w-2xl
                                  text-sm
                                  leading-6
                                  text-slate-400">

                            Find experienced alumni who can guide you
                            with career planning, skills, interviews,
                            professional development, and industry preparation.

                        </p>

                    </div>

                </div>


                <a href="{{ url('/alumni-mentors') }}"
                   class="inline-flex
                          shrink-0
                          items-center
                          justify-center
                          gap-2
                          rounded-2xl
                          bg-gradient-to-r
                          from-purple-500
                          to-pink-500
                          px-6 py-3.5
                          text-sm
                          font-black
                          text-white
                          shadow-xl
                          transition
                          hover:scale-[1.02]">

                    <i class="fas fa-magnifying-glass"></i>

                    Find Mentor

                </a>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- Dashboard resources --}}
        {{-- No fake percentage/progress bars --}}
        {{-- ========================================================= --}}





        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="uc-student-quick-panel rounded-[30px]
                    border border-white/10
                    bg-slate-900/80
                    p-6
                    shadow-xl">

            <div class="flex
                        flex-col
                        md:flex-row
                        md:items-end
                        md:justify-between
                        gap-4">

                <div>

                    <p class="text-xs
                              font-black
                              uppercase
                              tracking-[0.25em]
                              text-emerald-400">

                        Quick Actions

                    </p>

                    <h2 class="mt-2
                               text-2xl
                               font-black
                               text-white">

                        Continue Your Career Journey

                    </h2>

                </div>

            </div>


            <div class="mt-6
                        grid
                        grid-cols-2
                        lg:grid-cols-4
                        gap-4">


                {{-- Jobs --}}

                <a href="{{ route('jobs.index') }}"
                   class="group
                          rounded-2xl
                          border border-white/10
                          bg-white/5
                          p-5
                          text-center
                          transition
                          hover:-translate-y-1
                          hover:border-blue-400/30
                          hover:bg-blue-500/10">

                    <div class="mx-auto
                                h-11 w-11
                                rounded-xl
                                bg-blue-500/15
                                flex items-center
                                justify-center
                                text-blue-400">

                        <i class="fas fa-briefcase"></i>

                    </div>

                    <p class="mt-3
                              text-sm
                              font-black
                              text-white">

                        Browse Jobs

                    </p>

                </a>



                {{-- Mentor --}}

                <a href="{{ url('/alumni-mentors') }}"
                   class="group
                          rounded-2xl
                          border border-white/10
                          bg-white/5
                          p-5
                          text-center
                          transition
                          hover:-translate-y-1
                          hover:border-purple-400/30
                          hover:bg-purple-500/10">

                    <div class="mx-auto
                                h-11 w-11
                                rounded-xl
                                bg-purple-500/15
                                flex items-center
                                justify-center
                                text-purple-400">

                        <i class="fas fa-handshake"></i>

                    </div>

                    <p class="mt-3
                              text-sm
                              font-black
                              text-white">

                        Find Mentor

                    </p>

                </a>



                {{-- Profile --}}

                <a href="{{ route('profile.edit') }}"
                   class="group
                          rounded-2xl
                          border border-white/10
                          bg-white/5
                          p-5
                          text-center
                          transition
                          hover:-translate-y-1
                          hover:border-emerald-400/30
                          hover:bg-emerald-500/10">

                    <div class="mx-auto
                                h-11 w-11
                                rounded-xl
                                bg-emerald-500/15
                                flex items-center
                                justify-center
                                text-emerald-400">

                        <i class="fas fa-user-pen"></i>

                    </div>

                    <p class="mt-3
                              text-sm
                              font-black
                              text-white">

                        Update Profile

                    </p>

                </a>


            </div>

        </div>


    </div>

    <section class="mx-auto mt-4 max-w-7xl rounded-2xl border border-indigo-400/20 bg-slate-900/70 p-4 flex flex-wrap items-center justify-between gap-3">
        <div><h2 class="font-bold text-white">Alumni Conversion</h2><p class="mt-1 text-xs text-slate-400">Graduated? Apply to update your student account to alumni. Track your application on the same page.</p></div>
        <a href="{{ route('alumni-conversion.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white">Apply / View Status</a>
    </section>
    @include('components.student-dashboard-style')
</x-app-layout>
