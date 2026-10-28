<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $publicStatuses = [
            'active',
            'published',
            'approved',
        ];

        $events = Event::with([
            'creator',

            'participants' => function ($query) {
                $query->where(
                    'status',
                    'approved'
                );
            },
        ])
            ->whereIn(
                'status',
                $publicStatuses
            )
            ->when(\Illuminate\Support\Facades\Schema::hasColumn('events', 'archived_at'), fn ($q) => $q->whereNull('archived_at'))
            ->latest('event_date')
            ->orderByDesc('id')
            ->paginate(6)->withQueryString();


        $registeredEvents =
            EventParticipant::where(
                'user_id',
                auth()->id()
            )
                ->pluck(
                    'status',
                    'event_id'
                );


        $stats = [
            'total_events' =>
                Event::whereIn(
                    'status',
                    $publicStatuses
                )->count(),

            'upcoming_events' =>
                Event::whereIn(
                    'status',
                    $publicStatuses
                )
                    ->where(
                        'event_date',
                        '>=',
                        now()
                    )
                    ->count(),

            'my_registrations' =>
                EventParticipant::where(
                    'user_id',
                    auth()->id()
                )->count(),

            'approved_participants' =>
                EventParticipant::where(
                    'status',
                    'approved'
                )->count(),

            'pending_requests' =>
                EventParticipant::where(
                    'status',
                    'pending'
                )->count(),
        ];


        return view(
            'events.index',
            compact(
                'events',
                'registeredEvents',
                'stats'
            )
        );
    }


    public function create()
    {
        if (!auth()->check()) {
            return redirect()
                ->route('login');
        }

        if (
            !in_array(
                auth()->user()->role,
                [
                    'admin',
                    'super_admin',
                    'alumni',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }

        return view('events.create');
    }


    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()
                ->route('login');
        }

        $user = auth()->user();

        $isManagement = in_array(
            $user->role,
            [
                'admin',
                'super_admin',
            ],
            true
        );

        if (
            !in_array(
                $user->role,
                [
                    'admin',
                    'super_admin',
                    'alumni',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }


        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'type' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'required',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'end_time' => [
                'required',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'status' => [
                'nullable',
                'in:active,published,approved,draft,pending',
            ],
        ]);


        $coverPath = null;

        if (
            $request->hasFile(
                'cover_image'
            )
        ) {
            $coverPath =
                $request
                    ->file('cover_image')
                    ->store(
                        'events/covers',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Super Admin is also Management
        |--------------------------------------------------------------------------
        */

        $status = $isManagement
            ? $request->input(
                'status',
                'active'
            )
            : 'pending';


        $eventDateTime =
            $request->start_date .
            ' ' .
            $request->start_time;


        $event = Event::create([
            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'type' =>
                $request->type,

            'location' =>
                $request->location,

            'event_date' =>
                $eventDateTime,

            'start_date' =>
                $request->start_date,

            'start_time' =>
                $request->start_time,

            'end_date' =>
                $request->end_date,

            'end_time' =>
                $request->end_time,

            'capacity' =>
                $request->capacity,

            'cover_image' =>
                $coverPath,

            'status' =>
                $status,

            'created_by' =>
                $user->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Alumni submitted event
        |--------------------------------------------------------------------------
        */

        if (!$isManagement) {

            NotificationService::management(
                'event_pending',
                'New Event Awaiting Approval',
                $user->name .
                    ' submitted the event "' .
                    $event->title .
                    '".',
                route('events.index'),
                'high',
                $event,
                $user
            );

        } else {

            NotificationService::management(
                'event_created',
                'New Event Created',
                $user->name .
                    ' created the event "' .
                    $event->title .
                    '".',
                route('events.index'),
                'medium',
                $event,
                $user
            );
        }


        return redirect()
            ->route('events.index')
            ->with(
                'success',
                $isManagement
                    ? 'Event created successfully.'
                    : 'Event submitted successfully. Waiting for admin approval.'
            );
    }


    public function participants(Event $event)
    {
        abort_unless(\App\Support\PostAccess::canManage(auth()->user(), 'event', $event), 403);
        $participants = $event->participants()->with('user')->latest()->paginate(25);
        return response()->view('events.participants', compact('event', 'participants'))->header('Cache-Control', 'private, no-store');
    }

    public function cancelRegisteredParticipant(Event $event, int $participant)
    {
        abort_unless(\App\Support\PostAccess::canManage(auth()->user(), 'event', $event), 403);
        \Illuminate\Support\Facades\DB::transaction(function () use ($event, $participant) {
            Event::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $registration = $event->participants()->whereKey($participant)->lockForUpdate()->firstOrFail();
            abort_unless(in_array($registration->status, ['pending', 'approved'], true), 409);
            NotificationService::personal($registration->user_id, 'event_participation_cancelled', 'Event Participation Cancelled',
                'Your participation in "'.$event->title.'" was cancelled by '.auth()->user()->name.'.',
                route('events.show', $event), 'medium', $event, auth()->user());
            $registration->delete();
        });
        return redirect()->route('events.participants', $event)->with('success', 'Participation cancelled and the user has been notified.');
    }

    public function cancelParticipation(Event $event)
    {
        $user = auth()->user();
        abort_unless($user && in_array($user->role, ['student', 'alumni'], true) && $user->is_active && !$user->is_blocked, 403);
        \Illuminate\Support\Facades\DB::transaction(function () use ($event, $user) {
            $participant = EventParticipant::where('event_id', $event->id)->where('user_id', $user->id)->lockForUpdate()->firstOrFail();
            abort_unless(in_array($participant->status, ['pending', 'approved'], true), 409, 'This registration cannot be cancelled.');
            $participant->delete();
        });
        return back()->with('success', 'Your event participation has been cancelled.');
    }

    public function register(Event $event)
    {
        abort_unless(\App\Services\PostLifecycleService::accepting($event), 403, 'This event is archived or expired.');

        if (!auth()->check()) {
            return redirect()
                ->route('login');
        }

        $user = auth()->user();
        abort_if((int) $event->created_by === (int) $user->id, 403, 'Owners cannot register for their own event.');

        if (
            !in_array(
                $user->role,
                [
                    'student',
                    'alumni',
                ],
                true
            )
        ) {
            return back()->withErrors([
                'event' =>
                    'Only students and alumni can register for events.',
            ]);
        }


        if (
            !in_array(
                $event->status,
                [
                    'active',
                    'published',
                    'approved',
                ],
                true
            )
        ) {
            return back()->withErrors([
                'event' =>
                    'This event is not open for registration.',
            ]);
        }


        abort_unless($user->is_active && !$user->is_blocked, 403);
        $participant = \Illuminate\Support\Facades\DB::transaction(function () use ($event, $user) {
            $lockedEvent = Event::whereKey($event->id)->lockForUpdate()->firstOrFail();
            abort_unless(\App\Services\PostLifecycleService::accepting($lockedEvent) && in_array($lockedEvent->status, ['active', 'published', 'approved'], true), 403);
            $existing = EventParticipant::where('event_id', $event->id)->where('user_id', $user->id)->first();
            if ($existing && $existing->status === 'approved') {
                throw \Illuminate\Validation\ValidationException::withMessages(['event'=>'You are already registered for this event.']);
            }
            $count = $lockedEvent->participants()->where('status', 'approved')->count();
            if ($lockedEvent->capacity && $count >= $lockedEvent->capacity) {
                throw \Illuminate\Validation\ValidationException::withMessages(['event'=>'This event is already full.']);
            }
            $participant = $existing ?? new EventParticipant(['event_id'=>$event->id, 'user_id'=>$user->id]);
            $participant->fill(['status'=>'approved'])->save();
            return $participant;
        });

        /*
        |--------------------------------------------------------------------------
        | User Confirmation
        |--------------------------------------------------------------------------
        */

        NotificationService::personal(
            $user,
            'event_registration_submitted',
            'Event Registration Confirmed',
            'Your registration for "' .
                $event->title .
                '" is confirmed.',
            route('events.index'),
            'medium',
            $participant,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'event_registration',
            'New Event Registration',
            $user->name .
                ' registered for "' .
                $event->title .
                '".',
            null,
            'medium',
            $participant,
            $user
        );


        return back()->with(
            'success',
            'Registration confirmed. You are registered for this event.'
        );
    }


    public function pendingParticipants()
    {
        if (
            !in_array(
                auth()->user()->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }


        $participants =
            EventParticipant::with([
                'event',
                'user',
            ])
                ->where(
                    'status',
                    'pending'
                )
                ->latest()
                ->paginate(10);


        return view(
            'admin.events.pending-participants',
            compact(
                'participants'
            )
        );
    }


    public function approveParticipant(
        EventParticipant $participant
    ) {
        $admin = auth()->user();

        if (
            !in_array(
                $admin->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }


        if (
            $participant->status !==
            'pending'
        ) {
            return back()->withErrors([
                'event' =>
                    'This registration request has already been processed.',
            ]);
        }


        $participant->loadMissing([
            'event',
            'user',
        ]);


        $event =
            $participant->event;


        $approvedCount =
            $event
                ->participants()
                ->where(
                    'status',
                    'approved'
                )
                ->count();


        if (
            $event->capacity &&
            $approvedCount >=
            $event->capacity
        ) {
            return back()->withErrors([
                'event' =>
                    'Event capacity is already full.',
            ]);
        }


        $participant->update([
            'status' =>
                'approved',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Notify Participant
        |--------------------------------------------------------------------------
        */

        if ($participant->user) {

            NotificationService::personal(
                $participant->user,
                'event_registration_approved',
                'Event Registration Approved',
                'Your registration for "' .
                    $event->title .
                    '" has been approved.',
                route('events.index'),
                'high',
                $participant,
                $admin
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Management Activity
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'event_participant_approved',
            'Event Participant Approved',
            $admin->name .
                ' approved ' .
                ($participant->user?->name ?? 'a participant') .
                ' for "' .
                $event->title .
                '".',
            null,
            'medium',
            $participant,
            $admin
        );


        return back()->with(
            'success',
            'Participant approved successfully.'
        );
    }


    public function rejectParticipant(
        EventParticipant $participant
    ) {
        $admin = auth()->user();

        if (
            !in_array(
                $admin->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }


        if (
            $participant->status !==
            'pending'
        ) {
            return back()->withErrors([
                'event' =>
                    'This registration request has already been processed.',
            ]);
        }


        $participant->loadMissing([
            'event',
            'user',
        ]);


        $participant->update([
            'status' =>
                'rejected',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Notify Participant
        |--------------------------------------------------------------------------
        */

        if ($participant->user) {

            NotificationService::personal(
                $participant->user,
                'event_registration_rejected',
                'Event Registration Rejected',
                'Your registration for "' .
                    $participant->event->title .
                    '" was not approved.',
                route('events.index'),
                'medium',
                $participant,
                $admin
            );
        }


        NotificationService::management(
            'event_participant_rejected',
            'Event Participant Rejected',
            $admin->name .
                ' rejected ' .
                ($participant->user?->name ?? 'a participant') .
                ' from "' .
                $participant->event->title .
                '".',
            null,
            'medium',
            $participant,
            $admin
        );


        return back()->with(
            'success',
            'Participant rejected successfully.'
        );
    }
        /*
    |--------------------------------------------------------------------------
    | Edit Event
    |--------------------------------------------------------------------------
    */

    public function edit(Event $event)
    {
        $user = auth()->user();

        if (
            !$user ||
            !in_array(
                $user->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }

        return view(
            'events.edit',
            compact('event')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Event
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Event $event
    ) {
        $user = auth()->user();

        if (
            !$user ||
            !in_array(
                $user->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            )
        ) {
            abort(
                403,
                'UNAUTHORIZED ACCESS.'
            );
        }


        $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'type' => [
                'required',
                'string',
                'max:100',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'start_time' => [
                'required',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'end_time' => [
                'required',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'status' => [
                'required',
                'in:active,published,approved,draft,pending',
            ],

        ]);


        $coverPath = $event->cover_image;


        if ($request->hasFile('cover_image')) {

            /*
            |--------------------------------------------------------------------------
            | Delete old public cover if it exists
            |--------------------------------------------------------------------------
            */

            if (
                $event->cover_image &&
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->exists($event->cover_image)
            ) {
                \Illuminate\Support\Facades\Storage::disk('public')
                    ->delete($event->cover_image);
            }


            $coverPath = $request
                ->file('cover_image')
                ->store(
                    'events/covers',
                    'public'
                );
        }


        $eventDateTime =
            $request->start_date .
            ' ' .
            $request->start_time;


        $event->update([

            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'type' =>
                $request->type,

            'location' =>
                $request->location,

            'event_date' =>
                $eventDateTime,

            'start_date' =>
                $request->start_date,

            'start_time' =>
                $request->start_time,

            'end_date' =>
                $request->end_date,

            'end_time' =>
                $request->end_time,

            'capacity' =>
                $request->capacity,

            'cover_image' =>
                $coverPath,

            'status' =>
                $request->status,

        ]);


        NotificationService::management(

            'event_updated',

            'Event Updated',

            $user->name .
                ' updated the event "' .
                $event->title .
                '".',

            route('events.index'),

            'medium',

            $event,

            $user

        );


        return redirect()
            ->route('events.index')
            ->with(
                'success',
                'Event updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Event
    |--------------------------------------------------------------------------
    */

    public function destroy(Event $event)
    {
        return app(PostManagementController::class)->destroy('event', $event->id);
    }
    /*
|--------------------------------------------------------------------------
| Show Event Details
|--------------------------------------------------------------------------
*/

public function show(Event $event)
{
    $user = auth()->user();

    abort_unless(in_array($event->status, ['active', 'published', 'approved'], true)
        || \App\Support\PostAccess::canManage($user, 'event', $event), 404);

    abort_unless(
        $user &&
        in_array(
            $user->role,
            [
                'student',
                'alumni',
                'admin',
                'super_admin',
            ],
            true
        ),
        403,
        'UNAUTHORIZED ACCESS.'
    );

    /*
    |--------------------------------------------------------------------------
    | Load Event Information
    |--------------------------------------------------------------------------
    */

    $event->load([
        'creator',
        'participants',
    ]);



    /*
    |--------------------------------------------------------------------------
    | User Registration
    |--------------------------------------------------------------------------
    */

    $myRegistration = null;

    if (
        in_array(
            $user->role,
            ['student', 'alumni'],
            true
        )
    ) {
        $myRegistration = EventParticipant::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Participant Information
    |--------------------------------------------------------------------------
    */

    $participantCount =
        $event->participants->count();

    $approvedCount =
        $event->participants
            ->where('status', 'approved')
            ->count();

    $pendingCount =
        $event->participants
            ->where('status', 'pending')
            ->count();


    $isFull =
        $event->capacity &&
        $approvedCount >= $event->capacity;


    $isOpen = \App\Services\PostLifecycleService::accepting($event) && in_array(
        $event->status,
        [
            'active',
            'published',
            'approved',
        ],
        true
    );


    $isAdminPanel = in_array(
        $user->role,
        [
            'admin',
            'super_admin',
        ],
        true
    );


    return view(
        'events.show',
        compact(
            'event',
            'myRegistration',
            'participantCount',
            'approvedCount',
            'pendingCount',
            'isFull',
            'isOpen',
            'isAdminPanel'
        )
    );
}
}
