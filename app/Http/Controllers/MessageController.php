<?php



namespace App\Http\Controllers;



use App\Services\EncryptedFileService;

use App\Models\Message;
use App\Models\Mentorship;

use App\Models\Notification;

use App\Models\User;

use App\Services\NotificationService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;



class MessageController extends Controller

{

    /**

     * Message inbox.

     */

    public function index()
    {
        $authUser = auth()->user();

        abort_unless(
            $authUser !== null && in_array($authUser->role, ['student', 'alumni'], true),
            403,
            'Messaging is available only for students and alumni.'
        );

        // Only currently accepted mentorship connections can appear in Messages.
        $approvedUserIds = $authUser->role === 'student'
            ? Mentorship::query()
                ->where('student_id', $authUser->id)
                ->whereIn('status', ['accepted', 'completed'])
                ->pluck('mentor_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
            : Mentorship::query()
                ->where('mentor_id', $authUser->id)
                ->whereIn('status', ['accepted', 'completed'])
                ->pluck('student_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

        // Keep old conversations readable; only accepted connections can send.
        $partnerIds = Message::query()
            ->where(function ($query) use ($authUser) {
                $query->where('sender_id', $authUser->id)
                    ->where('deleted_by_sender', false);
            })
            ->orWhere(function ($query) use ($authUser) {
                $query->where('recipient_id', $authUser->id)
                    ->where('deleted_by_receiver', false);
            })
            ->get()
            ->map(function ($message) use ($authUser) {
                return (int) $message->sender_id === (int) $authUser->id
                    ? (int) $message->recipient_id
                    : (int) $message->sender_id;
            })
            ->unique()
            ->values();

        $conversations = User::query()
            ->whereIn('id', $partnerIds)
            ->whereIn('role', $authUser->role === 'student' ? ['alumni'] : ['student', 'alumni'])
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->get()
            ->map(function ($user) use ($authUser) {
                $lastMessage = Message::query()
                    ->where(function ($query) use ($authUser, $user) {
                        $query->where('sender_id', $authUser->id)
                            ->where('recipient_id', $user->id)
                            ->where('deleted_by_sender', false);
                    })
                    ->orWhere(function ($query) use ($authUser, $user) {
                        $query->where('sender_id', $user->id)
                            ->where('recipient_id', $authUser->id)
                            ->where('deleted_by_receiver', false);
                    })
                    ->latest()
                    ->first();

                $unreadCount = Message::query()
                    ->where('sender_id', $user->id)
                    ->where('recipient_id', $authUser->id)
                    ->where('is_read', false)
                    ->where('deleted_by_receiver', false)
                    ->count();

                $user->last_message = $lastMessage;
                $user->unread_count = $unreadCount;

                return $user;
            })
            ->filter(fn ($user) => $user->last_message !== null)
            ->sortByDesc(fn ($user) => optional($user->last_message)->created_at)
            ->values();

        $approvedUserIds = $approvedUserIds->merge(Mentorship::where('student_id', $authUser->id)->whereIn('status', ['accepted', 'completed'])->pluck('mentor_id'))->unique();
        // New Chat/Search shows only accepted mentor/mentee connections.
        $users = User::query()
            ->where(function ($q) use ($approvedUserIds, $partnerIds) {
                $q->whereIn('id', $approvedUserIds)->orWhereIn('id', $partnerIds)->orWhere(fn ($open) => $open->where('role', 'alumni')->where('requires_message_request', false));
            })
            ->where('id', '!=', $authUser->id)
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->orderBy('name')
            ->get();

        $users = $users->filter(fn ($candidate) => $authUser->canMessage($candidate));

        $preferredRole = $authUser->role === 'student' ? 'alumni' : 'student';

        $users = $users
            ->sort(function ($a, $b) use ($partnerIds) {
                $aHasConversation = $partnerIds->contains((int) $a->id);
                $bHasConversation = $partnerIds->contains((int) $b->id);

                if ($aHasConversation !== $bHasConversation) {
                    return $aHasConversation <=> $bHasConversation;
                }

                return strcasecmp((string) $a->name, (string) $b->name);
            })
            ->values();

        return view('messages.index', compact('users', 'conversations', 'preferredRole'));
    }


    /**

     * Open conversation.

     */

    public function show(User $user)

    {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Access

        |--------------------------------------------------------------------------

        */



        abort_unless(

            in_array(

                $authUser->role,

                ['student', 'alumni'],

                true

            ),

            403

        );



        /*

        |--------------------------------------------------------------------------

        | Cannot Message Yourself

        |--------------------------------------------------------------------------

        */



        if (

            (int) $authUser->id ===

            (int) $user->id

        ) {

            abort(

                403,

                'You cannot message yourself.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Only Student / Alumni

        |--------------------------------------------------------------------------

        */



        if (

            !in_array(

                $user->role,

                ['student', 'alumni'],

                true

            )

        ) {

            abort(

                403,

                'Only students and alumni can be messaged.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Availability

        |--------------------------------------------------------------------------

        */



        if (

            !$user->is_active ||

            $user->is_blocked

        ) {

            abort(

                403,

                'This user is currently unavailable.'

            );

        }



        abort_if($authUser->role === 'student' && $user->role === 'student', 403, 'Student-to-student messaging is disabled.');
        $canMessage = $authUser->canMessage($user);
        $hasHistory = Message::where(function ($query) use ($authUser, $user) {
            $query->where('sender_id', $authUser->id)->where('recipient_id', $user->id)->where('deleted_by_sender', false);
        })->orWhere(function ($query) use ($authUser, $user) {
            $query->where('sender_id', $user->id)->where('recipient_id', $authUser->id)->where('deleted_by_receiver', false);
        })->exists();
        abort_unless($canMessage || $hasHistory, 403, 'An accepted mentorship is required to start a conversation.');

        /*

        |--------------------------------------------------------------------------

        | Mark Received Messages As Read

        |--------------------------------------------------------------------------

        */



        $messagePages = Message::query()

            ->where(function ($query) use (

                $authUser,

                $user

            ) {

                $query

                    ->where(

                        'sender_id',

                        $authUser->id

                    )

                    ->where(

                        'recipient_id',

                        $user->id

                    )

                    ->where(

                        'deleted_by_sender',

                        false

                    );

            })

            ->orWhere(function ($query) use (

                $authUser,

                $user

            ) {

                $query

                    ->where(

                        'sender_id',

                        $user->id

                    )

                    ->where(

                        'recipient_id',

                        $authUser->id

                    )

                    ->where(

                        'deleted_by_receiver',

                        false

                    );

            })

            ->orderByDesc('created_at')->orderByDesc('id')

            ->paginate(50, ['*'], 'messages_page')->withQueryString();
        $messages = $messagePages->getCollection()->reverse()->values();

        Message::query()->whereIn('id', $messages->pluck('id'))

            ->where(

                'sender_id',

                $user->id

            )

            ->where(

                'recipient_id',

                $authUser->id

            )

            ->where(

                'is_read',

                false

            )

            ->update([

                'is_read' => true,

                'read_at' => now(),

            ]);



        /*

        |--------------------------------------------------------------------------

        | Mark Related Notifications Read

        |--------------------------------------------------------------------------

        */



        $hasUnreadHistory = Message::where('sender_id',$user->id)->where('recipient_id',$authUser->id)->where('deleted_by_receiver',false)->where('is_read',false)->exists();
        Notification::query()->when($hasUnreadHistory, fn ($q) => $q->whereRaw('1 = 0'))

            ->where(

                'user_id',

                $authUser->id

            )

            ->where(

                'actor_id',

                $user->id

            )

            ->where(

                'type',

                'message'

            )

            ->where(

                'is_read',

                false

            )

            ->update([

                'is_read' => true,

                'read_at' => now(),

            ]);



        /*

        |--------------------------------------------------------------------------

        | Conversation Messages

        |--------------------------------------------------------------------------

        */







        /*

        |--------------------------------------------------------------------------

        | View

        |--------------------------------------------------------------------------

        */



        return view(

            'messages.show',

            compact(

                'user',

                'messages',
                'canMessage',
                'messagePages'

            )

        );

    }



    /**

     * Send message.

     */

    public function store(

        Request $request,

        User $user

    ) {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Access

        |--------------------------------------------------------------------------

        */



        abort_unless(

            in_array(

                $authUser->role,

                ['student', 'alumni'],

                true

            ),

            403

        );



        /*

        |--------------------------------------------------------------------------

        | Cannot Message Yourself

        |--------------------------------------------------------------------------

        */



        if (

            (int) $authUser->id ===

            (int) $user->id

        ) {

            abort(

                403,

                'You cannot message yourself.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Only Student / Alumni

        |--------------------------------------------------------------------------

        */



        if (

            !in_array(

                $user->role,

                ['student', 'alumni'],

                true

            )

        ) {

            abort(

                403,

                'Only students and alumni can be messaged.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | User Availability

        |--------------------------------------------------------------------------

        */



        if (

            !$user->is_active ||

            $user->is_blocked

        ) {

            return back()->withErrors([

                'message' =>

                    'This user is currently unavailable.',

            ]);

        }



        $this->ensureAcceptedMentorship($authUser, $user);

        /*

        |--------------------------------------------------------------------------

        | Validation

        |--------------------------------------------------------------------------

        */



        $validated = $request->validate([

            'body' => [

                'nullable',

                'string',

                'max:5000',

            ],



            'attachment' => [

                'nullable',

                'file',

                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip,rar,txt,mp3,mp4',

                'max:10240',

            ],

        ]);



        /*

        |--------------------------------------------------------------------------

        | Message Body

        |--------------------------------------------------------------------------

        */



        $body = trim(

            (string) (

                $validated['body'] ?? ''

            )

        );



        if (

            $body === '' &&

            !$request->hasFile('attachment')

        ) {

            return back()

                ->withInput()

                ->withErrors([

                    'message' =>

                        'Please write a message or attach a file.',

                ]);

        }



        /*

        |--------------------------------------------------------------------------

        | Attachment

        |--------------------------------------------------------------------------

        */



        $attachmentPath = null;

        $attachmentName = null;

        $attachmentType = null;



        if ($request->hasFile('attachment')) {



            $file =

                $request->file('attachment');



            $attachmentPath =

          app(EncryptedFileService::class)

         ->store(

            $file,

            'message-attachments'

        );



            $attachmentName =

                $file->getClientOriginalName();



            $attachmentType =

                $file->getClientMimeType();

        }



        /*

        |--------------------------------------------------------------------------

        | Save Message

        |--------------------------------------------------------------------------

        */



        $message = DB::transaction(

            function () use (

                $authUser,

                $user,

                $body,

                $attachmentPath,

                $attachmentName,

                $attachmentType

            ) {

                // Keep existing mentorship state stable while checking conversation access.
                $studentId = $authUser->role === 'student' ? $authUser->id : $user->id;
                $mentorId = $authUser->role === 'alumni' ? $authUser->id : $user->id;
                Mentorship::where('student_id', $studentId)->where('mentor_id', $mentorId)->lockForUpdate()->get();
                $this->ensureAcceptedMentorship($authUser, $user);



                return Message::create([

                    'sender_id' =>

                        $authUser->id,



                    'recipient_id' =>

                        $user->id,



                   'content' => $body,



                    'attachment' =>

                        $attachmentPath,



                    'attachment_name' =>

                        $attachmentName,



                    'attachment_type' =>

                        $attachmentType,



                    'is_read' =>

                        false,



                    'read_at' =>

                        null,



                    'is_edited' =>

                        false,



                    'deleted_by_sender' =>

                        false,



                    'deleted_by_receiver' =>

                        false,

                ]);

            }

        );



        /*

        |--------------------------------------------------------------------------

        | Notification -> Receiver

        |--------------------------------------------------------------------------

        */



        $preview =

            $body !== ''

                ? str($body)->limit(80)

                : 'Sent you an attachment.';



        NotificationService::send(

            $user,

            'message',

            'New Message',

            $authUser->name .

                ': ' .

                $preview,

            route(

                'messages.show',

                $authUser

            ),

            'medium',

            $message,

            $authUser

        );



        return back();

    }



    /**

     * Edit own message.

     */

    public function update(

        Request $request,

        Message $message

    ) {

        $authUser = auth()->user();
        $this->ensureAcceptedMentorship($authUser, User::findOrFail($message->recipient_id));



        /*

        |--------------------------------------------------------------------------

        | Ownership

        |--------------------------------------------------------------------------

        */



        if (

            (int) $message->sender_id !==

            (int) $authUser->id

        ) {

            abort(

                403,

                'You can only edit your own messages.'

            );

        }



        if ($message->deleted_by_sender) {

            abort(

                404,

                'Message not found.'

            );

        }



        /*

        |--------------------------------------------------------------------------

        | Validation

        |--------------------------------------------------------------------------

        */



        $validated = $request->validate([

            'body' => [

                'required',

                'string',

                'max:5000',

            ],

        ]);



        /*

        |--------------------------------------------------------------------------

        | Update

        |--------------------------------------------------------------------------

        */



        $message->update([

            'content' =>

                trim(

                    $validated['body']

                ),



            'is_edited' =>

                true,

        ]);



        return back()->with(

            'success',

            'Message updated.'

        );

    }



    /**

     * Delete message for current user.

     */

    public function destroy(

        Message $message

    ) {

        $authUser = auth()->user();



        /*

        |--------------------------------------------------------------------------

        | Soft Delete For Current User

        |--------------------------------------------------------------------------

        */



        if (

            (int) $message->sender_id ===

            (int) $authUser->id

        ) {



            $message->update([

                'deleted_by_sender' =>

                    true,

            ]);



        } elseif (

            (int) $message->recipient_id ===

            (int) $authUser->id

        ) {



            $message->update([

                'deleted_by_receiver' =>

                    true,

            ]);



        } else {



            abort(

                403,

                'Unauthorized action.'

            );

        }



        $message->refresh();



        /*

        |--------------------------------------------------------------------------

        | Permanently Delete When Both Sides Removed It

        |--------------------------------------------------------------------------

        */



        if (

            $message->deleted_by_sender &&

            $message->deleted_by_receiver

        ) {



            if (

                $message->attachment &&

                Storage::disk('public')->exists(

                    $message->attachment

                )

            ) {

                Storage::disk('public')->delete(

                    $message->attachment

                );

            }



            $message->delete();

        }



        return back()->with(

            'success',

            'Message deleted.'

        );

    }

    /**
     * Accepted/completed mentorships and existing conversations may continue messaging.
     */
    private function ensureAcceptedMentorship(User $authUser, User $otherUser): void
    {
        abort_unless(
            $authUser->canMessage($otherUser),
            403,
            'Messaging is available only between approved mentorship connections.'
        );
    }

}
