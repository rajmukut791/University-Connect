<x-app-layout>
    <div class="mx-auto max-w-7xl px-4"><x-post-lifecycle-status :post="$event" /><x-post-actions :post="$event" type="event" :post-id="$event->id" :owner-id="$event->created_by" /></div>
    @if($isOpen && $isFull)
        <div class="mx-auto my-4 max-w-7xl rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 font-bold text-amber-300">Participation Full — {{ $approvedCount }} / {{ $event->capacity }} approved participants. All places are filled; new registrations are closed.</div>
    @endif

    @if($isAdminPanel || (int) $event->created_by === (int) auth()->id())
        <div class="mx-auto my-5 max-w-7xl">
            <a href="{{ route('events.participants', $event) }}" class="inline-block rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white">Registered Participants ({{ $participantCount }})</a>
        </div>
    @endif



    <div class="uc-event-details min-h-screen">

        <style>

            @keyframes eventFloat {
                0%, 100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-12px);
                }
            }


            @keyframes eventPulse {
                0%, 100% {
                    opacity: .55;
                    transform: scale(1);
                }

                50% {
                    opacity: .9;
                    transform: scale(1.08);
                }
            }


            .event-orb {
                animation:
                    eventFloat
                    7s
                    ease-in-out
                    infinite;
            }


            .event-pulse {
                animation:
                    eventPulse
                    6s
                    ease-in-out
                    infinite;
            }


            .event-glass {
                background:
                    linear-gradient(
                        135deg,
                        rgba(255,255,255,.09),
                        rgba(255,255,255,.035)
                    );

                border:
                    1px solid
                    rgba(255,255,255,.11);

                backdrop-filter:
                    blur(20px);

                box-shadow:
                    0 25px 70px
                    rgba(0,0,0,.25);
            }

        </style>


        <div
            class="uc-event-details-shell relative
                   overflow-hidden
                   rounded-[2rem]
                   bg-gradient-to-br
                   from-slate-950
                   via-indigo-950
                   to-purple-950
                   p-6
                   md:p-8"
        >

            {{-- BACKGROUND --}}

            <div
                class="event-orb
                       absolute
                       -right-24
                       -top-24
                       h-80
                       w-80
                       rounded-full
                       bg-purple-500/20
                       blur-3xl">
            </div>


            <div
                class="event-pulse
                       absolute
                       -bottom-32
                       -left-24
                       h-96
                       w-96
                       rounded-full
                       bg-cyan-500/20
                       blur-3xl">
            </div>


            {{-- HEADER --}}

            <div
                class="uc-compact-header relative
                       z-10
                       mb-7
                       flex
                       flex-col
                       gap-4
                       lg:flex-row
                       lg:items-center
                       lg:justify-between"
            >

                <div>

                    <div
                        class="inline-flex
                               items-center
                               gap-2
                               rounded-full
                               border
                               border-cyan-400/20
                               bg-cyan-400/10
                               px-4
                               py-2
                               text-xs
                               font-black
                               uppercase
                               tracking-[.25em]
                               text-cyan-300"
                    >

                        <i class="fas fa-calendar-days"></i>

                        University Event

                    </div>


                    <h1
                        class="uc-header-context mt-4
                               text-3xl
                               font-black
                               text-white
                               md:text-5xl"
                    >

                        {{ $event->title }}

                    </h1>


                    <p
                        class="mt-3
                               text-sm
                               font-semibold
                               text-slate-400"
                    >

                        Complete event information
                        and registration details.

                    </p>

                </div>


                <a
                    href="{{ route('events.index') }}"

                    class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-2xl
                           border
                           border-white/10
                           bg-white/10
                           px-5
                           py-3
                           text-sm
                           font-black
                           text-white
                           transition
                           hover:scale-105
                           hover:bg-white/15"
                >

                    <i class="fas fa-arrow-left"></i>

                    Back to Events

                </a>

            </div>


            {{-- CONTENT --}}

            <div
                class="relative
                       z-10
                       grid
                       grid-cols-1
                       gap-6
                       xl:grid-cols-12"
            >


                {{-- LEFT SIDE --}}

                <div class="space-y-6 xl:col-span-8">


                    {{-- MAIN EVENT --}}

                    <div
                        class="event-glass
                               overflow-hidden
                               rounded-[2rem]"
                    >

                        @if($event->cover_image)

                            <div class="relative">

                                <img
                                    src="{{ route('secure.events.cover', $event) }}"

                                    alt="{{ $event->title }}"

                                    class="h-[320px]
                                           w-full
                                           object-cover
                                           md:h-[420px]"
                                >


                                <div
                                    class="absolute
                                           inset-0
                                           bg-gradient-to-t
                                           from-slate-950
                                           via-transparent
                                           to-transparent">
                                </div>

                            </div>

                        @else

                            <div
                                class="flex
                                       h-[280px]
                                       items-center
                                       justify-center
                                       bg-gradient-to-br
                                       from-emerald-500/20
                                       via-cyan-500/10
                                       to-purple-500/20"
                            >

                                <div
                                    class="flex
                                           h-28
                                           w-28
                                           items-center
                                           justify-center
                                           rounded-[2rem]
                                           bg-gradient-to-br
                                           from-emerald-500
                                           to-cyan-500
                                           text-5xl
                                           text-white
                                           shadow-2xl"
                                >

                                    <i class="fas fa-calendar-days"></i>

                                </div>

                            </div>

                        @endif


                        <div class="p-6 md:p-8">

                            <div
                                class="flex
                                       flex-wrap
                                       items-center
                                       gap-3"
                            >

                                <span
                                    class="rounded-full
                                           bg-emerald-500/15
                                           px-4
                                           py-2
                                           text-xs
                                           font-black
                                           text-emerald-300"
                                >

                                    {{ ucfirst(
                                        $event->type
                                        ?? 'Event'
                                    ) }}

                                </span>


                                <span
                                    class="rounded-full
                                           px-4
                                           py-2
                                           text-xs
                                           font-black
                                           {{ $isOpen
                                                ? 'bg-cyan-500/15 text-cyan-300'
                                                : 'bg-red-500/15 text-red-300'
                                           }}"
                                >

                                    {{ strtoupper(
                                        $event->status
                                        ?? 'ACTIVE'
                                    ) }}

                                </span>

                            </div>


                            <h2
                                class="mt-6
                                       text-2xl
                                       font-black
                                       text-white"
                            >

                                About This Event

                            </h2>


                            <p
                                class="mt-4
                                       whitespace-pre-line
                                       text-sm
                                       leading-7
                                       text-slate-300"
                            >{{ $event->description }}</p>

                        </div>

                    </div>


                    {{-- ADMIN MANAGEMENT --}}

                    @if($isAdminPanel)

                        <div
                            class="event-glass
                                   rounded-[2rem]
                                   p-6"
                        >

                            <div
                                class="flex
                                       flex-col
                                       gap-4
                                       md:flex-row
                                       md:items-center
                                       md:justify-between"
                            >

                                <div>

                                    <h3
                                        class="text-xl
                                               font-black
                                               text-white"
                                    >

                                        Event Management

                                    </h3>


                                    <p
                                        class="mt-1
                                               text-sm
                                               text-slate-400"
                                    >

                                        Edit or remove this event.

                                    </p>

                                </div>


                                <div
                                    class="flex
                                           flex-col
                                           gap-3
                                           sm:flex-row"
                                >

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
                                               px-6
                                               py-3
                                               text-sm
                                               font-black
                                               text-white"
                                    >

                                        <i class="fas fa-pen-to-square"></i>

                                        Edit Event

                                    </a>


                                    <form
                                        method="POST"

                                        action="{{ route(
                                            'events.destroy',
                                            $event
                                        ) }}"

                                        onsubmit="
                                            return confirm(
                                                'Are you sure you want to delete this event?'
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
                                                   px-6
                                                   py-3
                                                   text-sm
                                                   font-black
                                                   text-white"
                                        >

                                            <i class="fas fa-trash-can"></i>

                                            Delete Event

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>


                {{-- RIGHT SIDE --}}

                <div class="space-y-6 xl:col-span-4">


                    {{-- EVENT DETAILS --}}

                    <div
                        class="event-glass
                               rounded-[2rem]
                               p-6"
                    >

                        <h3
                            class="text-xl
                                   font-black
                                   text-white"
                        >

                            Event Details

                        </h3>


                        <div class="mt-6 space-y-5">


                            {{-- DATE --}}

                            <div class="flex gap-4">

                                <div
                                    class="flex
                                           h-11
                                           w-11
                                           shrink-0
                                           items-center
                                           justify-center
                                           rounded-xl
                                           bg-emerald-500/15
                                           text-emerald-300"
                                >

                                    <i class="fas fa-calendar"></i>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                               font-black
                                               uppercase
                                               tracking-wider
                                               text-slate-500"
                                    >
                                        Date & Time
                                    </p>


                                    <p
                                        class="mt-1
                                               text-sm
                                               font-bold
                                               text-white"
                                    >

                                        {{ $event->event_date
                                            ? $event->event_date->format(
                                                'd M Y, h:i A'
                                            )
                                            : 'Date not set'
                                        }}

                                    </p>

                                </div>

                            </div>


                            {{-- LOCATION --}}

                            <div class="flex gap-4">

                                <div
                                    class="flex
                                           h-11
                                           w-11
                                           shrink-0
                                           items-center
                                           justify-center
                                           rounded-xl
                                           bg-pink-500/15
                                           text-pink-300"
                                >

                                    <i class="fas fa-location-dot"></i>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                               font-black
                                               uppercase
                                               tracking-wider
                                               text-slate-500"
                                    >
                                        Location
                                    </p>


                                    <p
                                        class="mt-1
                                               text-sm
                                               font-bold
                                               text-white"
                                    >

                                        {{ $event->location
                                            ?? 'Not specified'
                                        }}

                                    </p>

                                </div>

                            </div>


                            {{-- CAPACITY --}}

                            <div class="flex gap-4">

                                <div
                                    class="flex
                                           h-11
                                           w-11
                                           shrink-0
                                           items-center
                                           justify-center
                                           rounded-xl
                                           bg-cyan-500/15
                                           text-cyan-300"
                                >

                                    <i class="fas fa-users"></i>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                               font-black
                                               uppercase
                                               tracking-wider
                                               text-slate-500"
                                    >
                                        Participants
                                    </p>


                                    <p
                                        class="mt-1
                                               text-sm
                                               font-bold
                                               text-white"
                                    >

                                        {{ $participantCount }}

                                        /

                                        {{ $event->capacity
                                            ?? 'Unlimited'
                                        }}

                                    </p>

                                </div>

                            </div>


                            {{-- CREATOR --}}

                            <div class="flex gap-4">

                                <div
                                    class="flex
                                           h-11
                                           w-11
                                           shrink-0
                                           items-center
                                           justify-center
                                           rounded-xl
                                           bg-purple-500/15
                                           text-purple-300"
                                >

                                    <i class="fas fa-user-shield"></i>

                                </div>


                                <div>

                                    <p
                                        class="text-xs
                                               font-black
                                               uppercase
                                               tracking-wider
                                               text-slate-500"
                                    >
                                        Created By
                                    </p>


                                    <p
                                        class="mt-1
                                               text-sm
                                               font-bold
                                               text-white"
                                    >

                                        {{ $event->creator?->name
                                            ?? 'University Admin'
                                        }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- REGISTRATION --}}

                    @if(
                        in_array(
                            auth()->user()->role,
                            ['student', 'alumni'],
                            true
                        ) && (int) $event->created_by !== (int) auth()->id()
                    )

                        <div
                            class="event-glass
                                   rounded-[2rem]
                                   p-6"
                        >

                            <h3
                                class="text-xl
                                       font-black
                                       text-white"
                            >

                                Registration

                            </h3>


                            <div class="mt-5">


                                @if($myRegistration)
                                    <x-event-cancel-participation :event="$event" :status="$myRegistration->status" />


                                    @if(
                                        $myRegistration->status
                                        === 'pending'
                                    )

                                        <div
                                            class="rounded-2xl
                                                   bg-amber-500/15
                                                   p-4
                                                   text-center
                                                   font-black
                                                   text-amber-300"
                                        >

                                            <i class="fas fa-clock mr-2"></i>

                                            <form method="POST" action="{{ route('events.register', $event) }}">@csrf<button type="submit" class="font-bold">Complete Registration</button></form>

                                        </div>


                                    @elseif(
                                        $myRegistration->status
                                        === 'approved'
                                    )

                                        <div
                                            class="rounded-2xl
                                                   bg-emerald-500/15
                                                   p-4
                                                   text-center
                                                   font-black
                                                   text-emerald-300"
                                        >

                                            <i class="fas fa-circle-check mr-2"></i>

                                            Registration Approved

                                        </div>


                                    @elseif(
                                        $myRegistration->status
                                        === 'rejected'
                                    )

                                        <div
                                            class="rounded-2xl
                                                   bg-red-500/15
                                                   p-4
                                                   text-center
                                                   font-black
                                                   text-red-300"
                                        >

                                            <i class="fas fa-circle-xmark mr-2"></i>

                                            Registration Rejected

                                        </div>

                                    @endif


                                @elseif(!$isOpen)

                                    <div
                                        class="rounded-2xl
                                               bg-red-500/15
                                               p-4
                                               text-center
                                               font-black
                                               text-red-300"
                                    >

                                        Registration Closed

                                    </div>


                                @elseif($isFull)

                                    <div
                                        class="rounded-2xl
                                               bg-red-500/15
                                               p-4
                                               text-center
                                               font-black
                                               text-red-300"
                                    >

                                        Participation Full

                                    </div>


                                @else

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

                                            class="inline-flex
                                                   w-full
                                                   items-center
                                                   justify-center
                                                   gap-2
                                                   rounded-2xl
                                                   bg-gradient-to-r
                                                   from-emerald-500
                                                   to-cyan-500
                                                   px-6
                                                   py-4
                                                   text-sm
                                                   font-black
                                                   text-white
                                                   shadow-xl
                                                   transition
                                                   hover:scale-[1.02]"
                                        >

                                            <i class="fas fa-ticket"></i>

                                            Register for Event

                                        </button>

                                    </form>

                                @endif

                            </div>

                        </div>

                    @endif


                    {{-- ADMIN STATS --}}

                    @if($isAdminPanel)

                        <div
                            class="event-glass
                                   rounded-[2rem]
                                   p-6"
                        >

                            <h3
                                class="text-xl
                                       font-black
                                       text-white"
                            >

                                Registration Overview

                            </h3>


                            <div
                                class="mt-5
                                       grid
                                       grid-cols-2
                                       gap-3"
                            >

                                <div
                                    class="rounded-2xl
                                           bg-emerald-500/10
                                           p-4"
                                >

                                    <p
                                        class="text-xs
                                               font-bold
                                               text-slate-400"
                                    >
                                        Approved
                                    </p>


                                    <p
                                        class="mt-2
                                               text-3xl
                                               font-black
                                               text-emerald-300"
                                    >

                                        {{ $approvedCount }}

                                    </p>

                                </div>


                                <div
                                    class="rounded-2xl
                                           bg-amber-500/10
                                           p-4"
                                >

                                    <p
                                        class="text-xs
                                               font-bold
                                               text-slate-400"
                                    >
                                        Pending
                                    </p>


                                    <p
                                        class="mt-2
                                               text-3xl
                                               font-black
                                               text-amber-300"
                                    >

                                        {{ $pendingCount }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

    @include('components.event-details-style')
</x-app-layout>
