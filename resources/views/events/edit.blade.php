<x-app-layout>

    <div class="min-h-screen text-white">

        <style>

            @keyframes eventFloat {
                0%, 100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-14px) scale(1.03);
                }
            }


            @keyframes eventShine {
                0% {
                    transform: translateX(-120%);
                }

                100% {
                    transform: translateX(120%);
                }
            }


            @keyframes eventPulse {
                0%, 100% {
                    box-shadow: 0 20px 60px rgba(168, 85, 247, .20);
                }

                50% {
                    box-shadow: 0 25px 90px rgba(34, 211, 238, .26);
                }
            }


            .event-orb {
                animation: eventFloat 7s ease-in-out infinite;
            }


            .event-card {
                animation: eventPulse 5s ease-in-out infinite;
            }


            .event-shine::before {
                content: "";
                position: absolute;
                inset: 0;

                background:
                    linear-gradient(
                        90deg,
                        transparent,
                        rgba(255,255,255,.08),
                        transparent
                    );

                transform: translateX(-120%);
                animation: eventShine 7s ease-in-out infinite;

                pointer-events: none;
            }


            .event-input {
                width: 100%;
                border-radius: 1.25rem;

                border:
                    1px solid rgba(255,255,255,.12);

                background:
                    rgba(2,6,23,.72);

                padding: 1rem 1.1rem;

                color: white;

                font-size: .875rem;
                font-weight: 800;

                outline: none;

                transition: .25s ease;
            }


            .event-input::placeholder {
                color: rgba(148,163,184,.75);
            }


            .event-input:focus {
                border-color: rgba(34,211,238,.75);

                box-shadow:
                    0 0 0 5px rgba(34,211,238,.10);

                transform: translateY(-2px);
            }


            .event-label {
                display: flex;
                align-items: center;
                gap: .55rem;

                margin-bottom: .65rem;

                color: rgb(226 232 240);

                font-size: .85rem;
                font-weight: 950;
            }

        </style>


        <div
            class="relative overflow-hidden
                   rounded-[2rem]
                   border border-white/10
                   bg-gradient-to-br
                   from-slate-950
                   via-indigo-950
                   to-slate-950
                   p-6
                   shadow-2xl
                   md:p-8"
        >


            {{-- BACKGROUND ORBS --}}

            <div
                class="event-orb
                       absolute
                       -top-24
                       -right-24
                       h-80
                       w-80
                       rounded-full
                       bg-fuchsia-500/20
                       blur-3xl">
            </div>


            <div
                class="event-orb
                       absolute
                       top-56
                       -left-28
                       h-80
                       w-80
                       rounded-full
                       bg-cyan-500/20
                       blur-3xl"
                style="animation-delay:2s">
            </div>


            <div
                class="event-orb
                       absolute
                       -bottom-28
                       right-1/3
                       h-80
                       w-80
                       rounded-full
                       bg-emerald-500/15
                       blur-3xl"
                style="animation-delay:4s">
            </div>



            {{-- HEADER --}}

            <div
                class="uc-compact-header relative z-10
                       mb-8
                       flex flex-col
                       gap-5
                       lg:flex-row
                       lg:items-center
                       lg:justify-between"
            >

                <div>

                    <div
                        class="mb-4
                               inline-flex
                               items-center
                               gap-2
                               rounded-full
                               border border-cyan-400/20
                               bg-cyan-400/10
                               px-4 py-2
                               text-xs
                               font-black
                               uppercase
                               tracking-[.3em]
                               text-cyan-300"
                    >

                        <i class="fas fa-pen-to-square"></i>

                        Event Management

                    </div>


                    <h1
                        class="text-4xl
                               font-black
                               leading-tight
                               md:text-5xl"
                    >

                        Edit Event

                    </h1>


                    <p
                        class="mt-3
                               max-w-2xl
                               text-sm
                               font-semibold
                               text-slate-400"
                    >

                        Update event information, schedule,
                        capacity, cover image and publication status.

                    </p>

                </div>


                <a
                    href="{{ route('events.index') }}"

                    class="inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-2xl
                           border border-white/10
                           bg-white/10
                           px-5 py-3
                           text-sm
                           font-black
                           text-white
                           shadow-xl
                           transition
                           hover:scale-105
                           hover:bg-white/15"
                >

                    <i class="fas fa-arrow-left"></i>

                    Back to Events

                </a>

            </div>



            {{-- ERRORS --}}

            @if ($errors->any())

                <div
                    class="relative z-10
                           mb-6
                           rounded-2xl
                           border border-red-500/30
                           bg-red-500/10
                           p-5
                           text-sm
                           font-bold
                           text-red-200"
                >

                    <ul class="list-disc pl-5">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- FORM --}}

            <form
                method="POST"

                action="{{ route(
                    'events.update',
                    $event
                ) }}"

                enctype="multipart/form-data"

                class="event-card
                       event-shine
                       relative z-10
                       overflow-hidden
                       rounded-[2rem]
                       border border-white/10
                       bg-white/[.06]
                       p-5
                       shadow-2xl
                       backdrop-blur-2xl
                       md:p-7"
            >

                @csrf

                @method('PATCH')


                <div
                    class="grid
                           grid-cols-1
                           gap-6
                           xl:grid-cols-12"
                >


                    {{-- TITLE --}}

                    <div class="xl:col-span-8">

                        <label class="event-label">

                            <i class="fas fa-heading text-cyan-300"></i>

                            Event Title

                        </label>


                        <input
                            type="text"

                            name="title"

                            value="{{ old(
                                'title',
                                $event->title
                            ) }}"

                            required

                            placeholder="Example: Career Development Workshop"

                            class="event-input"
                        >

                    </div>



                    {{-- TYPE --}}

                    <div class="xl:col-span-4">

                        <label class="event-label">

                            <i class="fas fa-layer-group text-purple-300"></i>

                            Event Type

                        </label>


                        <input
                            type="text"

                            name="type"

                            value="{{ old(
                                'type',
                                $event->type
                            ) }}"

                            required

                            placeholder="Workshop, Seminar, Networking"

                            class="event-input"
                        >

                    </div>



                    {{-- CURRENT COVER --}}

                    @if($event->cover_image)

                        <div class="xl:col-span-12">

                            <label class="event-label">

                                <i class="fas fa-image text-emerald-300"></i>

                                Current Cover

                            </label>


                            <div
                                class="overflow-hidden
                                       rounded-[1.6rem]
                                       border border-white/10
                                       bg-slate-950/60
                                       p-4"
                            >

                                <img
                                    src="{{ route('secure.events.cover', $event) }}"

                                    alt="{{ $event->title }}"

                                    class="max-h-72
                                           w-full
                                           rounded-2xl
                                           object-cover"
                                >

                            </div>

                        </div>

                    @endif



                    {{-- NEW COVER --}}

                    <div class="xl:col-span-12">

                        <label class="event-label">

                            <i class="fas fa-image text-pink-300"></i>

                            Change Cover Picture

                        </label>


                        <label
                            class="group
                                   flex
                                   cursor-pointer
                                   flex-col
                                   items-center
                                   justify-center
                                   rounded-[1.6rem]
                                   border
                                   border-dashed
                                   border-white/20
                                   bg-slate-950/60
                                   px-6 py-8
                                   text-center
                                   transition
                                   hover:border-cyan-400/60
                                   hover:bg-cyan-400/5"
                        >

                            <div
                                class="mb-3
                                       flex
                                       h-16
                                       w-16
                                       items-center
                                       justify-center
                                       rounded-2xl
                                       bg-gradient-to-br
                                       from-indigo-600
                                       via-purple-600
                                       to-pink-500
                                       text-2xl
                                       shadow-xl
                                       transition
                                       group-hover:scale-110"
                            >

                                <i class="fas fa-cloud-arrow-up"></i>

                            </div>


                            <p class="text-base font-black text-white">

                                Upload New Event Cover

                            </p>


                            <p
                                class="mt-1
                                       text-xs
                                       font-semibold
                                       text-slate-400"
                            >

                                Leave empty to keep the current image.
                                JPG, PNG, WEBP — Max 4MB.

                            </p>


                            <input
                                type="file"

                                name="cover_image"

                                accept="image/*"

                                class="hidden"
                            >

                        </label>

                    </div>



                    {{-- DESCRIPTION --}}

                    <div class="xl:col-span-12">

                        <label class="event-label">

                            <i class="fas fa-align-left text-emerald-300"></i>

                            Event Description

                        </label>


                        <textarea
                            name="description"

                            rows="6"

                            required

                            placeholder="Write event details..."

                            class="event-input min-h-[170px]"
                        >{{ old(
                            'description',
                            $event->description
                        ) }}</textarea>

                    </div>



                    {{-- LOCATION --}}

                    <div class="xl:col-span-6">

                        <label class="event-label">

                            <i class="fas fa-location-dot text-red-300"></i>

                            Location

                        </label>


                        <input
                            type="text"

                            name="location"

                            value="{{ old(
                                'location',
                                $event->location
                            ) }}"

                            required

                            placeholder="University Auditorium Hall"

                            class="event-input"
                        >

                    </div>



                    {{-- CAPACITY --}}

                    <div class="xl:col-span-6">

                        <label class="event-label">

                            <i class="fas fa-users text-blue-300"></i>

                            Capacity

                        </label>


                        <input
                            type="number"

                            name="capacity"

                            value="{{ old(
                                'capacity',
                                $event->capacity
                            ) }}"

                            min="1"

                            placeholder="Example: 100"

                            class="event-input"
                        >

                    </div>



                    {{-- START DATE --}}

                    <div class="xl:col-span-3">

                        <label class="event-label">

                            <i class="fas fa-calendar-day text-cyan-300"></i>

                            Start Date

                        </label>


                        <input
                            type="date"

                            name="start_date"

                            value="{{ old(
                                'start_date',
                                $event->start_date
                                    ? \Illuminate\Support\Carbon::parse(
                                        $event->start_date
                                    )->format('Y-m-d')
                                    : optional(
                                        $event->event_date
                                    )->format('Y-m-d')
                            ) }}"

                            required

                            class="event-input"
                        >

                    </div>



                    {{-- START TIME --}}

                    <div class="xl:col-span-3">

                        <label class="event-label">

                            <i class="fas fa-clock text-emerald-300"></i>

                            Start Time

                        </label>


                        <input
                            type="time"

                            name="start_time"

                            value="{{ old(
                                'start_time',
                                $event->start_time
                                    ? \Illuminate\Support\Carbon::parse(
                                        $event->start_time
                                    )->format('H:i')
                                    : optional(
                                        $event->event_date
                                    )->format('H:i')
                            ) }}"

                            required

                            class="event-input"
                        >

                    </div>



                    {{-- END DATE --}}

                    <div class="xl:col-span-3">

                        <label class="event-label">

                            <i class="fas fa-calendar-check text-purple-300"></i>

                            End Date

                        </label>


                        <input
                            type="date"

                            name="end_date"

                            value="{{ old(
                                'end_date',
                                $event->end_date
                                    ? \Illuminate\Support\Carbon::parse(
                                        $event->end_date
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"

                            required

                            class="event-input"
                        >

                    </div>



                    {{-- END TIME --}}

                    <div class="xl:col-span-3">

                        <label class="event-label">

                            <i class="fas fa-hourglass-end text-pink-300"></i>

                            End Time

                        </label>


                        <input
                            type="time"

                            name="end_time"

                            value="{{ old(
                                'end_time',
                                $event->end_time
                                    ? \Illuminate\Support\Carbon::parse(
                                        $event->end_time
                                    )->format('H:i')
                                    : ''
                            ) }}"

                            required

                            class="event-input"
                        >

                    </div>



                    {{-- STATUS --}}

                    <div class="xl:col-span-12">

                        <label class="event-label">

                            <i class="fas fa-toggle-on text-yellow-300"></i>

                            Event Status

                        </label>


                        <select
                            name="status"
                            class="event-input"
                            required
                        >

                            <option
                                value="active"

                                {{ old(
                                    'status',
                                    $event->status
                                ) === 'active'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Active
                            </option>


                            <option
                                value="published"

                                {{ old(
                                    'status',
                                    $event->status
                                ) === 'published'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Published
                            </option>


                            <option
                                value="approved"

                                {{ old(
                                    'status',
                                    $event->status
                                ) === 'approved'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Approved
                            </option>


                            <option
                                value="draft"

                                {{ old(
                                    'status',
                                    $event->status
                                ) === 'draft'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Draft
                            </option>


                            <option
                                value="pending"

                                {{ old(
                                    'status',
                                    $event->status
                                ) === 'pending'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                Pending
                            </option>

                        </select>

                    </div>

                </div>



                {{-- ACTIONS --}}

                <div
                    class="mt-8
                           flex
                           flex-col
                           gap-3
                           sm:flex-row
                           sm:items-center
                           sm:justify-end"
                >

                    <a
                        href="{{ route('events.index') }}"

                        class="inline-flex
                               items-center
                               justify-center
                               rounded-2xl
                               border border-white/10
                               bg-white/10
                               px-6 py-3
                               text-sm
                               font-black
                               text-white
                               transition
                               hover:bg-white/15"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"

                        class="inline-flex
                               items-center
                               justify-center
                               gap-2
                               rounded-2xl
                               bg-gradient-to-r
                               from-cyan-500
                               via-purple-600
                               to-pink-500
                               px-8 py-3
                               text-sm
                               font-black
                               text-white
                               shadow-xl
                               shadow-purple-500/25
                               transition
                               hover:scale-105"
                    >

                        <i class="fas fa-floppy-disk"></i>

                        Update Event

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
