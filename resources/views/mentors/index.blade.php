<x-app-layout>


    <style>

        @keyframes ucFloat {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

        }


        @keyframes ucScan {

            0% {
                transform: translateX(-120%);
            }

            100% {
                transform: translateX(120%);
            }

        }


        .uc-card {

            position: relative;
            overflow: hidden;

            border-radius: 1.5rem;

            border: 1px solid rgba(255, 255, 255, .16);

            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, .16),
                rgba(255, 255, 255, .06)
            );

            backdrop-filter: blur(22px);

            box-shadow: 0 24px 70px rgba(15, 23, 42, .18);

            transition: .35s ease;

        }


        .uc-card:hover {
            transform: translateY(-5px);
        }


        .uc-card::before {

            content: "";

            position: absolute;

            inset: 0;

            width: 45%;

            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, .15),
                transparent
            );

            transform: translateX(-120%);

            pointer-events: none;

        }


        .uc-card:hover::before {
            animation: ucScan 1.1s ease;
        }


        .uc-float {
            animation: ucFloat 5s ease-in-out infinite;
        }


        .uc-button {
            transition: .25s ease;
        }


        .uc-button:hover {
            transform: translateY(-2px);
        }


        select option {
            background: #0f172a;
            color: #ffffff;
        }

        /* Mentor card polish */
        .mentor-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .mentor-card .mentor-card-body {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .mentor-avatar {
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .mentor-card:hover .mentor-avatar {
            transform: scale(1.035);
        }

        .mentor-actions {
            margin-top: auto;
        }

        .mentor-message {
            min-height: 88px;
            resize: vertical;
        }

        .mentor-profile-button {
            background: rgba(14, 165, 233, .12);
            border: 1px solid rgba(56, 189, 248, .28);
            color: #38bdf8;
        }

        .mentor-profile-button:hover {
            background: rgba(14, 165, 233, .20);
            border-color: rgba(56, 189, 248, .45);
        }

        .dark .mentor-profile-button {
            color: #7dd3fc;
        }

        @media (prefers-reduced-motion: reduce) {
            .uc-float {
                animation: none;
            }
        }

    </style>


    <div class="space-y-8">


        {{-- ========================================================= --}}
        {{-- SUCCESS --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-start gap-3 text-emerald-600">

                    <div class="h-10 w-10 shrink-0 rounded-xl bg-emerald-500/15 flex items-center justify-center">

                        <i class="fas fa-circle-check"></i>

                    </div>

                    <div>

                        <p class="font-black">
                            Success
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div class="uc-card p-5">

                <div class="relative z-10 flex items-start gap-3 text-red-600">

                    <div class="h-10 w-10 shrink-0 rounded-xl bg-red-500/15 flex items-center justify-center">

                        <i class="fas fa-circle-exclamation"></i>

                    </div>

                    <div>

                        <p class="font-black">
                            Action Failed
                        </p>

                        <p class="mt-1 text-sm font-semibold">
                            {{ session('error') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="uc-card p-5">

                <div class="relative z-10">

                    <p class="font-black text-red-500 mb-3">

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


        {{-- ========================================================= --}}
        {{-- SEARCH FILTER --}}
        {{-- ========================================================= --}}

        <div class="uc-card p-4">

            <div class="relative z-10">

                <div class="mb-5">

                    <h3 class="text-xl font-black text-slate-300 dark:text-white">
                        Find Your Mentor
                    </h3>

                </div>


                <form
                    method="GET"
                    action="{{ route('mentors.index') }}"
                    class="grid grid-cols-1 lg:grid-cols-4 gap-4"
                >


                    <div class="lg:col-span-2 relative">

                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search mentor, company, skill..."
                            class="w-full rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl pl-12 pr-5 py-4 text-slate-900 dark:text-white placeholder-slate-400 focus:border-pink-400 focus:ring-2 focus:ring-pink-400/30"
                        >

                    </div>


                    <div class="relative">

                        <select
                            name="department"
                            class="w-full rounded-2xl border border-purple-500/20 bg-slate-900/95 text-white px-5 py-4 pr-12 font-semibold shadow-xl appearance-none focus:border-purple-400 focus:ring-2 focus:ring-purple-400/30"
                        >

                            <option value="">
                                All Departments
                            </option>


                            @foreach($departments as $dept)

                                <option
                                    value="{{ $dept }}"
                                    {{ request('department') === $dept ? 'selected' : '' }}
                                >

                                    {{ $dept }}

                                </option>

                            @endforeach

                        </select>




                    </div>


                    <button
                        type="submit"
                        class="uc-button rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-4 text-white font-black shadow-xl"
                    >

                        <i class="fas fa-search mr-2"></i>

                        Search Mentors

                    </button>

                </form>


                @if(request('search') || request('department'))

                    <div class="mt-5">

                        <a
                            href="{{ route('mentors.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-red-500"
                        >

                            <i class="fas fa-xmark"></i>

                            Clear Filters

                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MENTOR LIST --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 xl:gap-6 items-stretch">


            @forelse($mentors as $mentor)


                @php

                    $mentorship = $myRequests->get($mentor->id);

                    $status = $mentorship?->status;


                    $skills = $mentor->skills
                        ? collect(explode(',', $mentor->skills))
                            ->map(fn ($skill) => trim($skill))
                            ->filter()
                            ->take(4)
                        : collect([
                            'Career Guidance',
                            'Interview',
                            'Portfolio',
                            'Networking'
                        ]);

                @endphp


                <div class="uc-card mentor-card p-6">

                    <div class="mentor-card-body">


                        {{-- MENTOR INFO --}}

                        <div class="flex items-start justify-between gap-4">


                            <div class="flex items-center gap-4 min-w-0">


                                @if($mentor->profile_image)

                                    <img
                                        src="{{ $mentor->getProfileImageUrl() }}"
                                        class="mentor-avatar h-20 w-20 shrink-0 rounded-3xl object-cover bg-white shadow-lg ring-1 ring-white/30"
                                        alt="{{ $mentor->name }}"
                                    >

                                @else

                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($mentor->email) }}"
                                        class="mentor-avatar h-20 w-20 shrink-0 rounded-3xl bg-white shadow-lg ring-1 ring-white/30"
                                        alt="{{ $mentor->name }}"
                                    >

                                @endif


                                <div class="min-w-0">

                                    <h3 class="text-lg leading-tight font-black text-slate-900 dark:text-white break-words">

                                        {{ $mentor->name }}

                                    </h3>


                                    <p class="mt-1.5 text-sm font-semibold text-slate-500 dark:text-slate-400">

                                        {{ $mentor->current_designation ?: 'Alumni Mentor' }}

                                    </p>


                                    <p class="mt-1 text-sm font-bold text-purple-500/90">

                                        {{ $mentor->current_company ?: 'University Alumni' }}

                                    </p>

                                </div>

                            </div>


                            <span class="shrink-0 px-2.5 py-1 rounded-full border text-[11px] font-black {{ ($mentor->requires_message_request ?? true) ? 'bg-amber-400/10 border-amber-400/25 text-amber-300' : 'bg-emerald-400/10 border-emerald-400/25 text-emerald-300' }}">
                                <i class="fas {{ ($mentor->requires_message_request ?? true) ? 'fa-lock' : 'fa-comment-dots' }} mr-1" aria-hidden="true"></i>
                                {{ ($mentor->requires_message_request ?? true) ? 'Request Required' : 'No Request Needed' }}
                            </span>

                        </div>


                        {{-- DEPARTMENT --}}

                        @if($mentor->department)

                            <div class="mt-4">

                                <span class="inline-flex items-center gap-2 rounded-full bg-cyan-500/10 text-cyan-600 px-3 py-1.5 text-xs font-black">

                                    <i class="fas fa-building-columns"></i>

                                    {{ $mentor->department }}

                                </span>

                            </div>

                        @endif


                        {{-- STATUS --}}

                        @if($status)

                            <div class="mt-5">


                                @if($status === 'pending')

                                    <div class="rounded-2xl bg-amber-500/10 border border-amber-500/20 p-4">

                                        <p class="font-black text-amber-600">

                                            <i class="fas fa-clock mr-2"></i>

                                            Request Pending

                                        </p>

                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">

                                            Waiting for mentor response.

                                        </p>

                                    </div>


                                @elseif($status === 'accepted')

                                    <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4">

                                        <p class="font-black text-emerald-600">

                                            <i class="fas fa-handshake mr-2"></i>

                                            Active Mentorship

                                        </p>


                                        @if($mentorship?->started_at)

                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">

                                                Started:
                                                {{ $mentorship->started_at->format('d M Y') }}

                                            </p>

                                        @endif

                                    </div>


                                @elseif($status === 'completed')

                                    <div class="rounded-2xl bg-blue-500/10 border border-blue-500/20 p-4">

                                        <p class="font-black text-blue-600">

                                            <i class="fas fa-flag-checkered mr-2"></i>

                                            Mentorship Completed

                                        </p>


                                        @if($mentorship?->ended_at)

                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">

                                                Completed:
                                                {{ $mentorship->ended_at->format('d M Y') }}

                                            </p>

                                        @endif

                                    </div>


                                @elseif($status === 'rejected')

                                    <div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-4">

                                        <p class="font-black text-red-600">

                                            <i class="fas fa-circle-xmark mr-2"></i>

                                            Request Rejected

                                        </p>


                                        @if($mentorship?->rejection_reason)

                                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-5">

                                                {{ $mentorship->rejection_reason }}

                                            </p>

                                        @endif

                                    </div>

                                @endif


                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- ACTIONS --}}
                        {{-- ================================================= --}}

                        <div class="mentor-actions pt-5 flex flex-col gap-3">


                            {{-- PROFILE --}}

                            <a
                                href="{{ route('profiles.alumni.show', $mentor) }}"
                                class="uc-button mentor-profile-button w-full rounded-2xl py-3 font-black text-center"
                            >

                                <i class="fas fa-user mr-2"></i>

                                View Profile

                            </a>


                            {{-- NO REQUEST --}}

                            @if(auth()->user()->canMessage($mentor))
                                <a href="{{ route('messages.show', $mentor) }}" class="uc-button block w-full rounded-2xl bg-indigo-600 py-3 text-center font-black text-white"><i class="fas fa-comment-dots mr-2"></i>Message</a>
                            @elseif(!$status)

                                <form
                                    method="POST"
                                    action="{{ route('mentors.request', $mentor) }}"
                                >

                                    @csrf


                                    <div class="mb-3">

                                        <label
                                            for="description_{{ $mentor->id }}"
                                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                                        >

                                            Mentorship Request Message

                                            <span class="font-normal text-slate-400">
                                                (Optional)
                                            </span>

                                        </label>


                                        <textarea
                                            id="description_{{ $mentor->id }}"
                                            name="description"
                                            rows="3"
                                            maxlength="2000"
                                            placeholder="Tell the mentor what kind of guidance you need..."
                                            class="mentor-message w-full rounded-2xl border border-purple-500/20 bg-purple-500/5 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                                        ></textarea>

                                    </div>


                                    <button
                                        type="submit"
                                        class="uc-button w-full rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 py-3 text-white font-black shadow-xl"
                                    >

                                        <i class="fas fa-paper-plane mr-2"></i>

                                        Request Mentorship

                                    </button>

                                </form>


                            {{-- PENDING --}}

                            @elseif($status === 'pending')

                                <form
                                    method="POST"
                                    action="{{ route('mentors.cancel', $mentor) }}"
                                    onsubmit="return confirm('Are you sure you want to cancel this mentorship request?');"
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="uc-button w-full rounded-2xl bg-red-500/10 border border-red-500/20 py-3 text-red-600 font-black hover:bg-red-500 hover:text-white"
                                    >

                                        <i class="fas fa-xmark mr-2"></i>

                                        Cancel Request

                                    </button>

                                </form>


                            {{-- ACCEPTED --}}

                            @elseif($status === 'accepted')

                                <div class="w-full rounded-2xl bg-emerald-500/10 border border-emerald-500/20 py-3 text-center text-emerald-600 font-black">

                                    <i class="fas fa-handshake mr-2"></i>

                                    Mentorship Active

                                </div>


                            {{-- REJECTED --}}

                            @elseif($status === 'rejected')

                                <form
                                    method="POST"
                                    action="{{ route('mentors.request', $mentor) }}"
                                    onsubmit="return confirm('Send another mentorship request to this mentor?');"
                                >

                                    @csrf


                                    <input
                                        type="hidden"
                                        name="description"
                                        value="I would like to request mentorship again for career guidance."
                                    >


                                    <button
                                        type="submit"
                                        class="uc-button w-full rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 py-3 text-white font-black shadow-xl"
                                    >

                                        <i class="fas fa-rotate-right mr-2"></i>

                                        Request Again

                                    </button>

                                </form>


                            {{-- COMPLETED --}}

                            @elseif($status === 'completed')

                                <div class="w-full rounded-2xl bg-blue-500/10 border border-blue-500/20 py-3 text-center text-blue-600 font-black">

                                    <i class="fas fa-flag-checkered mr-2"></i>

                                    Mentorship Completed

                                </div>

                            @endif


                        </div>

                    </div>

                </div>


            @empty


                <div class="md:col-span-2 xl:col-span-3 uc-card p-12 text-center">

                    <div class="relative z-10">


                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-xl">

                            <i class="fas fa-user-tie text-3xl text-white"></i>

                        </div>


                        <h3 class="mt-6 text-2xl font-black text-slate-900 dark:text-white">

                            No Alumni Mentors Found

                        </h3>


                        @if(request('search') || request('department'))

                            <p class="mt-3 text-slate-500 dark:text-slate-300">

                                No mentor matches your current filter.

                            </p>


                            <a
                                href="{{ route('mentors.index') }}"
                                class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-3 text-white font-black"
                            >

                                <i class="fas fa-rotate-left"></i>

                                Clear Filters

                            </a>

                        @else

                            <p class="mt-3 text-slate-500 dark:text-slate-300">

                                No active alumni mentor is currently available.

                            </p>

                        @endif


                    </div>

                </div>


            @endforelse


        </div>

        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($mentors->hasPages())
            <div class="uc-card p-4 sm:p-5">
                <div class="relative z-10">
                    {{ $mentors->onEachSide(1)->links() }}
                </div>
            </div>
        @endif

    </div>

</x-app-layout>