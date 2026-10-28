<x-app-layout>

    <style>

        /* ==============================================================
         | CHAT CONTAINER
         ============================================================== */

        .chat-container {
            height: calc(100vh - 155px);
            min-height: 0;
            max-height: none;
        }

        .chat-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, .32);
            border-radius: 999px;
        }

        /* ==============================================================
         | MESSAGE COMPOSER
         ============================================================== */

        .chat-textarea {
            min-height: 42px;
            max-height: 100px;
            resize: none;
            overflow-y: auto;
        }

        /* ==============================================================
         | COMPACT MESSAGE BUBBLE
         ============================================================== */

        .chat-bubble {
            width: fit-content;
            max-width: min(64%, 460px);
        }

        .chat-message-box {
            width: fit-content;
            min-width: 0;
            max-width: 100%;
        }

        /*
         | Messages containing media/files can use a little more room.
         */

        .chat-message-box.has-attachment {
            min-width: 220px;
        }

        /*
         | Message text
         */

        .chat-message-text {
            margin: 0;
            font-size: 14px;
            line-height: 1.4;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /*
         | Compact metadata:
         | time / edited / read tick
         */

        .chat-message-meta {
            margin-top: 4px;
            font-size: 9px;
            line-height: 1;
            white-space: nowrap;
        }

        /*
         | Attachment media
         */

        .chat-attachment-image,
        .chat-attachment-video {
            width: auto;
            max-width: 100%;
            max-height: 260px;
        }

        /* ==============================================================
         | MOBILE
         ============================================================== */

        @media (max-width: 768px) {

            .chat-container {
                height: calc(100vh - 110px);
                min-height: 0;
            }

            .chat-bubble {
                max-width: 84%;
            }

            .chat-message-box.has-attachment {
                min-width: 190px;
            }

        }

        @media (max-width: 480px) {

            .chat-bubble {
                max-width: 88%;
            }

            .chat-message-text {
                font-size: 13.5px;
            }

        }

    </style>


    <div class="uc-messages-view uc-chat-view mx-auto max-w-5xl">

        <div
            class="
                chat-container
                flex
                flex-col
                overflow-hidden
                rounded-3xl
                border
                border-white/10
                bg-slate-950/70
                shadow-2xl
                backdrop-blur-xl
            "
        >

            {{-- ========================================================= --}}
            {{-- CHAT HEADER --}}
            {{-- ========================================================= --}}

            <div
                class="
                    flex
                    shrink-0
                    items-center
                    justify-between
                    gap-3
                    border-b
                    border-white/10
                    bg-slate-950/80
                    px-4
                    py-3
                    md:px-5
                "
            >

                <div class="flex min-w-0 items-center gap-3">

                    {{-- Back --}}

                    <a
                        href="{{ route('messages.index') }}"
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-white/10
                            text-white
                            transition
                            hover:bg-white/15
                        "
                    >
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>


                    {{-- Profile Image --}}

                    @if($user->profile_image)

                        <a class="shrink-0" aria-label="View profile" href="{{ route($user->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $user) }}"><img
                            src="{{ $user->getProfileImageUrl() }}"
                            alt="{{ $user->name }}"
                            class="
                                h-11
                                w-11
                                shrink-0
                                rounded-xl
                                object-cover
                            "
                        ></a>

                    @else

                        <div
                            class="
                                flex
                                h-11
                                w-11
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl
                                bg-gradient-to-br
                                from-cyan-500
                                to-purple-600
                                font-black
                                text-white
                            "
                        >
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                    @endif


                    {{-- User Information --}}

                    <div class="min-w-0">

                        <h1 class="truncate font-black text-white">
                            <a href="{{ route($user->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $user) }}" class="hover:underline">{{ $user->name }}</a>
                        </h1>

                        <p
                            class="
                                truncate
                                text-xs
                                font-semibold
                                text-slate-400
                            "
                        >

                            {{ ucfirst($user->role) }}

                            @if($user->department)
                                • {{ $user->department }}
                            @endif

                        </p>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- CALL BUTTONS --}}
                {{-- ===================================================== --}}

                <div class="flex shrink-0 items-center gap-2">

                    {{-- Audio Call --}}

                    <form
                        method="POST"
                        action="{{ route('calls.start', $user) }}"
                    >
                    <fieldset @disabled(!$canMessage)>

                        @csrf

                        <input
                            type="hidden"
                            name="type"
                            value="audio"
                        >

                        <button
                            type="submit"
                            title="Audio Call"
                            class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-xl
                                bg-emerald-500/10
                                text-emerald-400
                                transition
                                hover:bg-emerald-500/20
                            "
                        >
                            <i class="fas fa-phone text-sm"></i>
                        </button>

                    </fieldset>
                    </form>


                    {{-- Video Call --}}

                    <form
                        method="POST"
                        action="{{ route('calls.start', $user) }}"
                    >
                    <fieldset @disabled(!$canMessage)>

                        @csrf

                        <input
                            type="hidden"
                            name="type"
                            value="video"
                        >

                        <button
                            type="submit"
                            title="Video Call"
                            class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-xl
                                bg-cyan-500/10
                                text-cyan-400
                                transition
                                hover:bg-cyan-500/20
                            "
                        >
                            <i class="fas fa-video text-sm"></i>
                        </button>

                    </fieldset>
                    </form>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- FLASH MESSAGES --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div
                    class="
                        mx-4
                        mt-3
                        rounded-xl
                        border
                        border-emerald-500/20
                        bg-emerald-500/10
                        px-4
                        py-2
                        text-xs
                        font-bold
                        text-emerald-400
                    "
                >
                    {{ session('success') }}
                </div>

            @endif


            @if($errors->any())

                <div
                    class="
                        mx-4
                        mt-3
                        rounded-xl
                        border
                        border-red-500/20
                        bg-red-500/10
                        px-4
                        py-2
                        text-xs
                        font-bold
                        text-red-400
                    "
                >

                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- MESSAGES --}}
            {{-- ========================================================= --}}

            <div
                id="chatMessages"
                class="
                    chat-scroll
                    flex-1
                    space-y-2.5
                    overflow-y-auto
                    px-4
                    py-5
                    md:px-6
                "
            >

                @if($messagePages->hasPages())
                    <nav class="flex flex-wrap justify-center gap-3 py-2 text-xs" aria-label="Conversation history">
                        @if($messagePages->hasMorePages())<a class="rounded-lg bg-white/10 px-3 py-2 text-indigo-200" href="{{ $messagePages->nextPageUrl() }}">Older messages</a>@endif
                        @if($messagePages->currentPage() > 1)<a class="rounded-lg bg-white/10 px-3 py-2 text-indigo-200" href="{{ $messagePages->previousPageUrl() }}">Newer messages</a><a class="rounded-lg bg-indigo-600 px-3 py-2 text-white" href="{{ route('messages.show',$user) }}">Latest messages</a>@endif
                    </nav>
                @endif
                @forelse($messages as $message)

                    @php

                        $isMine =
                            (int) $message->sender_id ===
                            (int) auth()->id();

                        $mime =
                            $message->attachment_type ?? '';

                        $isImage =
                            str_starts_with(
                                $mime,
                                'image/'
                            );

                        $isVideo =
                            str_starts_with(
                                $mime,
                                'video/'
                            );

                        $isAudio =
                            str_starts_with(
                                $mime,
                                'audio/'
                            );

                    @endphp


                    {{-- ================================================= --}}
                    {{-- SINGLE MESSAGE --}}
                    {{-- ================================================= --}}

                    <div
                        class="
                            flex
                            {{ $isMine
                                ? 'justify-end'
                                : 'justify-start'
                            }}
                        "
                    >

                        <div class="chat-bubble group">

                            <div
                                class="
                                    flex
                                    items-end
                                    gap-1.5

                                    {{ $isMine
                                        ? 'flex-row-reverse'
                                        : ''
                                    }}
                                "
                            >

                                {{-- ===================================== --}}
                                {{-- MESSAGE BUBBLE --}}
                                {{-- ===================================== --}}

                                <div
                                    class="
                                        chat-message-box

                                        {{ $message->attachment
                                            ? 'has-attachment'
                                            : ''
                                        }}

                                        overflow-hidden

                                        rounded-2xl

                                        px-3
                                        py-2

                                        shadow-sm

                                        {{ $isMine
                                            ? 'rounded-br-md bg-gradient-to-br from-cyan-600 to-indigo-600 text-white'
                                            : 'rounded-bl-md border border-white/10 bg-slate-800 text-slate-100'
                                        }}
                                    "
                                >

                                    {{-- ================================= --}}
                                    {{-- TEXT --}}
                                    {{-- ================================= --}}

                                    @if($message->content)

                                        <p class="chat-message-text">
                                            {{ $message->content }}
                                        </p>

                                    @endif


                                    {{-- ================================= --}}
                                    {{-- ATTACHMENT --}}
                                    {{-- ================================= --}}

                                    @if($message->attachment)
                                    @php
                                       $attachmentUrl = route(
                                     'secure.messages.attachment',
                                        $message
                                         );
                                    @endphp
                                        <div
                                            class="
                                                {{ $message->content
                                                    ? 'mt-2'
                                                    : ''
                                                }}
                                            "
                                        >

                                            {{-- IMAGE --}}

                                            @if($isImage)

                                                <a
                                                    href="{{ $attachmentUrl }}"
                                                    target="_blank"
                                                >

                                                    <img
                                                        src="{{ $attachmentUrl }}"
                                                        alt="{{ $message->attachment_name ?? 'Image' }}"
                                                        class="
                                                            chat-attachment-image
                                                            rounded-xl
                                                            object-cover
                                                        "
                                                    >

                                                </a>


                                            {{-- VIDEO --}}

                                            @elseif($isVideo)

                                                <video
                                                    controls
                                                    class="
                                                        chat-attachment-video
                                                        rounded-xl
                                                    "
                                                >

                                                    <source
                                                        src="{{ $attachmentUrl }}"
                                                        type="{{ $message->attachment_type }}"
                                                    >

                                                </video>


                                            {{-- AUDIO --}}

                                            @elseif($isAudio)

                                                <audio
                                                    controls
                                                    class="
                                                        w-full
                                                        max-w-[300px]
                                                    "
                                                >

                                                    <source
                                                        src="{{ $attachmentUrl }}"
                                                        type="{{ $message->attachment_type }}"
                                                    >

                                                </audio>


                                            {{-- OTHER FILE --}}

                                            @else

                                                <a
                                                    href="{{ $attachmentUrl }}"
                                                    target="_blank"
                                                    class="
                                                        flex
                                                        max-w-[300px]
                                                        items-center
                                                        gap-2
                                                        rounded-xl
                                                        bg-black/15
                                                        px-3
                                                        py-2
                                                        text-xs
                                                        font-bold
                                                    "
                                                >

                                                    <i
                                                        class="
                                                            fas
                                                            fa-file-arrow-down
                                                            shrink-0
                                                        "
                                                    ></i>

                                                    <span class="truncate">
                                                        {{ $message->attachment_name ?? 'Download attachment' }}
                                                    </span>

                                                </a>

                                            @endif

                                        </div>

                                    @endif


                                    {{-- ================================= --}}
                                    {{-- TIME / EDITED / READ --}}
                                    {{-- ================================= --}}

                                    <div
                                        class="
                                            chat-message-meta
                                            flex
                                            items-center
                                            justify-end
                                            gap-1

                                            {{ $isMine
                                                ? 'text-white/65'
                                                : 'text-slate-400'
                                            }}
                                        "
                                    >

                                        <span>
                                            {{ $message->created_at?->format('h:i A') }}
                                        </span>


                                        @if($message->is_edited)

                                            <span>
                                                • edited
                                            </span>

                                        @endif


                                        @if($isMine)

                                            <span class="flex items-center gap-1">

                                                <span>•</span>

                                                @if($message->is_read)

                                                    <i
                                                        class="
                                                            fas
                                                            fa-check-double
                                                            text-cyan-200
                                                        "
                                                    ></i>

                                                @else

                                                    <i class="fas fa-check"></i>

                                                @endif

                                            </span>

                                        @endif

                                    </div>

                                </div>


                                {{-- ===================================== --}}
                                {{-- MESSAGE ACTIONS --}}
                                {{-- ===================================== --}}

                                @if($isMine)

                                    <div
                                        class="
                                            flex
                                            shrink-0
                                            items-center
                                            gap-1
                                            opacity-100
                                            transition


                                        "
                                    >

                                        {{-- Edit --}}
                                        @if($canMessage && $message->content)

                                            <button
                                                type="button"
                                                onclick="toggleEdit({{ $message->id }})"
                                                title="Edit message"
                                                class="
                                                    flex
                                                    h-7
                                                    w-7
                                                    items-center
                                                    justify-center
                                                    rounded-lg
                                                    bg-cyan-500/10
                                                    text-cyan-400
                                                    transition
                                                    hover:bg-cyan-500/20
                                                "
                                            >

                                                <i
                                                    class="
                                                        fas
                                                        fa-pen
                                                        text-[10px]
                                                    "
                                                ></i>

                                            </button>
                                        @endif


                                        {{-- Delete --}}

                                        <form
                                            method="POST"
                                            action="{{ route('messages.destroy', $message) }}"
                                            onsubmit="return confirm('Delete this message?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                title="Delete this message from your inbox" aria-label="Delete this message"
                                                class="
                                                    flex
                                                    min-h-7
                                                    px-2
                                                    items-center
                                                    justify-center
                                                    rounded-lg
                                                    bg-red-500/10
                                                    text-red-400
                                                    transition
                                                    hover:bg-red-500/20
                                                "
                                            >

                                                <i
                                                    class="
                                                        fas
                                                        fa-trash
                                                        text-[10px]
                                                    "
                                                ></i>

                                             <span class="ml-1 text-xs">Delete</span>
