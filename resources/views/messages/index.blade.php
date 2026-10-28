<x-app-layout>



    <x-slot name="header">



        <div class="

            rounded-3xl

            border

            border-white/10

            bg-gradient-to-r

            from-slate-950

            via-indigo-950

            to-purple-950

            px-6

            py-6

            shadow-xl

        ">



            <div class="

                flex

                flex-col

                gap-4

                sm:flex-row

                sm:items-center

                sm:justify-between

            ">



                <div>



                    <p class="

                        text-xs

                        font-black

                        uppercase

                        tracking-[0.25em]

                        text-cyan-300

                    ">

                        Message Center

                    </p>



                    <h2 class="

                        mt-1

                        text-2xl

                        font-black

                        text-white

                        md:text-3xl

                    ">

                        Messages

                    </h2>



                    <p class="

                        mt-1

                        text-sm

                        text-slate-400

                    ">

                        Chat with your approved mentorship connections.

                    </p>



                </div>



                <div class="

                    flex

                    h-12

                    w-12

                    items-center

                    justify-center

                    rounded-2xl

                    bg-cyan-500/15

                    text-cyan-300

                ">



                    <i class="

                        fas

                        fa-comments

                        text-xl

                    "></i>



                </div>



            </div>



        </div>



    </x-slot>





    <style>



        /*

        |--------------------------------------------------------------------------

        | Main Panels

        |--------------------------------------------------------------------------

        */



        .message-panel {

            border:

                1px solid rgba(255, 255, 255, .10);



            background:

                rgba(15, 23, 42, .55);



            backdrop-filter:

                blur(18px);



            box-shadow:

                0 18px 55px rgba(2, 6, 23, .18);

        }



        /*

        |--------------------------------------------------------------------------

        | User Item

        |--------------------------------------------------------------------------

        */



        .message-user {

            transition:

                transform .20s ease,

                *background* .20s ease,

                border-color .20s ease;

        }



        .message-user:hover {

            background:

                rgba(255, 255, 255, .07);



            transform:

                translateY(-1px);

        }



        /*

        |--------------------------------------------------------------------------

        | Scrollbar

        |--------------------------------------------------------------------------

        */



        .message-scroll::-webkit-scrollbar {

            width: 6px;

        }



        .message-scroll::-webkit-scrollbar-thumb {

            background:

                rgba(148, 163, 184, .35);



            border-radius:

                999px;

        }



        /*

        |--------------------------------------------------------------------------

        | Mentorship Connections User

        |--------------------------------------------------------------------------

        */



        .chat-user {

            display: none;

        }



        .chat-user.uc-visible-user {

            display: block;

        }



        /*

        |--------------------------------------------------------------------------

        | Mentorship Connections Footer

        |--------------------------------------------------------------------------

        */



        .new-chat-footer {

            background:

                linear-gradient(

                    180deg,

                    rgba(15, 23, 42, .20),

                    rgba(15, 23, 42, .55)

                );

        }



        /*

        |--------------------------------------------------------------------------

        | Search

        |--------------------------------------------------------------------------

        */



        .message-search-input {

            transition:

                border-color .20s ease,

                box-shadow .20s ease,

                *background* .20s ease;

        }



        .message-search-input:focus {

            box-shadow:

                0 0 0 3px rgba(6, 182, 212, .08);

        }



    </style>





    <div class="

        mx-auto

        uc-messages-view uc-inbox-view
        max-w-6xl

    ">



        <div class="

            grid

            grid-cols-1

            gap-5

            lg:grid-cols-12
            {{ $conversations->isEmpty() ? 'uc-inbox-single' : '' }}

        ">





            {{-- ========================================================= --}}

            {{-- RECENT CONVERSATIONS --}}

            {{-- ========================================================= --}}



            @if($conversations->isNotEmpty())
            <section class="

                message-panel

                overflow-hidden

                rounded-3xl

                lg:col-span-7

            ">



                {{-- Header --}}



                <div class="

                    border-b

                    border-white/10

                    px-5

                    py-4

                ">



                    <div class="

                        flex

                        items-center

                        justify-between

                    ">



                        <div>



                            <h3 class="

                                text-lg

                                font-black

                                text-slate-900

                                dark:text-white

                            ">

                                Recent Conversations

                            </h3>



                            <p class="

                                mt-1

                                text-xs

                                text-slate-500

                                dark:text-slate-400

                            ">

                                Approved mentorship conversations

                            </p>



                        </div>



                        <span class="

                            rounded-full

                            bg-cyan-500/10

                            px-3

                            py-1

                            text-xs

                            font-bold

                            text-cyan-500

                        ">



                            {{ $conversations->count() }}



                        </span>



                    </div>



                </div>





                {{-- Conversation List --}}



                <div class="

                    message-scroll

                    max-h-[650px]

                    overflow-y-auto

                ">



                   @foreach($conversations as $conversationUser)



                        @php



                            $lastMessage =

                                $conversationUser->last_message;



                            $isMine =

                                $lastMessage

                                    ? (

                                        (int) $lastMessage->sender_id ===

                                        (int) auth()->id()

                                    )

                                    : false;



                            $preview =

                                $lastMessage?->content;



                            if (

                                !$preview &&

                                $lastMessage?->attachment

                            ) {

                                $preview =

                                    'Attachment';

                            }



                        @endphp





                        <div

                            class="

                                message-user

                                block

                                border-b

                                border-white/5

                                px-5

                                py-4

                            "

                        >



                            <div class="

                                flex

                                items-center

                                gap-3

                            ">



                                {{-- Avatar --}}



                                @if(

                                    $conversationUser

                                        ->profile_image

                                )



                                    <img

                                        src="{{

                                            $conversationUser

                                                ->getProfileImageUrl()

                                        }}"

                                        alt="{{ $conversationUser->name }}"

                                        class="

                                            h-12

                                            w-12

                                            shrink-0

                                            rounded-2xl

                                            object-cover

                                        "

                                    >



                                @else



                                    <div class="

                                        flex

                                        h-12

                                        w-12

                                        shrink-0

                                        items-center

                                        justify-center

                                        rounded-2xl

                                        bg-gradient-to-br

                                        from-cyan-500

                                        to-purple-600

                                        font-black

                                        text-white

                                    ">



                                        {{

                                            strtoupper(

                                                substr(

                                                    $conversationUser->name,

                                                    0,

                                                    1

                                                )

                                            )

                                        }}



                                    </div>



                                @endif





                                <div class="

                                    min-w-0

                                    flex-1

                                ">



                                    {{-- Name + Time --}}



                                    <div class="

                                        flex

                                        items-center

                                        justify-between

                                        gap-3

                                    ">



                                        <h4 class="

                                            truncate

                                            font-black

                                            text-slate-900

                                            dark:text-white

                                        ">



                                            <a href="{{ route($conversationUser->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $conversationUser) }}" class="hover:underline">{{ $conversationUser->name }}</a>



                                        </h4>





                                        @if($lastMessage)



                                            <span class="

                                                shrink-0

                                                text-[11px]

                                                font-semibold

                                                text-slate-400

                                            ">



                                                {{

                                                    $lastMessage

                                                        ->created_at

                                                        ?->diffForHumans(

                                                            null,

                                                            true

                                                        )

                                                }}



                                            </span>



                                        @endif



                                    </div>





                                    {{-- Message Preview --}}



                                    <div class="

                                        mt-1

                                        flex

                                        items-center

                                        justify-between

                                        gap-3

                                    ">



                                        <p class="

                                            truncate

                                            text-sm



                                            {{

                                                $conversationUser

                                                    ->unread_count > 0



                                                    ? 'font-black text-slate-900 dark:text-white'



                                                    : 'text-slate-500 dark:text-slate-400'

                                            }}

                                        ">



                                            @if($isMine)

                                                You:

                                            @endif



                                            {{

                                                \Illuminate\Support\Str::limit(

                                                    $preview

                                                        ?? 'Start a conversation',

                                                    55

                                                )

                                            }}



                                        </p>





                                        @if(

                                            $conversationUser

                                                ->unread_count > 0

                                        )



                                            <span class="

                                                flex

                                                h-6

                                                min-w-6

                                                shrink-0

                                                items-center

                                                justify-center

                                                rounded-full

                                                bg-cyan-500

                                                px-2

                                                text-[11px]

                                                font-black

                                                text-white

                                            ">



                                                {{

                                                    $conversationUser

                                                        ->unread_count > 99



                                                        ? '99+'



                                                        : $conversationUser

                                                            ->unread_count

                                                }}



                                            </span>



                                        @endif



                                    </div>





                                    {{-- Role --}}



                                    <p class="

                                        mt-1

                                        text-[11px]

                                        font-bold

                                        uppercase

                                        tracking-wide

                                        text-slate-400

                                    ">



                                        {{

                                            ucfirst(

                                                $conversationUser->role

                                            )

                                        }}



                                        @if(

                                            $conversationUser

                                                ->department

                                        )



                                            <span

                                                class="

                                                    normal-case

                                                    tracking-normal

                                                "

                                            >

                                                •

                                                {{

                                                    $conversationUser

                                                        ->department

                                                }}

                                            </span>



                                        @endif



                                    </p>



                                </div>



                            </div>



                        <a href="{{ route('messages.show', $conversationUser) }}" class="mt-2 inline-block rounded-lg bg-indigo-600 px-3 py-2 text-sm font-bold text-white">Open Chat</a></div>





                    @endforeach



                </div>



            </section>
            @endif





            {{-- ========================================================= --}}

            {{-- NEW CHAT --}}

            {{-- ========================================================= --}}



            <section class="

                message-panel

                overflow-hidden

                rounded-3xl

                lg:col-span-5

            ">



                {{-- Header --}}



                <div class="

                    border-b

                    border-white/10

                    px-5

                    py-4

                ">



                    <div class="

                        flex

                        items-start

                        justify-between

                        gap-4

                    ">



                        <div>



                            <h3 class="

                                text-lg

                                font-black

                                text-slate-900

                                dark:text-white

                            ">

                                Mentorship Connections

                            </h3>



                            <p class="

                                mt-1

                                text-xs

                                text-slate-500

                                dark:text-slate-400

                            ">



                                @if(

                                    auth()->user()->role ===

                                    'student'

                                )



                                    Alumni suggestions first



                                @else



                                    Student suggestions first



                                @endif



                            </p>



                        </div>



                        <div class="

                            flex

                            h-10

                            w-10

                            shrink-0

                            items-center

                            justify-center

                            rounded-xl

                            bg-indigo-500/10

                            text-indigo-400

                        ">



                            <i class="

                                fas

                                fa-user-plus

                                text-sm

                            "></i>



                        </div>



                    </div>



                </div>





                {{-- Search --}}



                <div class="

                    border-b

                    border-white/10

                    p-4

                ">



                    <div class="relative">



                        <i class="

                            fas

                            fa-search

                            absolute

                            left-4

                            top-1/2

                            -translate-y-1/2

                            text-slate-400

                        "></i>



                        <input

                            type="text"

                            id="userSearch"

                            autocomplete="off"

                            placeholder="Search name, email or department..."

                            class="

                                message-search-input

                                w-full

                                rounded-2xl

                                border

                                border-white/10

                                bg-slate-950/40

                                py-3

                                pl-11

                                pr-10

                                text-sm

                                text-slate-900

                                outline-none

                                focus:border-cyan-500

                                dark:text-white

                            "

                        >



                        <button

                            type="button"

                            id="clearUserSearch"

                            title="Clear search"

                            class="

                                hidden

                                absolute

                                right-3

                                top-1/2

                                -translate-y-1/2

                                h-7

                                w-7

                                items-center

                                justify-center

                                rounded-lg

                                bg-white/5

                                text-slate-400

                                transition

                                hover:bg-white/10

                                hover:text-white

                            "

                        >



                            <i class="

                                fas

                                fa-xmark

                                text-xs

                            "></i>



                        </button>



                    </div>





                    {{-- Small Information Row --}}



                    <div class="

                        mt-3

                        flex

                        items-center

                        justify-between

                        gap-3

                    ">



                        <p

                            id="newChatInfo"

                            class="

                                text-[11px]

                                font-semibold

                                text-slate-500

                                dark:text-slate-400

                            "

                        >

                            Showing approved mentors / mentees

                        </p>



                        <span class="

                            rounded-full

                            bg-white/5

                            px-2.5

                            py-1

                            text-[10px]

                            font-bold

                            text-slate-400

                        ">



                            {{ $users->count() }}

                            available



                        </span>



                    </div>



                </div>





                {{-- ===================================================== --}}

                {{-- USER LIST --}}

                {{-- ===================================================== --}}



                <div

                    id="userList"

                    class="

                        message-scroll

                        overflow-y-auto

                    "

                >



                    @forelse($users as $chatUser)



                        <div



                            data-user-name="{{

                                strtolower(

                                    $chatUser->name

                                )

                            }}"



                            data-user-email="{{

                                strtolower(

                                    $chatUser->email

                                )

                            }}"



                            data-user-department="{{

                                strtolower(

                                    $chatUser->department ?? ''

                                )

                            }}"



                            data-user-role="{{

                                strtolower(

                                    $chatUser->role

                                )

                            }}"



                            class="

                                message-user

                                chat-user

                                border-b

                                border-white/5

                                px-5

                                py-3

                            "

                        >



                            <div class="

                                flex

                                items-center

                                gap-3

                            ">



                                {{-- Avatar --}}<a class="shrink-0" aria-label="View profile" href="{{ route($chatUser->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $chatUser) }}">



                                @if(

                                    $chatUser

                                        ->profile_image

                                )



                                    <img

                                        src="{{

                                            $chatUser

                                                ->getProfileImageUrl()

                                        }}"

                                        alt="{{

                                            $chatUser->name

                                        }}"

                                        class="

                                            h-10

                                            w-10

                                            shrink-0

                                            rounded-xl

                                            object-cover

                                        "

                                    >



                                @else



                                    <div class="

                                        flex

                                        h-10

                                        w-10

                                        shrink-0

                                        items-center

                                        justify-center

                                        rounded-xl

                                        bg-gradient-to-br

                                        from-indigo-500

                                        to-purple-600

                                        text-sm

                                        font-black

                                        text-white

                                    ">



                                        {{

                                            strtoupper(

                                                substr(

                                                    $chatUser->name,

                                                    0,

                                                    1

                                                )

                                            )

                                        }}



                                    </div>



                                @endif





                                </a>{{-- Information --}}



                                <div class="

                                    min-w-0

                                    flex-1

                                ">



                                    <p class="

                                        truncate

                                        text-sm

                                        font-black

                                        text-slate-900

                                        dark:text-white

                                    ">



                                        <a href="{{ route($chatUser->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $chatUser) }}" class="hover:underline" @click.stop>{{ $chatUser->name }}</a>



                                    </p>



                                    <p class="

                                        mt-0.5

                                        truncate

                                        text-xs

                                        text-slate-500

                                        dark:text-slate-400

                                    ">



                                        {{

                                            ucfirst(

                                                $chatUser->role

                                            )

                                        }}



                                        @if(

                                            $chatUser

                                                ->department

                                        )



                                            •

                                            {{

                                                $chatUser

                                                    ->department

                                            }}



                                        @endif



                                    </p>



                                </div>





                                {{-- Arrow --}}



                                <div class="

                                    flex

                                    h-8

                                    w-8

                                    shrink-0

                                    items-center

                                    justify-center

                                    rounded-lg

                                    bg-white/5

                                    text-slate-400

                                    transition

                                ">



                                    <i class="

                                        fas

                                        fa-chevron-right

                                        text-[10px]

                                    "></i>



                                </div>



                            </div>



                        <a href="{{ route('messages.show', $chatUser) }}" class="mt-2 inline-block rounded-lg bg-indigo-600 px-3 py-2 text-sm font-bold text-white">Open Chat</a></div>





                    @empty





                        <div class="

                            p-8

                            text-center

                        ">



                            <div class="

                                mx-auto

                                flex

                                h-14

                                w-14

                                items-center

                                justify-center

                                rounded-2xl

                                bg-slate-500/10

                                text-slate-400

                            ">



                                <i class="

                                    fas

                                    fa-users-slash

                                    text-xl

                                "></i>



                            </div>



                            <p class="

                                mt-3

                                text-sm

                                font-bold

                                text-slate-500

                            ">

                                No approved mentorship connections yet.

                            </p>



                        </div>





                    @endforelse





                    {{-- Search Empty State --}}



                    <div

                        id="noSearchResults"

                        class="

                            hidden

                            px-6

                            py-10

                            text-center

                        "

                    >



                        <div class="

                            mx-auto

                            flex

                            h-14

                            w-14

                            items-center

                            justify-center

                            rounded-2xl

                            bg-cyan-500/10

                            text-cyan-500

                        ">



                            <i class="

                                fas

                                fa-user-slash

                                text-xl

                            "></i>



                        </div>



                        <h4 class="

                            mt-3

                            font-black

                            text-slate-900

                            dark:text-white

                        ">

                            No approved mentorship connection found

                        </h4>



                        <p class="

                            mt-1

                            text-xs

                            text-slate-500

                            dark:text-slate-400

                        ">

                            Try another name, email or department.

                        </p>



                    </div>



                </div>





                {{-- ===================================================== --}}

                {{-- SHOW MORE / LESS --}}

                {{-- ===================================================== --}}



                @if($users->count() > 5)



                    <div

                        id="newChatFooter"

                        class="

                            new-chat-footer

                            border-t

                            border-white/10

                            p-4

                        "

                    >



                        <button

                            type="button"

                            id="showMoreUsers"

                            class="

                                flex

                                w-full

                                items-center

                                justify-center

                                gap-2

                                rounded-xl

                                border

                                border-cyan-500/15

                                bg-cyan-500/10

                                px-4

                                py-2.5

                                text-xs

                                font-black

                                text-cyan-500

                                transition

                                hover:border-cyan-500/30

                                hover:bg-cyan-500/15

                                dark:text-cyan-300

                            "

                        >



                            <span id="showMoreText">

                                Show More

                            </span>



                            <i

                                id="showMoreIcon"

                                class="

                                    fas

                                    fa-chevron-down

                                    text-[9px]

                                "

                            ></i>



                        </button>



                    </div>



                @endif



            </section>



        </div>



    </div>





    {{-- ============================================================= --}}

    {{-- NEW CHAT JAVASCRIPT --}}

    {{-- ============================================================= --}}



    <script>



        document.addEventListener(

            'DOMContentLoaded',

            function () {



                /*

                |--------------------------------------------------------------------------

                | Elements

                |--------------------------------------------------------------------------

                */



                const search =

                    document.getElementById(

                        'userSearch'

                    );



                const clearSearch =

                    document.getElementById(

                        'clearUserSearch'

                    );



                const users =

                    Array.from(

                        document.querySelectorAll(

                            '.chat-user'

                        )

                    );



                const showMoreButton =

                    document.getElementById(

                        'showMoreUsers'

                    );



                const showMoreText =

                    document.getElementById(

                        'showMoreText'

                    );



                const showMoreIcon =

                    document.getElementById(

                        'showMoreIcon'

                    );



                const footer =

                    document.getElementById(

                        'newChatFooter'

                    );



                const noResults =

                    document.getElementById(

                        'noSearchResults'

                    );



                const info =

                    document.getElementById(

                        'newChatInfo'

                    );





                /*

                |--------------------------------------------------------------------------

                | Settings

                |--------------------------------------------------------------------------

                */



                const initialLimit = 5;



                const increment = 5;



                let visibleLimit =

                    initialLimit;





                /*

                |--------------------------------------------------------------------------

                | Utility

                |--------------------------------------------------------------------------

                */



                function getSearchValue() {



                    if (!search) {

                        return '';

                    }



                    return search.value

                        .toLowerCase()

                        .trim();

                }





                /*

                |--------------------------------------------------------------------------

                | Search Matching

                |--------------------------------------------------------------------------

                */



                function matchesSearch(

                    user,

                    value

                ) {



                    const name =

                        user.dataset.userName || '';



                    const email =

                        user.dataset.userEmail || '';



                    const department =

                        user.dataset.userDepartment || '';



                    const role =

                        user.dataset.userRole || '';



                    return (

                        name.includes(value) ||

                        email.includes(value) ||

                        department.includes(value) ||

                        role.includes(value)

                    );

                }





                /*

                |--------------------------------------------------------------------------

                | Render User List

                |--------------------------------------------------------------------------

                */



                function renderUsers() {



                    const searchValue =

                        getSearchValue();



                    const searching =

                        searchValue.length > 0;



                    let matchingUsers = [];



                    /*

                    |--------------------------------------------------------------------------

                    | Find matching users

                    |--------------------------------------------------------------------------

                    */



                    users.forEach(

                        function (user) {



                            user.classList.remove(

                                'uc-visible-user'

                            );



                            if (

                                !searching ||

                                matchesSearch(

                                    user,

                                    searchValue

                                )

                            ) {

                                matchingUsers.push(

                                    user

                                );

                            }



                        }

                    );





                    /*

                    |--------------------------------------------------------------------------

                    | Search Mode

                    |--------------------------------------------------------------------------

                    |

                    | Show every matching result.

                    |

                    */



                    if (searching) {



                        matchingUsers.forEach(

                            function (user) {



                                user.classList.add(

                                    'uc-visible-user'

                                );



                            }

                        );



                        /*

                        | Hide Show More while searching.

                        */



                        if (footer) {

                            footer.classList.add(

                                'hidden'

                            );

                        }



                        /*

                        | Clear button

                        */



                        if (clearSearch) {



                            clearSearch.classList.remove(

                                'hidden'

                            );



                            clearSearch.classList.add(

                                'flex'

                            );

                        }



                        /*

                        | Search information

                        */



                        if (info) {



                            info.textContent =

                                matchingUsers.length +

                                (

                                    matchingUsers.length === 1

                                        ? ' person found'

                                        : ' people found'

                                );

                        }



                    }



                    /*

                    |--------------------------------------------------------------------------

                    | Default Recommendation Mode

                    |--------------------------------------------------------------------------

                    */



                    else {



                        matchingUsers

                            .slice(

                                0,

                                visibleLimit

                            )

                            .forEach(

                                function (user) {



                                    user.classList.add(

                                        'uc-visible-user'

                                    );



                                }

                            );





                        /*

                        | Clear search button

                        */



                        if (clearSearch) {



                            clearSearch.classList.add(

                                'hidden'

                            );



                            clearSearch.classList.remove(

                                'flex'

                            );

                        }





                        /*

                        | Information

                        */



                        if (info) {



                            const currentlyVisible =

                                Math.min(

                                    visibleLimit,

                                    matchingUsers.length

                                );



                            info.textContent =

                                'Showing ' +

                                currentlyVisible +

                                ' recommended ' +

                                (

                                    currentlyVisible === 1

                                        ? 'person'

                                        : 'people'

                                );

                        }





                        /*

                        | Footer

                        */



                        if (footer) {



                            if (

                                matchingUsers.length >

                                initialLimit

                            ) {



                                footer.classList.remove(

                                    'hidden'

                                );



                            } else {



                                footer.classList.add(

                                    'hidden'

                                );

                            }

                        }





                        /*

                        |--------------------------------------------------------------------------

                        | Show More / Show Less Text

                        |--------------------------------------------------------------------------

                        */



                        if (

                            showMoreButton &&

                            showMoreText &&

                            showMoreIcon

                        ) {



                            if (

                                visibleLimit >=

                                matchingUsers.length

                            ) {



                                showMoreText.textContent =

                                    'Show Less';



                                showMoreIcon.className =

                                    'fas fa-chevron-up text-[9px]';



                            } else {



                                const remaining =

                                    matchingUsers.length -

                                    visibleLimit;



                                const nextAmount =

                                    Math.min(

                                        increment,

                                        remaining

                                    );



                                showMoreText.textContent =

                                    'Show ' +

                                    nextAmount +

                                    ' More';



                                showMoreIcon.className =

                                    'fas fa-chevron-down text-[9px]';

                            }

                        }

                    }





                    /*

                    |--------------------------------------------------------------------------

                    | No Search Results

                    |--------------------------------------------------------------------------

                    */



                    if (noResults) {



                        if (

                            searching &&

                            matchingUsers.length === 0

                        ) {



                            noResults.classList.remove(

                                'hidden'

                            );



                        } else {



                            noResults.classList.add(

                                'hidden'

                            );

                        }

                    }



                }





                /*

                |--------------------------------------------------------------------------

                | Search Input

                |--------------------------------------------------------------------------

                */



                if (search) {



                    search.addEventListener(

                        'input',

                        function () {



                            /*

                            | Reset recommendations whenever

                            | search changes.

                            */



                            visibleLimit =

                                initialLimit;



                            renderUsers();

                        }

                    );

                }





                /*

                |--------------------------------------------------------------------------

                | Clear Search

                |--------------------------------------------------------------------------

                */



                if (

                    clearSearch &&

                    search

                ) {



                    clearSearch.addEventListener(

                        'click',

                        function () {



                            search.value = '';



                            visibleLimit =

                                initialLimit;



                            renderUsers();



                            search.focus();

                        }

                    );

                }





                /*

                |--------------------------------------------------------------------------

                | Show More / Show Less

                |--------------------------------------------------------------------------

                */



                if (showMoreButton) {



                    showMoreButton.addEventListener(

                        'click',

                        function () {



                            const searchValue =

                                getSearchValue();



                            /*

                            | Show More only works

                            | outside search mode.

                            */



                            if (searchValue !== '') {

                                return;

                            }



                            if (

                                visibleLimit >=

                                users.length

                            ) {



                                /*

                                | Collapse back to five.

                                */



                                visibleLimit =

                                    initialLimit;



                                /*

                                | Scroll gently back to

                                | the Mentorship Connections section.

                                */



                                const userList =

                                    document.getElementById(

                                        'userList'

                                    );



                                if (userList) {



                                    userList.scrollTo({

                                        top: 0,

                                        behavior: 'smooth'

                                    });

                                }



                            } else {



                                /*

                                | Reveal five more.

                                */



                                visibleLimit +=

                                    increment;

                            }



                            renderUsers();

                        }

                    );

                }





                /*

                |--------------------------------------------------------------------------

                | Initial Render

                |--------------------------------------------------------------------------

                */



                renderUsers();



            }

        );



    </script>



    @include('components.messaging-layout')
</x-app-layout>