<x-app-layout>

    @php
        $user = auth()->user();

        $isAdminPanel = $user &&
            in_array($user->role, ['admin', 'super_admin'], true);

        $isUserPanel = $user &&
            in_array($user->role, ['student', 'alumni'], true);
    @endphp


    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <x-slot name="header">

        <div class="relative overflow-hidden
                    rounded-3xl
                    bg-gradient-to-r
                    from-slate-950
                    via-emerald-950
                    to-cyan-950
                    p-8
                    shadow-2xl
                    border border-white/10">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.35),transparent_35%)]">
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
                              text-emerald-300
                              font-black">

                        {{ $isAdminPanel
                            ? 'Event Management Center'
                            : 'University Event Hub'
                        }}

                    </p>


                    <h2 class="mt-3
                               text-4xl
                               lg:text-5xl
                               font-black
                               text-white">

                        {{ $isAdminPanel
                            ? 'Event Requests & Management'
                            : 'University Events'
                        }}

                    </h2>


                    <p class="mt-3
                              text-slate-300
                              max-w-2xl">

                        @if($isAdminPanel)

                            Create university events, manage registration
                            requests, and monitor event participation.

                        @else

                            Explore upcoming university events,
                            discover activities, and register for events
                            that interest you.

                        @endif

                    </p>


                    @if($isAdminPanel)

                        <div class="mt-6">

                            <a
                                href="{{ route('events.create') }}"
                                class="inline-flex
                                       items-center
                                       gap-2
                                       rounded-xl
                                       bg-white/10
                                       border border-white/15
                                       px-4 py-2.5
                                       text-sm
                                       font-black
                                       text-white
                                       hover:bg-white/20
                                       hover:scale-105
                                       transition-all
                                       duration-300"
                            >

                                <i class="fas fa-calendar-plus
                                          text-emerald-300">
                                </i>

                                <span>Add Event</span>

                            </a>

                        </div>

                    @endif

                </div>



                {{-- Admin Pending Request Indicator --}}

                @if($isAdminPanel)

                    <div class="rounded-3xl
                                bg-white/10
                                backdrop-blur-xl
                                border border-white/10
                                px-6 py-5">

                        <p class="text-xs text-slate-300">
                            Registration Requests
                        </p>

                        <p class="mt-1
                                  text-2xl
                                  font-black
                                  text-amber-300">

                            {{ $stats['approved_participants'] ?? 0 }}
                            Pending

                        </p>

                        <p class="text-xs
                                  text-slate-400
                                  mt-1">

                            Waiting for review

                        </p>

                    </div>

                @endif

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
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-5px);
            border-color: rgba(34,211,238,.30);
        }

        .event-action-card {
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background:
                linear-gradient(
                    135deg,
                    rgba(15,23,42,.92),
                    rgba(30,41,59,.70)
                );
            box-shadow:
                0 24px 70px rgba(15,23,42,.25);
        }

    </style>



    {{-- ============================================================= --}}
    {{-- CONTENT --}}
    {{-- ============================================================= --}}

    <div class="space-y-8">


        {{-- ========================================================= --}}
        {{-- ADMIN / SUPER ADMIN CONTROL --}}
        {{-- Student/Alumni will NEVER see this --}}
        {{-- ========================================================= --}}





        {{-- ========================================================= --}}
        {{-- SUCCESS --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="uc-card
                        p-5
                        text-emerald-500
                        font-black">

                <i class="fas fa-circle-check mr-2"></i>

                {{ session('success') }}

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="uc-card
                        p-5
                        text-red-500
                        font-black">

                @foreach($errors->all() as $error)

                    <p>{{ $error }}</p>

                @endforeach

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- STATISTICS --}}
        {{-- ========================================================= --}}

        @if($isAdminPanel)

            {{-- ADMIN / SUPER ADMIN: 4 MANAGEMENT STATS --}}

            <div class="grid
                        grid-cols-1
                        sm:grid-cols-2
                        xl:grid-cols-4
                        gap-6">


                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                Published Events

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-emerald-500">

                                {{ $stats['total_events'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-emerald-500/15
                                    flex items-center
                                    justify-center
                                    text-emerald-500">

                            <i class="fas fa-calendar-days"></i>

                        </div>

                    </div>

                </div>



                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                Upcoming Events

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-cyan-500">

                                {{ $stats['upcoming_events'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-cyan-500/15
                                    flex items-center
                                    justify-center
                                    text-cyan-500">

                            <i class="fas fa-clock"></i>

                        </div>

                    </div>

                </div>



                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                Registered Participants

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-amber-500">

                                {{ $stats['approved_participants'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-amber-500/15
                                    flex items-center
                                    justify-center
                                    text-amber-500">

                            <i class="fas fa-hourglass-half"></i>

                        </div>

                    </div>

                </div>



                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                Approved Participants

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-pink-500">

                                {{ $stats['approved_participants'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-pink-500/15
                                    flex items-center
                                    justify-center
                                    text-pink-500">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

            </div>


        @else

            {{-- STUDENT / ALUMNI: ONLY 3 PERSONAL STATS --}}

            <div class="grid
                        grid-cols-1
                        md:grid-cols-3
                        gap-6">


                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                Available Events

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-emerald-500">

                                {{ $stats['total_events'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-emerald-500/15
                                    flex items-center
                                    justify-center
                                    text-emerald-500">

                            <i class="fas fa-calendar-days"></i>

                        </div>

                    </div>

                </div>



                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                Upcoming Events

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-cyan-500">

                                {{ $stats['upcoming_events'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-cyan-500/15
                                    flex items-center
                                    justify-center
                                    text-cyan-500">

                            <i class="fas fa-clock"></i>

                        </div>

                    </div>

                </div>



                <div class="uc-card p-6">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-bold
                                      text-slate-500">

                                My Registrations

                            </p>

                            <h3 class="mt-3
                                       text-4xl
                                       font-black
                                       text-purple-500">

                                {{ $stats['my_registrations'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="h-12 w-12
                                    rounded-2xl
                                    bg-purple-500/15
                                    flex items-center
                                    justify-center
                                    text-purple-500">

                            <i class="fas fa-ticket"></i>

                        </div>

                    </div>

                </div>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- EVENT CARDS --}}
        {{-- ========================================================= --}}

        <div id="event-list" class="uc-post-grid grid
                    grid-cols-1
                    md:grid-cols-2
                    xl:grid-cols-3
                    gap-6">


            @forelse($events as $event)

                @php
                    $isRegistered =
                        isset($registeredEvents[$event->id]);

                    $participants =
                        $event->participants->count();

                    $isFull =
                        $event->capacity &&
                        $participants >= $event->capacity;

                    $isOpen = \App\Services\PostLifecycleService::accepting($event) &&
                        in_array(
                            $event->status,
                            [
                                'active',
                                'published',
                                'approved'
                            ],
                            true
                        );
                @endphp


                <div class="uc-card p-6">

                    <div class="relative z-10">


                        {{-- Icon --}}

                        <div class="h-20 w-20
                                    rounded-3xl
                                    bg-gradient-to-br
                                    from-emerald-500
                                    to-cyan-500
                                    flex items-center
                                    justify-center
                                    shadow-xl">

                            <i class="fas fa-calendar-days
                                      text-3xl
                                      text-white">
                            </i>

                        </div>



                        {{-- Type / Status --}}

                        <div class="mt-6
                                    flex
                                    items-center
                                    justify-between
                                    gap-3">

                            <span class="px-3 py-1
                                         rounded-full
                                         bg-emerald-500/15
                                         text-emerald-600
                                         text-xs
                                         font-black">

                                {{ ucfirst($event->type ?? 'Event') }}

                            </span>


                            @if($isAdminPanel)

                                <span class="px-3 py-1
                                             rounded-full
                                             {{ $isOpen
                                                ? 'bg-cyan-500/15 text-cyan-600'
                                                : 'bg-red-500/15 text-red-600'
                                             }}
                                             text-xs
                                             font-black">

                                    {{ strtoupper(
                                        $event->status ?? 'PUBLISHED'
                                    ) }}

                                </span>

                            @else

                                <span class="px-3 py-1
                                             rounded-full
                                             bg-cyan-500/15
                                             text-cyan-600
                                             text-xs
                                             font-black">

                                    {{ !$isOpen ? 'Registration Closed' : ($isFull ? 'Participation Full' : 'Available') }}

                                </span>

                            @endif

                        </div>



                        @if($isOpen && $isFull)
                            <p class="mt-4 rounded-xl bg-amber-500/10 p-3 font-bold text-amber-300">Participation Full — {{ $participants }} / {{ $event->capacity }} approved participants. All places are filled.</p>
                        @endif

                        {{-- Title --}}

                        <h3 class="mt-5
                                   text-2xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            {{ $event->title }}

                        </h3>



                        {{-- Description --}}

                        <p class="mt-3
                                  text-sm
                                  text-slate-500
                                  leading-relaxed">

                            {{ \Illuminate\Support\Str::limit(
                                $event->description,
                                120
                            ) }}

                        </p>



                        {{-- Event Details --}}

                        <div class="mt-5
                                    space-y-3
                                    text-sm
                                    text-slate-500">


                            <p>

                                <i class="fas fa-calendar
                                          mr-2
                                          text-emerald-500">
                                </i>

                                {{ $event->event_date
                                    ? $event->event_date->format(
                                        'd M Y, h:i A'
                                    )
                                    : 'Date not set'
                                }}

                            </p>


                            <p>

                                <i class="fas fa-location-dot
                                          mr-2
                                          text-pink-500">
                                </i>

                                {{ $event->location
                                    ?? 'Location not specified'
                                }}

                            </p>


                            <p>

                                <i class="fas fa-users
                                          mr-2
                                          text-cyan-500">
                                </i>

                                {{ $participants }}

                                /

                                {{ $event->capacity
                                    ?? 'Unlimited'
                                }}

                                Participants

                            </p>


                            <a href="{{ route('events.show', $event) }}" class="mb-3 block w-full rounded-xl bg-indigo-600 px-4 py-3 text-center font-bold text-white">{{ $isAdminPanel ? 'View / Manage Event' : 'View Details' }}</a>
                            @if($isAdminPanel)

                                <p>

                                    <i class="fas fa-user-shield
                                              mr-2
                                              text-purple-500">
                                    </i>

                                    {{ $event->creator?->name
                                        ?? 'University Admin'
                                    }}

                                </p>

                            @endif

                        </div>



                        {{-- ================================================= --}}
                        {{-- ACTION --}}
                        {{-- ================================================= --}}

                        <div class="mt-6">


                            {{-- ADMIN --}}

                            @if($isAdminPanel)

    <div class="grid grid-cols-2 gap-3">

        {{-- EDIT EVENT --}}

        <a
            href="{{ route(
                'events.edit',
                $event
            ) }}"

            class="inline-flex
                   items-center
                   justify-center
                   gap-2
                   rounded-2xl
                   bg-gradient-to-r
                   from-cyan-500
                   to-blue-600
                   px-4
                   py-3
                   text-sm
                   font-black
                   text-white
                   shadow-lg
                   shadow-cyan-500/20
                   transition
                   hover:scale-[1.03]
                   hover:shadow-xl"
        >

            <i class="fas fa-pen-to-square"></i>

            Edit Event

        </a>


        {{-- DELETE EVENT --}}

        <form
            method="POST"

            action="{{ route(
                'events.destroy',
                $event
            ) }}"

            onsubmit="
                return confirm(
                    'Are you sure you want to delete this event? This action cannot be undone.'
                );
            "
        >

            @csrf

            @method('DELETE')


            <button
                type="submit"

                class="inline-flex
                       w-full
                       items-center
                       justify-center
                       gap-2
                       rounded-2xl
                       bg-gradient-to-r
                       from-red-500
                       to-rose-600
                       px-4
                       py-3
                       text-sm
                       font-black
                       text-white
                       shadow-lg
                       shadow-red-500/20
                       transition
                       hover:scale-[1.03]
                       hover:shadow-xl"
            >

                <i class="fas fa-trash-can"></i>

                Delete Event

            </button>

        </form>

    </div>


                            {{-- USER ALREADY REGISTERED --}}

                            @elseif((int) $event->created_by === (int) auth()->id())
                                <p class="rounded-xl bg-indigo-500/10 p-3 text-center font-bold text-indigo-300">Your Event</p>
                            @elseif($isRegistered)

                                @php
                                    $myStatus =
                                        $registeredEvents[$event->id];
                                @endphp


                                @if($myStatus === 'pending')

                                    <div class="w-full
                                                rounded-2xl
                                                bg-amber-500/15
                                                text-amber-600
                                                py-3
                                                text-center
                                                font-black">

                                        <i class="fas fa-clock mr-2"></i>

                                        <form method="POST" action="{{ route('events.register', $event) }}">@csrf<button type="submit" class="font-bold">Complete Registration</button></form>

                                    </div>


                                @elseif($myStatus === 'approved')

                                    <div class="w-full
                                                rounded-2xl
                                                bg-emerald-500/15
                                                text-emerald-600
                                                py-3
                                                text-center
                                                font-black">

                                        <i class="fas fa-circle-check mr-2"></i>

                                        Registered

                                    </div>


                                @elseif($myStatus === 'rejected')

                                    <div class="w-full
                                                rounded-2xl
                                                bg-red-500/15
                                                text-red-600
                                                py-3
                                                text-center
                                                font-black">

                                        <i class="fas fa-circle-xmark mr-2"></i>

                                        Registration Unsuccessful

                                    </div>


                                @else

                                    <div class="w-full
                                                rounded-2xl
                                                bg-slate-500/15
                                                text-slate-600
                                                py-3
                                                text-center
                                                font-black">

                                        Registered

                                    </div>

                                @endif
                                <x-event-cancel-participation :event="$event" :status="$myStatus" />




                            {{-- EVENT CLOSED --}}

                            @elseif(!$isOpen)

                                <div class="w-full
                                            rounded-2xl
                                            bg-red-500/15
                                            text-red-600
                                            py-3
                                            text-center
                                            font-black">

                                    Registration Closed

                                </div>



                            {{-- EVENT FULL --}}

                            @elseif($isFull)

                                <div class="w-full
                                            rounded-2xl
                                            bg-red-500/15
                                            text-red-600
                                            py-3
                                            text-center
                                            font-black">

                                    Participation Full

                                </div>



                            {{-- REGISTER --}}

                            @elseif($isUserPanel)

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'events.register',
                                        $event
                                    ) }}"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full
                                               rounded-2xl
                                               bg-gradient-to-r
                                               from-emerald-500
                                               to-cyan-500
                                               py-3
                                               text-white
                                               font-black
                                               shadow-xl
                                               hover:scale-[1.02]
                                               transition"
                                    >

                                        <i class="fas fa-ticket mr-2"></i>

                                        Register for Event

                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                </div>


            @empty


                <div class="xl:col-span-3
                            uc-card
                            p-12
                            text-center">


                    <div class="mx-auto
                                h-16 w-16
                                rounded-2xl
                                bg-emerald-500/10
                                flex
                                items-center
                                justify-center
                                text-emerald-500
                                text-2xl">

                        <i class="fas fa-calendar-days"></i>

                    </div>


                    @if($isAdminPanel)

                        <h3 class="mt-5
                                   text-2xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            No Events Created Yet

                        </h3>


                        <p class="mt-2 text-slate-500">

                            Create your first university event
                            to make it available to the community.

                        </p>


                        <a
                            href="{{ route('events.create') }}"
                            class="mt-5
                                   inline-flex
                                   items-center
                                   gap-2
                                   rounded-xl
                                   bg-gradient-to-r
                                   from-emerald-500
                                   to-cyan-500
                                   px-5 py-3
                                   text-sm
                                   font-black
                                   text-white"
                        >

                            <i class="fas fa-calendar-plus"></i>

                            Add Event

                        </a>


                    @else

                        <h3 class="mt-5
                                   text-2xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            No Events Available

                        </h3>


                        <p class="mt-2 text-slate-500">

                            There are no university events
                            available right now.

                        </p>

                    @endif

                </div>

            @endforelse

        </div>
        <div class="mt-6">{{ $events->links() }}</div>


    </div>

</x-app-layout>