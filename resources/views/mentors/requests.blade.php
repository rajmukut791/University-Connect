<x-app-layout>

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl border border-white/10
                    bg-gradient-to-r from-slate-950 via-violet-950 to-fuchsia-950
                    px-6 py-6 lg:px-8 shadow-2xl">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(139,92,246,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.25),transparent_35%)]">
            </div>

            <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <p class="text-xs font-black uppercase tracking-[0.32em] text-fuchsia-300">
                        Mentorship
                    </p>

                    <h2 class="mt-2 text-3xl font-black text-white lg:text-4xl">
                        Mentorship Requests
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                        Review student and alumni mentorship requests and manage your active mentorships.
                    </p>
                </div>

                <a
                    href="{{ route('alumni.dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 self-start rounded-2xl
                           border border-white/10 bg-white/10 px-5 py-3 text-sm font-black
                           text-white transition hover:bg-white/20 lg:self-auto"
                >
                    <i class="fas fa-arrow-left"></i>
                    Dashboard
                </a>

            </div>
        </div>
    </x-slot>


    <style>
        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, .14);

            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, .14),
                rgba(255, 255, 255, .05)
            );

            backdrop-filter: blur(20px);
            box-shadow: 0 20px 60px rgba(15, 23, 42, .16);
        }

        .uc-stat-card {
            transition: transform .25s ease, border-color .25s ease;
        }

        .uc-stat-card:hover {
            transform: translateY(-3px);
        }

        .uc-request-card {
            transition: transform .25s ease, border-color .25s ease;
        }

        .uc-request-card:hover {
            transform: translateY(-2px);
        }

        .uc-filter-btn {
            transition: all .2s ease;
        }

        .uc-filter-btn.active {
            color: #ffffff;
            border-color: transparent;
            background: linear-gradient(90deg, #7c3aed, #d946ef);
            box-shadow: 0 8px 24px rgba(168, 85, 247, .28);
        }

        .uc-hidden {
            display: none !important;
        }
    </style>


    <div class="space-y-6">

        {{-- =========================================================
             FLASH MESSAGES
        ========================================================== --}}

        @if(session('success'))
            <div class="uc-card p-4">
                <div class="relative z-10 flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                rounded-xl bg-emerald-500/15 text-emerald-500">
                        <i class="fas fa-circle-check"></i>
                    </div>

                    <div>
                        <p class="font-black text-emerald-500">
                            Success
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300">
                            {{ session('success') }}
                        </p>
                    </div>

                </div>
            </div>
        @endif


        @if(session('error'))
            <div class="uc-card p-4">
                <div class="relative z-10 flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                rounded-xl bg-red-500/15 text-red-500">
                        <i class="fas fa-circle-exclamation"></i>
                    </div>

                    <div>
                        <p class="font-black text-red-500">
                            Action Failed
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-300">
                            {{ session('error') }}
                        </p>
                    </div>

                </div>
            </div>
        @endif


        @if($errors->any())
            <div class="uc-card p-4">

                <div class="relative z-10">

                    <p class="mb-2 font-black text-red-500">
                        <i class="fas fa-triangle-exclamation mr-2"></i>
                        Please check the following:
                    </p>

                    <ul class="space-y-1 text-sm text-red-500">
                        @foreach($errors->all() as $error)
                            <li>
                                • {{ $error }}
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        @endif


        {{-- =========================================================
             COUNTS
        ========================================================== --}}

        @php
            $pendingCount = $requests->where('status', 'pending')->count();
            $acceptedCount = $requests->where('status', 'accepted')->count();
            $completedCount = $requests->where('status', 'completed')->count();
            $rejectedCount = $requests->where('status', 'rejected')->count();
        @endphp


        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

            {{-- Pending --}}
            <button
                type="button"
                data-stat-filter="pending"
                class="uc-card uc-stat-card p-5 text-left"
            >
                <div class="relative z-10 flex items-center justify-between gap-4">

                    <div>
                        <p class="text-xs font-black uppercase tracking-wider
                                  text-slate-500 dark:text-slate-300">
                            Pending
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-amber-500">
                            {{ $pendingCount }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-400">
                            Needs your decision
                        </p>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center
                                rounded-2xl bg-amber-500/15 text-amber-500">
                        <i class="fas fa-clock"></i>
                    </div>

                </div>
            </button>


            {{-- Accepted --}}
            <button
                type="button"
                data-stat-filter="accepted"
                class="uc-card uc-stat-card p-5 text-left"
            >
                <div class="relative z-10 flex items-center justify-between gap-4">

                    <div>
                        <p class="text-xs font-black uppercase tracking-wider
                                  text-slate-500 dark:text-slate-300">
                            Active
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-emerald-500">
                            {{ $acceptedCount }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-400">
                            Current mentorships
                        </p>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center
                                rounded-2xl bg-emerald-500/15 text-emerald-500">
                        <i class="fas fa-handshake"></i>
                    </div>

                </div>
            </button>


            {{-- Completed --}}
            <button
                type="button"
                data-stat-filter="completed"
                class="uc-card uc-stat-card p-5 text-left"
            >
                <div class="relative z-10 flex items-center justify-between gap-4">

                    <div>
                        <p class="text-xs font-black uppercase tracking-wider
                                  text-slate-500 dark:text-slate-300">
                            Completed
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-blue-500">
                            {{ $completedCount }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-400">
                            Finished mentorships
                        </p>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center
                                rounded-2xl bg-blue-500/15 text-blue-500">
                        <i class="fas fa-flag-checkered"></i>
                    </div>

                </div>
            </button>


            {{-- Rejected --}}
            <button
                type="button"
                data-stat-filter="rejected"
                class="uc-card uc-stat-card p-5 text-left"
            >
                <div class="relative z-10 flex items-center justify-between gap-4">

                    <div>
                        <p class="text-xs font-black uppercase tracking-wider
                                  text-slate-500 dark:text-slate-300">
                            Rejected
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-red-500">
                            {{ $rejectedCount }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-400">
                            Declined requests
                        </p>
                    </div>

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center
                                rounded-2xl bg-red-500/15 text-red-500">
                        <i class="fas fa-circle-xmark"></i>
                    </div>

                </div>
            </button>

        </div>


        {{-- =========================================================
             REQUEST MANAGEMENT HEADER
        ========================================================== --}}

        <div class="uc-card p-5">

            <div class="relative z-10 flex flex-col gap-4
                        xl:flex-row xl:items-center xl:justify-between">

                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                        Mentorship Requests
                    </h3>

                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Filter and manage requests by their current status.
                    </p>
                </div>


                {{-- FILTER TABS --}}
                <div class="flex flex-wrap gap-2">

                    <button
                        type="button"
                        data-filter="all"
                        class="uc-filter-btn active rounded-xl border border-white/10
                               bg-white/5 px-4 py-2 text-xs font-black
                               text-slate-600 dark:text-slate-300"
                    >
                        All
                        <span class="ml-1 opacity-70">
                            {{ $requests->count() }}
                        </span>
                    </button>


                    <button
                        type="button"
                        data-filter="pending"
                        class="uc-filter-btn rounded-xl border border-white/10
                               bg-white/5 px-4 py-2 text-xs font-black
                               text-slate-600 dark:text-slate-300"
                    >
                        Pending
                        <span class="ml-1 opacity-70">
                            {{ $pendingCount }}
                        </span>
                    </button>


                    <button
                        type="button"
                        data-filter="accepted"
                        class="uc-filter-btn rounded-xl border border-white/10
                               bg-white/5 px-4 py-2 text-xs font-black
                               text-slate-600 dark:text-slate-300"
                    >
                        Active
                        <span class="ml-1 opacity-70">
                            {{ $acceptedCount }}
                        </span>
                    </button>


                    <button
                        type="button"
                        data-filter="completed"
                        class="uc-filter-btn rounded-xl border border-white/10
                               bg-white/5 px-4 py-2 text-xs font-black
                               text-slate-600 dark:text-slate-300"
                    >
                        Completed
                        <span class="ml-1 opacity-70">
                            {{ $completedCount }}
                        </span>
                    </button>


                    <button
                        type="button"
                        data-filter="rejected"
                        class="uc-filter-btn rounded-xl border border-white/10
                               bg-white/5 px-4 py-2 text-xs font-black
                               text-slate-600 dark:text-slate-300"
                    >
                        Rejected
                        <span class="ml-1 opacity-70">
                            {{ $rejectedCount }}
                        </span>
                    </button>

                </div>

            </div>

        </div>


        {{-- =========================================================
             REQUEST LIST
        ========================================================== --}}

        <div
            id="mentorshipRequestList"
            class="space-y-5"
        >

            @forelse($requests as $requestItem)

                <div
                    class="uc-card uc-request-card mentorship-request p-5 lg:p-6"
                    data-status="{{ $requestItem->status }}"
                >

                    <div class="relative z-10">

                        {{-- =================================================
                             TOP: STUDENT + STATUS
                        ================================================== --}}

                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                            <div class="flex min-w-0 flex-1 items-start gap-4">

                                {{-- PROFILE IMAGE --}}
                                @if($requestItem->student?->profile_image)

                                    <img
                                        src="{{ $requestItem->student->getProfileImageUrl() }}"
                                        alt="{{ $requestItem->student?->name ?? 'Student' }}"
                                        class="h-16 w-16 shrink-0 rounded-2xl
                                               bg-white object-cover shadow-lg"
                                    >

                                @else

                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($requestItem->student?->email ?? 'student') }}"
                                        alt="{{ $requestItem->student?->name ?? 'Student' }}"
                                        class="h-16 w-16 shrink-0 rounded-2xl
                                               bg-white object-cover shadow-lg"
                                    >

                                @endif


                                <div class="min-w-0 flex-1">

                                    <div class="flex flex-wrap items-center gap-2">

                                        <h3 class="break-words text-xl font-black
                                                   text-slate-900 dark:text-white">
                                            {{ $requestItem->student?->name ?? 'Unknown Student' }}
                                        </h3>


                                        {{-- STATUS BADGE --}}
                                        @if($requestItem->status === 'pending')

                                            <span class="rounded-full bg-amber-500/15
                                                         px-3 py-1 text-[11px] font-black
                                                         text-amber-500">
                                                <i class="fas fa-clock mr-1"></i>
                                                PENDING
                                            </span>

                                        @elseif($requestItem->status === 'accepted')

                                            <span class="rounded-full bg-emerald-500/15
                                                         px-3 py-1 text-[11px] font-black
                                                         text-emerald-500">
                                                <i class="fas fa-handshake mr-1"></i>
                                                ACTIVE
                                            </span>

                                        @elseif($requestItem->status === 'completed')

                                            <span class="rounded-full bg-blue-500/15
                                                         px-3 py-1 text-[11px] font-black
                                                         text-blue-500">
                                                <i class="fas fa-flag-checkered mr-1"></i>
                                                COMPLETED
                                            </span>

                                        @elseif($requestItem->status === 'rejected')

                                            <span class="rounded-full bg-red-500/15
                                                         px-3 py-1 text-[11px] font-black
                                                         text-red-500">
                                                <i class="fas fa-circle-xmark mr-1"></i>
                                                REJECTED
                                            </span>

                                        @else

                                            <span class="rounded-full bg-slate-500/15
                                                         px-3 py-1 text-[11px] font-black
                                                         text-slate-500">
                                                {{ strtoupper($requestItem->status) }}
                                            </span>

                                        @endif

                                    </div>


                                    {{-- STUDENT META --}}
                                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1
                                                text-xs font-semibold
                                                text-slate-500 dark:text-slate-400">

                                        @if($requestItem->student?->department)
                                            <span>
                                                <i class="fas fa-building-columns mr-1"></i>
                                                {{ $requestItem->student->department }}
                                            </span>
                                        @endif


                                        @if($requestItem->student?->batch)
                                            <span>
                                                <i class="fas fa-users mr-1"></i>
                                                Batch {{ $requestItem->student->batch }}
                                            </span>
                                        @endif


                                        @if($requestItem->student?->official_id)
                                            <span>
                                                <i class="fas fa-id-card mr-1"></i>
                                                {{ $requestItem->student->official_id }}
                                            </span>
                                        @endif

                                    </div>


                                    @if($requestItem->student)
                                        <a
                                            href="{{ route($requestItem->student->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $requestItem->student) }}"
                                            class="mt-3 inline-flex items-center gap-2
                                                   text-xs font-black text-cyan-500
                                                   transition hover:text-cyan-400"
                                        >
                                            <i class="fas fa-user-graduate"></i>
                                            View Profile
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </a>
                                    @endif

                                </div>

                            </div>


                            {{-- REQUEST DATE --}}
                            <div class="shrink-0 rounded-xl border border-white/10
                                        bg-white/5 px-4 py-2 text-xs font-bold
                                        text-slate-500 dark:text-slate-400">

                                <i class="fas fa-calendar-days mr-1"></i>

                                {{ $requestItem->created_at?->format('d M Y') }}

                            </div>

                        </div>


                        {{-- =================================================
                             REQUEST CONTENT
                        ================================================== --}}

                        <div class="mt-5 rounded-2xl border border-violet-500/15
                                    bg-violet-500/5 p-5">

                            <p class="text-[11px] font-black uppercase
                                      tracking-[0.20em] text-violet-500">
                                Request
                            </p>

                            <h4 class="mt-2 text-lg font-black
                                       text-slate-900 dark:text-white">
                                {{ $requestItem->title ?: 'Career Mentorship Request' }}
                            </h4>

                            <p class="mt-3 whitespace-pre-line text-sm leading-7
                                      text-slate-600 dark:text-slate-300">
                                {{ $requestItem->description ?: 'No request description provided.' }}
                            </p>

                        </div>


                        {{-- =================================================
                             STATUS-SPECIFIC AREA
                        ================================================== --}}


                        {{-- ================= PENDING ================= --}}
                        @if($requestItem->status === 'pending')

                            <div class="mt-5 grid gap-4 lg:grid-cols-[1fr_1.35fr]">

                                {{-- ACCEPT --}}
                                <div class="rounded-2xl border border-emerald-500/20
                                            bg-emerald-500/5 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="flex h-10 w-10 shrink-0
                                                    items-center justify-center rounded-xl
                                                    bg-emerald-500/15 text-emerald-500">

                                            <i class="fas fa-check"></i>

                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900 dark:text-white">
                                                Accept Request
                                            </p>

                                            <p class="mt-1 text-xs leading-5
                                                      text-slate-500 dark:text-slate-400">
                                                Start a mentorship relationship with this student.
                                            </p>
                                        </div>

                                    </div>


                                    <form
                                        method="POST"
                                        action="{{ route('mentors.accept', $requestItem) }}"
                                        class="mt-4"
                                        onsubmit="return confirm('Are you sure you want to accept this mentorship request?');"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="w-full rounded-xl
                                                   bg-gradient-to-r from-emerald-500 to-green-600
                                                   px-5 py-3 text-sm font-black text-white
                                                   shadow-lg transition hover:scale-[1.01]"
                                        >
                                            <i class="fas fa-handshake mr-2"></i>
                                            Accept Mentorship
                                        </button>

                                    </form>

                                </div>


                                {{-- REJECT --}}
                                <div class="rounded-2xl border border-red-500/20
                                            bg-red-500/5 p-5">

                                    <div class="flex items-start gap-3">

                                        <div class="flex h-10 w-10 shrink-0
                                                    items-center justify-center rounded-xl
                                                    bg-red-500/15 text-red-500">

                                            <i class="fas fa-xmark"></i>

                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900 dark:text-white">
                                                Reject Request
                                            </p>

                                            <p class="mt-1 text-xs leading-5
                                                      text-slate-500 dark:text-slate-400">
                                                You can optionally explain why you cannot accept the request.
                                            </p>
                                        </div>

                                    </div>


                                    <form
                                        method="POST"
                                        action="{{ route('mentors.reject', $requestItem) }}"
                                        class="mt-4"
                                        onsubmit="return confirm('Are you sure you want to reject this mentorship request?');"
                                    >
                                        @csrf
                                        @method('PATCH')


                                        <textarea
                                            name="rejection_reason"
                                            rows="3"
                                            maxlength="1000"
                                            placeholder="Optional rejection reason..."
                                            class="w-full rounded-xl border border-red-500/20
                                                   bg-white/5 px-4 py-3 text-sm
                                                   text-slate-900 placeholder-slate-400
                                                   focus:border-red-500 focus:ring-red-500/20
                                                   dark:text-white"
                                        >{{ old('rejection_reason') }}</textarea>


                                        <button
                                            type="submit"
                                            class="mt-3 w-full rounded-xl
                                                   border border-red-500/20
                                                   bg-red-500/15 px-5 py-3
                                                   text-sm font-black text-red-500
                                                   transition hover:bg-red-500 hover:text-white"
                                        >
                                            <i class="fas fa-ban mr-2"></i>
                                            Reject Request
                                        </button>

                                    </form>

                                </div>

                            </div>


                        {{-- ================= ACCEPTED ================= --}}
                        @elseif($requestItem->status === 'accepted')

                            <div class="mt-5 flex flex-col gap-4 rounded-2xl
                                        border border-emerald-500/20
                                        bg-emerald-500/5 p-5
                                        lg:flex-row lg:items-center lg:justify-between">

                                <div class="flex items-start gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center
                                                justify-center rounded-xl
                                                bg-emerald-500/15 text-emerald-500">

                                        <i class="fas fa-handshake"></i>

                                    </div>

                                    <div>

                                        <p class="font-black text-emerald-500">
                                            Active Mentorship
                                        </p>

                                        @if($requestItem->started_at)

                                            <p class="mt-1 text-xs
                                                      text-slate-500 dark:text-slate-400">
                                                Started on
                                                {{ $requestItem->started_at->format('d M Y') }}
                                            </p>

                                        @else

                                            <p class="mt-1 text-xs
                                                      text-slate-500 dark:text-slate-400">
                                                This mentorship is currently active.
                                            </p>

                                        @endif

                                    </div>

                                </div>


                                <form
                                    method="POST"
                                    action="{{ route('mentors.complete', $requestItem) }}"
                                    onsubmit="return confirm('Are you sure you want to mark this mentorship as completed?');"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl
                                               bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600
                                               px-5 py-3 text-sm font-black text-white
                                               shadow-lg transition hover:scale-[1.02]
                                               lg:w-auto"
                                    >
                                        <i class="fas fa-flag-checkered mr-2"></i>
                                        Complete Mentorship
                                    </button>

                                </form>

                            </div>


                        {{-- ================= COMPLETED ================= --}}
                        @elseif($requestItem->status === 'completed')

                            <div class="mt-5 rounded-2xl border border-blue-500/20
                                        bg-blue-500/5 p-5">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center
                                                justify-center rounded-xl
                                                bg-blue-500/15 text-blue-500">

                                        <i class="fas fa-flag-checkered"></i>

                                    </div>

                                    <div>

                                        <p class="font-black text-blue-500">
                                            Mentorship Completed
                                        </p>

                                        <p class="mt-1 text-xs
                                                  text-slate-500 dark:text-slate-400">

                                            @if($requestItem->ended_at)

                                                Completed on
                                                {{ $requestItem->ended_at->format('d M Y') }}

                                            @else

                                                This mentorship journey has been completed.

                                            @endif

                                        </p>

                                    </div>

                                </div>

                            </div>


                        {{-- ================= REJECTED ================= --}}
                        @elseif($requestItem->status === 'rejected')

                            <div class="mt-5 rounded-2xl border border-red-500/20
                                        bg-red-500/5 p-5">

                                <div class="flex items-start gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center
                                                justify-center rounded-xl
                                                bg-red-500/15 text-red-500">

                                        <i class="fas fa-circle-xmark"></i>

                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-black text-red-500">
                                            Request Rejected
                                        </p>

                                        @if($requestItem->rejection_reason)

                                            <p class="mt-2 text-sm leading-6
                                                      text-slate-600 dark:text-slate-300">
                                                {{ $requestItem->rejection_reason }}
                                            </p>

                                        @else

                                            <p class="mt-1 text-xs
                                                      text-slate-500 dark:text-slate-400">
                                                This mentorship request was declined.
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


            @empty

                {{-- =====================================================
                     EMPTY STATE
                ====================================================== --}}

                <div class="uc-card p-8 text-center">

                    <div class="relative z-10">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center
                                    rounded-2xl bg-gradient-to-br
                                    from-violet-500 to-fuchsia-500
                                    text-white shadow-lg">

                            <i class="fas fa-inbox text-xl"></i>

                        </div>

                        <h3 class="mt-4 text-xl font-black
                                   text-slate-900 dark:text-white">
                            No Mentorship Requests Yet
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6
                                  text-slate-500 dark:text-slate-400">
                            New mentorship requests from students will appear here.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>


        {{-- =========================================================
             FILTER EMPTY MESSAGE
        ========================================================== --}}

        <div
            id="filterEmptyState"
            class="uc-card uc-hidden p-8 text-center"
        >

            <div class="relative z-10">

                <div class="mx-auto flex h-14 w-14 items-center justify-center
                            rounded-2xl bg-slate-500/10 text-slate-400">

                    <i class="fas fa-filter text-xl"></i>

                </div>

                <h3 class="mt-4 text-lg font-black
                           text-slate-900 dark:text-white">
                    No Requests Found
                </h3>

                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    There are no mentorship requests under this status.
                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTER SCRIPT
    ========================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const filterButtons =
                document.querySelectorAll('[data-filter]');

            const statButtons =
                document.querySelectorAll('[data-stat-filter]');

            const requestCards =
                document.querySelectorAll('.mentorship-request');

            const emptyState =
                document.getElementById('filterEmptyState');


            function applyFilter(status) {

                let visibleCount = 0;


                requestCards.forEach(function (card) {

                    const cardStatus =
                        card.dataset.status;


                    if (
                        status === 'all' ||
                        cardStatus === status
                    ) {
                        card.classList.remove('uc-hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('uc-hidden');
                    }

                });


                filterButtons.forEach(function (button) {

                    if (
                        button.dataset.filter === status
                    ) {
                        button.classList.add('active');
                    } else {
                        button.classList.remove('active');
                    }

                });


                if (
                    emptyState &&
                    requestCards.length > 0
                ) {

                    if (visibleCount === 0) {
                        emptyState.classList.remove('uc-hidden');
                    } else {
                        emptyState.classList.add('uc-hidden');
                    }

                }

            }


            filterButtons.forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        applyFilter(
                            button.dataset.filter
                        );

                    }
                );

            });


            statButtons.forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        applyFilter(
                            button.dataset.statFilter
                        );

                        document
                            .getElementById('mentorshipRequestList')
                            ?.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });

                    }
                );

            });

        });
    </script>

</x-app-layout>