</button>

                                        </form>

                                    </div>

                                @endif

                            </div>


                            {{-- ========================================= --}}
                            {{-- EDIT FORM --}}
                            {{-- ========================================= --}}

                            @if($isMine && $message->content)

                                <form
                                    id="edit-box-{{ $message->id }}"
                                    method="POST"
                                    action="{{ route('messages.update', $message) }}"
                                    class="
                                        mt-2
                                        hidden
                                        rounded-xl
                                        border
                                        border-cyan-500/20
                                        bg-slate-900
                                        p-2
                                    "
                                >

                                    <fieldset @disabled(!$canMessage)>
                                    @csrf
                                    @method('PATCH')


                                    <textarea
                                        name="body"
                                        rows="2"
                                        required
                                        class="
                                            w-full
                                            resize-none
                                            rounded-lg
                                            border
                                            border-white/10
                                            bg-slate-950
                                            px-3
                                            py-2
                                            text-sm
                                            text-white
                                            outline-none
                                            focus:border-cyan-500
                                        "
                                    >{{ $message->content }}</textarea>


                                    <div
                                        class="
                                            mt-2
                                            flex
                                            justify-end
                                            gap-2
                                        "
                                    >

                                        <button
                                            type="button"
                                            onclick="toggleEdit({{ $message->id }})"
                                            class="
                                                rounded-lg
                                                bg-white/10
                                                px-3
                                                py-1.5
                                                text-xs
                                                font-bold
                                                text-slate-300
                                            "
                                        >
                                            Cancel
                                        </button>


                                        <button
                                            type="submit"
                                            class="
                                                rounded-lg
                                                bg-cyan-600
                                                px-3
                                                py-1.5
                                                text-xs
                                                font-bold
                                                text-white
                                            "
                                        >
                                            Save
                                        </button>

                                    </div>

                                </fieldset>
                    </form>

                            @endif

                        </div>

                    </div>


                @empty

                    {{-- ================================================= --}}
                    {{-- EMPTY CONVERSATION --}}
                    {{-- ================================================= --}}

                    <div
                        class="
                            flex
                            h-full
                            min-h-[300px]
                            flex-col
                            items-center
                            justify-center
                            text-center
                        "
                    >

                        <div
                            class="
                                flex
                                h-16
                                w-16
                                items-center
                                justify-center
                                rounded-2xl
                                bg-cyan-500/10
                                text-cyan-400
                            "
                        >

                            <i
                                class="
                                    fas
                                    fa-comment-dots
                                    text-2xl
                                "
                            ></i>

                        </div>

                        <h3 class="mt-4 font-black text-white">
                            No messages yet
                        </h3>

                        <p class="mt-1 text-sm text-slate-400">
                            Send a message to start the conversation.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- ========================================================= --}}
            {{-- SELECTED FILE PREVIEW --}}
            {{-- ========================================================= --}}

            <div
                id="selectedFileBox"
                class="
                    hidden
                    shrink-0
                    border-t
                    border-white/10
                    bg-slate-950/70
                    px-4
                    py-2
                "
            >

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-3
                    "
                >

                    <div
                        class="
                            flex
                            min-w-0
                            items-center
                            gap-2
                            text-xs
                            font-bold
                            text-cyan-300
                        "
                    >

                        <i class="fas fa-paperclip"></i>

                        <span
                            id="selectedFileName"
                            class="truncate"
                        ></span>

                    </div>


                    <button
                        type="button"
                        id="removeAttachment"
                        class="
                            text-xs
                            font-bold
                            text-red-400
                        "
                    >
                        Remove
                    </button>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- MESSAGE COMPOSER --}}
            {{-- ========================================================= --}}

            <div
                class="
                    shrink-0
                    border-t
                    border-white/10
                    bg-slate-950/90
                    p-3
                "
            >

                <p class="mb-3 text-sm text-amber-300" @if($canMessage) hidden @endif>Messaging is locked. An accepted mentorship is required to send or edit messages. Previous messages remain readable.</p>
                <form
                    method="POST"
                    action="{{ route('messages.store', $user) }}"
                    enctype="multipart/form-data"
                    id="messageForm"
                >
                    <fieldset @disabled(!$canMessage)>

                    @csrf


                    <div class="flex items-end gap-2">

                        {{-- ================================================= --}}
                        {{-- ATTACHMENT --}}
                        {{-- ================================================= --}}

                        <label
                            title="Attach file"
                            class="
                                flex
                                h-10
                                w-10
                                shrink-0
                                cursor-pointer
                                items-center
                                justify-center
                                rounded-xl
                                bg-white/10
                                text-slate-300
                                transition
                                hover:bg-cyan-500/15
                                hover:text-cyan-300
                            "
                        >

                            <i class="fas fa-paperclip text-sm"></i>

                            <input
                                type="file"
                                name="attachment"
                                id="messageAttachment"
                                class="hidden"
                                accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip,.rar,.txt,.mp3,.mp4"
                            >

                        </label>


                        {{-- ================================================= --}}
                        {{-- TEXT BOX --}}
                        {{-- ================================================= --}}

                        <div class="flex-1">

                            <textarea
                                name="body"
                                id="messageBody"
                                rows="1"
                                maxlength="5000"
                                placeholder="Type a message..."
                                class="
                                    chat-textarea
                                    block
                                    w-full
                                    rounded-2xl
                                    border
                                    border-white/10
                                    bg-slate-800/90
                                    px-4
                                    py-2.5
                                    text-sm
                                    leading-5
                                    text-white
                                    outline-none
                                    placeholder:text-slate-500
                                    focus:border-cyan-500
                                "
                            >{{ old('body') }}</textarea>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SEND --}}
                        {{-- ================================================= --}}

                        <button
                            type="submit"
                            title="Send"
                            class="
                                flex
                                h-10
                                w-10
                                shrink-0
                                items-center
                                justify-center
                                rounded-xl
                                bg-gradient-to-r
                                from-cyan-500
                                to-indigo-600
                                text-white
                                shadow-lg
                                transition
                                hover:scale-105
                            "
                        >

                            <i class="fas fa-paper-plane text-sm"></i>

                        </button>

                    </div>

                </fieldset>
                </form>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const chatBox =
                    document.getElementById(
                        'chatMessages'
                    );

                const textarea =
                    document.getElementById(
                        'messageBody'
                    );

                const fileInput =
                    document.getElementById(
                        'messageAttachment'
                    );

                const fileBox =
                    document.getElementById(
                        'selectedFileBox'
                    );

                const fileName =
                    document.getElementById(
                        'selectedFileName'
                    );

                const removeAttachment =
                    document.getElementById(
                        'removeAttachment'
                    );


                /*
                |--------------------------------------------------------------------------
                | SCROLL TO LATEST MESSAGE
                |--------------------------------------------------------------------------
                */

                if (chatBox) {

                    chatBox.scrollTop =
                        chatBox.scrollHeight;

                }


                /*
                |--------------------------------------------------------------------------
                | AUTO RESIZE TEXTAREA
                |--------------------------------------------------------------------------
                */

                if (textarea) {

                    const resizeTextarea =
                        function () {

                            textarea.style.height =
                                '42px';

                            textarea.style.height =
                                Math.min(
                                    textarea.scrollHeight,
                                    100
                                ) + 'px';

                        };


                    resizeTextarea();


                    textarea.addEventListener(
                        'input',
                        resizeTextarea
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ENTER = SEND
                    | SHIFT + ENTER = NEW LINE
                    |--------------------------------------------------------------------------
                    */

                    textarea.addEventListener(
                        'keydown',
                        function (event) {

                            if (
                                event.key === 'Enter' &&
                                !event.shiftKey
                            ) {

                                event.preventDefault();

                                const form =
                                    document.getElementById(
                                        'messageForm'
                                    );

                                if (form) {
                                    form.requestSubmit();
                                }

                            }

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | ATTACHMENT SELECTION
                |--------------------------------------------------------------------------
                */

                if (fileInput) {

                    fileInput.addEventListener(
                        'change',
                        function () {

                            if (
                                this.files &&
                                this.files.length > 0
                            ) {

                                if (fileName) {

                                    fileName.textContent =
                                        this.files[0].name;

                                }

                                if (fileBox) {

                                    fileBox.classList.remove(
                                        'hidden'
                                    );

                                }

                            } else {

                                if (fileBox) {

                                    fileBox.classList.add(
                                        'hidden'
                                    );

                                }

                            }

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | REMOVE ATTACHMENT
                |--------------------------------------------------------------------------
                */

                if (
                    removeAttachment &&
                    fileInput
                ) {

                    removeAttachment.addEventListener(
                        'click',
                        function () {

                            fileInput.value = '';

                            if (fileName) {
                                fileName.textContent = '';
                            }

                            if (fileBox) {

                                fileBox.classList.add(
                                    'hidden'
                                );

                            }

                        }
                    );

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | EDIT MESSAGE
        |--------------------------------------------------------------------------
        */

        function toggleEdit(messageId) {

            const editBox =
                document.getElementById(
                    'edit-box-' + messageId
                );

            if (editBox) {

                editBox.classList.toggle(
                    'hidden'
                );

            }

        }

    </script>

    @include('components.messaging-layout')
</x-app-layout>