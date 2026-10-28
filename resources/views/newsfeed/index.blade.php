<x-app-layout>



    <div class="uc-newsfeed-page min-h-screen">



        <style>
            .uc-main-area:has(.uc-newsfeed-page) { padding-top: 8px; }
            .uc-feed-interests { background: linear-gradient(115deg,#17223bd9,#221b3bd9); border-color: #a78bfa2e; }
            .uc-feed-interests summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; list-style: none; font-size: 13px; color: #e0e7ff; }
            .uc-feed-interests summary::-webkit-details-marker { display: none; }
            .uc-feed-interests summary::after { content: '+'; display: grid; place-items: center; width: 24px; height: 24px; border-radius: 8px; background: #8b5cf61c; color: #c4b5fd; font-size: 18px; }
            .uc-feed-interests[open] summary::after { content: '−'; }
            .uc-feed-interests summary:focus-visible { outline: 2px solid #a5b4fc; outline-offset: 5px; border-radius: 4px; }




            .uc-feed-shell {

                width: 100%;

                max-width: 720px;

                margin: 0 auto;

            }



            .uc-post {

                overflow: hidden;

                border-radius: 1.5rem;

                border: 1px solid rgba(255, 255, 255, .10);

                background: linear-gradient(

                    145deg,

                    rgba(30, 41, 59, .96),

                    rgba(30, 27, 75, .96)

                );

                box-shadow: 0 18px 55px rgba(0, 0, 0, .25);

                transition: .25s ease;

            }



            .uc-post:hover {

                border-color: rgba(168, 85, 247, .25);

            }



            .uc-avatar {

                width: 46px;

                height: 46px;

                border-radius: 9999px;

                display: flex;

                align-items: center;

                justify-content: center;

                flex-shrink: 0;

                color: white;

                box-shadow: 0 8px 24px rgba(0,0,0,.25);

            }



       .uc-post-image {

    display: block;

    width: 100%;

    height: 280px;

    object-fit: contain;

    object-position: center center;

    background: #0f172a;

}



            .uc-action-btn {

                width: 100%;

                display: flex;

                align-items: center;

                justify-content: center;

                gap: .5rem;

                padding: .7rem;

                border-radius: .8rem;

                font-weight: 800;

                font-size: .875rem;

                color: #cbd5e1;

                transition: .2s ease;

            }



            .uc-action-btn:hover {

                background: rgba(255, 255, 255, .07);

                color: white;

            }



            .uc-comment-box {

                background: rgba(255, 255, 255, .055);

                border: 1px solid rgba(255, 255, 255, .08);

            }



            .uc-meta-pill {

                border: 1px solid rgba(255, 255, 255, .08);

                background: rgba(255, 255, 255, .06);

            }



            .uc-filter-btn {

                display: inline-flex;

                align-items: center;

                justify-content: center;

                gap: .45rem;

                border-radius: 9999px;

                border: 1px solid rgba(255, 255, 255, .10);

                background: rgba(255, 255, 255, .055);

                padding: .58rem .95rem;

                color: #94a3b8;

                font-size: .78rem;

                font-weight: 900;

                transition: .2s ease;

            }



            .uc-filter-btn:hover {

                color: white;

                background: rgba(255, 255, 255, .09);

            }



            .uc-filter-btn.is-active {

                color: white;

                border-color: rgba(168, 85, 247, .35);

                background: linear-gradient(135deg, rgba(59,130,246,.85), rgba(168,85,247,.85));

                box-shadow: 0 8px 24px rgba(99, 102, 241, .18);

            }



            .uc-load-more {

                display: inline-flex;

                align-items: center;

                justify-content: center;

                gap: .55rem;

                border-radius: 1rem;

                border: 1px solid rgba(255, 255, 255, .10);

                background: rgba(255, 255, 255, .065);

                padding: .75rem 1.2rem;

                color: #e2e8f0;

                font-size: .84rem;

                font-weight: 900;

                transition: .2s ease;

            }



            .uc-load-more:hover {

                transform: translateY(-1px);

                background: rgba(255, 255, 255, .10);

                color: white;

            }



            .uc-toast {

                position: fixed;

                right: 1rem;

                bottom: 1rem;

                z-index: 10050;

                max-width: 340px;

                border: 1px solid rgba(255,255,255,.10);

                border-radius: 1rem;

                background: rgba(15,23,42,.96);

                padding: .8rem 1rem;

                color: #e2e8f0;

                font-size: .82rem;

                font-weight: 800;

                box-shadow: 0 18px 55px rgba(0,0,0,.35);

                backdrop-filter: blur(12px);

            }



            @media (max-width: 768px) {



    .uc-post {

        border-radius: 1rem;

    }



    .uc-feed-shell {

        max-width: 100%;

    }



    .uc-post-image {

        height: 230px;

        object-fit: contain;

        object-position: center center;

    }

}





            /* Compact newsfeed polish */

            .uc-feed-shell + .uc-feed-shell {

                scroll-margin-top: 1rem;

            }



            .uc-post img {

                max-height: 320px;

                object-fit: cover;

            }



            .uc-post .uc-action-btn {

                min-height: 38px;

            }



            @media (max-width: 640px) {

                .uc-feed-shell {

                    width: 100%;

                }



                .uc-post img {

                    max-height: 280px;

                }

            }



        </style>





        <div class="mx-auto max-w-7xl px-3 sm:px-4 lg:px-6 pt-2 pb-6">





            {{-- ========================================================= --}}

            {{-- PAGE HEADER --}}

            {{-- ========================================================= --}}



            <div class="uc-feed-shell mb-6">



                <div class="flex items-center justify-between gap-4">



                    <div>



                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">

                            Newsfeed

                        </h1>



                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                            Jobs, events and campus updates from University Connect.

                        </p>



                    </div>





                    <a

                        href="{{ route('newsfeed.index') }}"

                        class="h-11 w-11 shrink-0 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/15 transition"

                        title="Refresh Feed"

                    >



                        <i class="fas fa-rotate"></i>



                    </a>



                </div>



            </div>





            {{-- ========================================================= --}}

            {{-- SUCCESS MESSAGE --}}

            {{-- ========================================================= --}}



            @if(session('success'))



                <div class="uc-feed-shell mb-5">



                    <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-400">



                        <i class="fas fa-circle-check mr-2"></i>



                        {{ session('success') }}



                    </div>



                </div>



            @endif





            {{-- ========================================================= --}}

            {{-- ERRORS --}}

            {{-- ========================================================= --}}



            @if($errors->any())



                <div class="uc-feed-shell mb-5">



                    <div class="rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-3">



                        @foreach($errors->all() as $error)



                            <p class="text-sm font-bold text-red-400">



                                <i class="fas fa-circle-exclamation mr-2"></i>



                                {{ $error }}



                            </p>



                        @endforeach



                    </div>



                </div>



            @endif





            {{-- ========================================================= --}}

            {{-- FEED FILTERS --}}

            {{-- ========================================================= --}}



            <div class="uc-feed-shell mt-5 mb-7">

                @if(in_array(auth()->user()->role, ['student', 'alumni'], true))
                    <details class="uc-feed-interests mb-4 rounded-2xl border border-white/10 bg-slate-900 p-4 text-white" @if($errors->has('interests') || $errors->has('interests.*')) open @endif>
                        <summary class="cursor-pointer font-bold">{{ empty(auth()->user()->interests) ? 'Choose your interests' : 'Update Interests' }}</summary>
                        <p class="my-3 text-sm text-slate-400">Related posts appear first. You can still browse all posts and change your interests here.</p>
                        <form method="POST" action="{{ route('newsfeed.interests.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach(\App\Services\FeedInterestService::OPTIONS as $key => $label)
                                    <label class="flex items-center gap-3"><input type="checkbox" name="interests[]" value="{{ $key }}" @checked(in_array($key, (array) old('interests', auth()->user()->interests ?? []), true))> {{ $label }}</label>
                                @endforeach
                            </div>
                            <button type="submit" class="mt-4 rounded-xl bg-indigo-600 px-5 py-2 font-bold">Save Interests</button>
                        </form>
                    </details>
                @endif

                <div class="flex flex-wrap items-center gap-2.5">

                    <button type="button" class="uc-filter-btn is-active" data-feed-filter="all">

                        <i class="fas fa-layer-group"></i>

                        All

                    </button>



                    <button type="button" class="uc-filter-btn" data-feed-filter="job">

                        <i class="fas fa-briefcase"></i>

                        Jobs

                    </button>



                    <button type="button" class="uc-filter-btn" data-feed-filter="event">

                        <i class="fas fa-calendar-days"></i>

                        Events

                    </button>



                    <button type="button" class="uc-filter-btn" data-feed-filter="donation">

                        <i class="fas fa-hand-holding-heart"></i>

                        Donations

                    </button>

                </div>

            </div>



            {{-- ========================================================= --}}

            {{-- FEED --}}

            {{-- ========================================================= --}}



            <div id="uc-feed-list" class="uc-feed-shell space-y-5">





                @forelse($feedItems as $item)





                    <article

                            class="uc-post uc-feed-item"

                            data-feed-type="{{ $item['type'] }}"

                            data-feed-index="{{ $loop->index }}"

                        >





                        {{-- ================================================= --}}

                        {{-- POST HEADER --}}

                        {{-- ================================================= --}}



                        <div class="flex items-center justify-between gap-4 px-4 sm:px-5 py-4">





                            <div class="flex items-center gap-3 min-w-0">





                                @if(!empty($item['author_image']))






                                    <img






                                        src="{{ $item['author_image'] }}"






                                        alt="{{ $item['author_name'] ?? 'User' }}"






                                        class="uc-avatar object-cover bg-white"






                                    >






                                @else






                                    <div class="uc-avatar bg-gradient-to-br {{ $item['color'] }} font-black">






                                        {{ strtoupper(substr($item['author_name'] ?? 'U', 0, 1)) }}






                                    </div>






                                @endif







                                <div class="min-w-0">







                                    <div class="flex items-center gap-2 flex-wrap">






                                        <p class="font-black text-white">






                                            {{ $item['author_name'] ?? 'University Connect' }}






                                        </p>







                                        @if(!empty($item['author_role']))






                                            <span class="rounded-full border border-white/10 bg-white/[.06] px-2 py-0.5 text-[10px] font-black uppercase tracking-wide text-slate-300">






                                                {{ $item['author_role'] }}






                                            </span>






                                        @endif






                                    </div>







                                    <div class="mt-0.5 flex items-center gap-2 text-xs text-slate-400">



                                        <span>

                                            {{ optional($item['date'])->diffForHumans() ?? 'Recently' }}

                                        </span>



                                        <span>

                                            ·

                                        </span>



                                        <span>



                                            <i class="fas fa-earth-americas"></i>



                                        </span>



                                    </div>





                                </div>



                            </div>





                            <span class="shrink-0 rounded-full bg-white/[.07] border border-white/10 px-3 py-1.5 text-[10px] sm:text-xs font-black text-slate-300">



                                {{ $item['badge'] }}



                            </span>





                        </div>





                        {{-- ================================================= --}}

                        {{-- POST TEXT --}}

                        {{-- ================================================= --}}



                        <div class="px-4 sm:px-5 pb-4">





                            <h2 class="text-lg sm:text-xl font-black text-white leading-snug">



                                {{ $item['title'] }}



                            </h2>





                            @if(!empty($item['description']))



                                <p class="mt-2 text-sm leading-6 text-slate-300">



                                    {{ \Illuminate\Support\Str::limit(

                                        strip_tags($item['description']),

                                        350

                                    ) }}



                                </p>



                            @endif





                            {{-- META --}}



                            @if(!empty($item['meta']))



                                <div class="mt-4 flex flex-wrap gap-2">



                                    @foreach($item['meta'] as $meta)



                                        @if($meta)



                                            <span class="uc-meta-pill rounded-full px-3 py-1.5 text-xs font-bold text-slate-300">



                                                {{ $meta }}



                                            </span>



                                        @endif



                                    @endforeach



                                </div>



                            @endif





                        </div>





                        {{-- ================================================= --}}

                        {{-- IMAGE --}}

                        {{-- ================================================= --}}



                        @if(!empty($item['image']))



                            <a

                                href="{{ $item['url'] }}"

                                class="block bg-slate-950"

                            >



                                <img

                                    src="{{ $item['type'] === 'event' ? route('secure.events.cover', $item['feedable_id']) : asset('storage/' . $item['image']) }}"

                                    alt="{{ $item['title'] }}"

                                    class="uc-post-image"

                                >



                            </a>



                        @endif





                        {{-- ================================================= --}}

                        {{-- ENGAGEMENT COUNTS --}}

                        {{-- ================================================= --}}



                        <div class="flex items-center justify-between gap-4 px-4 sm:px-5 pt-4 pb-3 text-sm text-slate-400">





                            <div class="flex items-center gap-2">



                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-red-500 text-white flex items-center justify-center text-[10px]">



                                    <i class="fas fa-heart"></i>



                                </div>



                                <span

                                    id="like-count-{{ $item['type'] }}-{{ $item['feedable_id'] }}"

                                >

                                    {{ $item['likes_count'] ?? 0 }}

                                </span>



                            </div>





                            <div class="flex items-center gap-4">



                                <button

                                    type="button"

                                    onclick="document.getElementById('comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}').classList.toggle('hidden')"

                                    class="hover:text-white transition"

                                >



                                    {{ $item['comments_count'] ?? 0 }}

                                    comments



                                </button>





                                <span>

                                   <span

                                    id="share-count-{{ $item['type'] }}-{{ $item['feedable_id'] }}"

                                  >

                                     {{ $item['shares_count'] ?? 0 }}

                                   </span>



                                         shares

                                 </span>



                            </div>





                        </div>





                        {{-- ================================================= --}}

                        {{-- ACTION BUTTONS --}}

                        {{-- ================================================= --}}



                        <div class="mx-4 sm:mx-5 border-t border-b border-white/10">



                            <div class="grid grid-cols-3 gap-1 py-1">





                                {{-- LIKE --}}

                                <button

                                    type="button"

                                    id="like-btn-{{ $item['type'] }}-{{ $item['feedable_id'] }}"

                                    class="uc-action-btn {{ ($item['liked_by_me'] ?? false) ? '!text-pink-400' : '' }}"

                                    data-liked="{{ ($item['liked_by_me'] ?? false) ? '1' : '0' }}"

                                    onclick="toggleFeedLike(

                                        this,

                                        @js($item['feedable_type']),

                                        @js($item['feedable_id']),

                                        @js($item['type'])

                                    )"

                                >

                                    <i class="{{ ($item['liked_by_me'] ?? false) ? 'fas fa-heart text-pink-400' : 'far fa-heart' }}"></i>

                                    <span>Like</span>

                                </button>





                                {{-- COMMENT --}}



                                <button

                                    type="button"

                                    onclick="document.getElementById('comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}').classList.toggle('hidden')"

                                    class="uc-action-btn"

                                >



                                    <i class="far fa-comment"></i>



                                    <span>

                                        Comment

                                    </span>



                            </button>





                                {{-- SHARE --}}

                                 <button

                                    type="button"

                                    class="uc-action-btn"

                                   onclick="openShareModal(

                                   @js($item['feedable_type']),

                                   @js($item['feedable_id']),

                                   @js($item['title']),

                                   @js($item['url'])

                                     )"

                                   >

                                  <i class="fas fa-share"></i>



                                    <span>

                                   Share

                                </span>

                            </button>





                            </div>



                        </div>





                        {{-- ================================================= --}}

                        {{-- COMMENT FORM --}}

                        {{-- ================================================= --}}



                        <div

                            id="comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}"

                            class="hidden px-4 sm:px-5 pt-4"

                        >



                            <form

                                method="POST"

                                action="{{ route('newsfeed.comment') }}"

                                class="flex items-start gap-3"

                            >



                                @csrf





                                <input

                                    type="hidden"

                                    name="feedable_type"

                                    value="{{ $item['feedable_type'] }}"

                                >





                                <input

                                    type="hidden"

                                    name="feedable_id"

                                    value="{{ $item['feedable_id'] }}"

                                >





                                {{-- CURRENT USER AVATAR --}}



                                @if(auth()->user()->profile_image)



                                    <img

                                        src="{{ auth()->user()->getProfileImageUrl() }}"

                                        alt="{{ auth()->user()->name }}"

                                        class="h-9 w-9 shrink-0 rounded-full object-cover bg-white"

                                    >



                                @else



                                    <div class="h-9 w-9 shrink-0 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 text-white flex items-center justify-center text-xs font-black">



                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}



                                    </div>



                                @endif





                                <div class="flex-1">



                                    <div class="flex items-end gap-2 rounded-2xl bg-white/[.07] border border-white/10 px-4 py-2">



                                        <textarea

                                            name="comment"

                                            rows="1"

                                            required

                                            maxlength="1000"

                                            placeholder="Write a comment..."

                                            class="flex-1 resize-none bg-transparent border-0 p-0 text-sm text-white placeholder-slate-500 focus:ring-0"

                                        ></textarea>





                                        <button

                                            type="submit"

                                            class="h-8 w-8 shrink-0 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white flex items-center justify-center"

                                            title="Post Comment"

                                        >



                                            <i class="fas fa-paper-plane text-xs"></i>



                                        </button>



                                    </div>



                                </div>



                            </form>



                        </div>





                        {{-- ================================================= --}}

                        {{-- RECENT COMMENTS --}}

                        {{-- ================================================= --}}



                        @if(!empty($item['recent_comments']) && count($item['recent_comments']))





                            <div class="px-4 sm:px-5 pt-4 space-y-3">





                                @foreach($item['recent_comments'] as $comment)





                                    <div class="flex items-start gap-3">





                                        {{-- COMMENT USER AVATAR --}}



                                        @if($comment->user?->profile_image)



                                            <img

                                                src="{{ $comment->user->getProfileImageUrl() }}"

                                                alt="{{ $comment->user->name ?? 'User' }}"

                                                class="h-9 w-9 shrink-0 rounded-full object-cover bg-white"

                                            >



                                        @else



                                            <div class="h-9 w-9 shrink-0 rounded-full bg-gradient-to-br from-cyan-500 to-purple-600 text-white flex items-center justify-center text-xs font-black">



                                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}



                                            </div>



                                        @endif





                                        <div class="min-w-0">





                                            <div class="uc-comment-box rounded-2xl px-4 py-2.5">





                                                <p class="text-xs font-black text-white">



                                                    {{ $comment->user->name ?? 'User' }}



                                                </p>





                                                <p class="mt-1 text-sm leading-5 text-slate-300">



                                                    {{ $comment->comment }}



                                                </p>





                                            </div>





                                            <p class="mt-1 ml-2 text-[11px] text-slate-500">



                                                {{ optional($comment->created_at)->diffForHumans() }}



                                            </p>





                                        </div>





                                    </div>





                                @endforeach





                            </div>





                        @endif





                        {{-- ================================================= --}}

                        {{-- DETAILS --}}

                        {{-- ================================================= --}}



                        <div class="px-4 sm:px-5 py-4">





                            <a

                                href="{{ $item['url'] }}"

                                class="flex items-center justify-center gap-2 w-full rounded-xl bg-white/[.06] border border-white/10 py-2.5 text-sm font-black text-slate-300 hover:bg-white/[.10] hover:text-white transition"

                            >



                                View Details



                                <i class="fas fa-arrow-right text-xs"></i>



                            </a>







                        </div>





                    </article>





                @empty





                    {{-- ================================================= --}}

                    {{-- EMPTY --}}

                    {{-- ================================================= --}}



                    <div class="rounded-3xl border border-white/10 bg-white/[.05] p-12 text-center">





                        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl">



                            <i class="fas fa-newspaper"></i>



                        </div>





                        <h3 class="mt-5 text-xl font-black text-white">

                            No posts yet

                        </h3>





                        <p class="mt-2 text-sm text-slate-400">



                            Approved jobs, events and donation campaigns will appear here.



                        </p>





                    </div>





                @endforelse



                <div

                    id="uc-filter-empty"

                    class="hidden rounded-3xl border border-white/10 bg-white/[.05] p-10 text-center"

                >

                    <div class="mx-auto h-14 w-14 rounded-full bg-white/[.07] flex items-center justify-center text-slate-400">

                        <i class="fas fa-filter"></i>

                    </div>

                    <h3 class="mt-4 text-lg font-black text-white">No posts in this category</h3>

                    <p class="mt-1 text-sm text-slate-400">Try another feed filter.</p>

                </div>



                @if($feedPage > 1 || $feedHasMore)
                    <nav class="flex justify-center gap-3 py-3 text-xs" aria-label="Feed pages">
                        @if($feedPage > 1)<a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-slate-200" href="{{ route('newsfeed.index',['feed_page'=>$feedPage-1]) }}">Newer posts</a>@endif
                        @if($feedHasMore)<a class="rounded-xl border border-indigo-400/30 bg-indigo-500/10 px-4 py-2 text-indigo-200" href="{{ route('newsfeed.index',['feed_page'=>$feedPage+1]) }}">Older posts</a>@endif
                    </nav>
                @endif
                @if($feedItems->count() > 12)

                    <div id="uc-load-more-wrap" class="pt-1 text-center">

                        <button id="uc-load-more" type="button" class="uc-load-more">

                            <i class="fas fa-chevron-down"></i>

                            Load More

                        </button>

                    </div>

                @endif







            </div>





        </div>



    </div>

    {{-- ========================================================= --}}

{{-- SHARE MODAL --}}

{{-- ========================================================= --}}



<div

    id="uc-share-modal"

    class="hidden fixed inset-0 z-[9999] items-center justify-center px-4"

>

    {{-- BACKDROP --}}

    <div

        class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"

        onclick="closeShareModal()"

    ></div>





    {{-- MODAL --}}

    <div

        class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-slate-900 shadow-2xl"

    >



        {{-- HEADER --}}

        <div

            class="flex items-center justify-between border-b border-white/10 px-5 py-4"

        >



            <div>



                <h3

                    class="text-lg font-black text-white"

                >

                    Share Post

                </h3>



                <p

                    class="mt-1 text-xs text-slate-400"

                >

                    Choose how you want to share this post.

                </p>



            </div>





            <button

                type="button"

                onclick="closeShareModal()"

                class="flex h-9 w-9 items-center justify-center rounded-full bg-white/[.06] text-slate-400 transition hover:bg-white/[.10] hover:text-white"

            >

                <i class="fas fa-xmark"></i>

            </button>



        </div>





        {{-- POST --}}

        <div

            class="px-5 pt-5"

        >



            <div

                class="rounded-2xl border border-white/10 bg-white/[.04] px-4 py-3"

            >



                <p

                    class="text-[10px] font-black uppercase tracking-[.18em] text-purple-400"

                >

                    University Connect

                </p>



                <p

                    id="uc-share-title"

                    class="mt-1 line-clamp-2 text-sm font-bold text-white"

                >

                    Post

                </p>



            </div>



        </div>





        {{-- OPTIONS --}}

        <div

            class="grid grid-cols-3 gap-3 p-5"

        >



            {{-- COPY LINK --}}

            <button

                type="button"

                onclick="shareCopyLink()"

                class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/[.04] px-3 py-5 transition hover:border-blue-400/30 hover:bg-blue-500/10"

            >



                <span

                    class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500/15 text-lg text-blue-400"

                >

                    <i class="fas fa-link"></i>

                </span>



                <span

                    class="text-xs font-black text-slate-300 group-hover:text-white"

                >

                    Copy Link

                </span>



            </button>





            {{-- WHATSAPP --}}

            <button

                type="button"

                onclick="shareWhatsApp()"

                class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/[.04] px-3 py-5 transition hover:border-emerald-400/30 hover:bg-emerald-500/10"

            >



                <span

                    class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/15 text-xl text-emerald-400"

                >

                    <i class="fab fa-whatsapp"></i>

                </span>



                <span

                    class="text-xs font-black text-slate-300 group-hover:text-white"

                >

                    WhatsApp

                </span>



            </button>





            {{-- FACEBOOK --}}

            <button

                type="button"

                onclick="shareFacebook()"

                class="group flex flex-col items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/[.04] px-3 py-5 transition hover:border-blue-500/30 hover:bg-blue-600/10"

            >



                <span

                    class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600/15 text-xl text-blue-400"

                >

                    <i class="fab fa-facebook-f"></i>

                </span>



                <span

                    class="text-xs font-black text-slate-300 group-hover:text-white"

                >

                    Facebook

                </span>



            </button>



        </div>





        {{-- COPY STATUS --}}

        <div

            id="uc-share-status"

            class="hidden mx-5 mb-5 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-center text-xs font-bold text-emerald-400"

        >

            <i class="fas fa-circle-check mr-1"></i>



            Link copied successfully.

        </div>



    </div>



</div>





<script>

    const UC_FEED_PAGE_SIZE = 12;

    let ucFeedFilter = 'all';

    let ucFeedVisible = UC_FEED_PAGE_SIZE;



    function ucFeedItems() {

        return Array.from(document.querySelectorAll('.uc-feed-item'));

    }



    function renderFeedItems() {

        const items = ucFeedItems();

        const filtered = items.filter((item) => {

            return ucFeedFilter === 'all'

                || item.dataset.feedType === ucFeedFilter;

        });



        items.forEach((item) => item.classList.add('hidden'));



        filtered.slice(0, ucFeedVisible).forEach((item) => {

            item.classList.remove('hidden');

        });



        const empty = document.getElementById('uc-filter-empty');

        if (empty) {

            empty.classList.toggle('hidden', filtered.length !== 0);

        }



        const wrap = document.getElementById('uc-load-more-wrap');

        if (wrap) {

            wrap.classList.toggle('hidden', filtered.length <= ucFeedVisible);

        }

    }



    document.querySelectorAll('[data-feed-filter]').forEach((button) => {

        button.addEventListener('click', () => {

            ucFeedFilter = button.dataset.feedFilter;

            ucFeedVisible = UC_FEED_PAGE_SIZE;



            document.querySelectorAll('[data-feed-filter]').forEach((item) => {

                item.classList.remove('is-active');

            });



            button.classList.add('is-active');

            renderFeedItems();

        });

    });



    const ucLoadMoreButton = document.getElementById('uc-load-more');



    if (ucLoadMoreButton) {

        ucLoadMoreButton.addEventListener('click', () => {

            ucFeedVisible += UC_FEED_PAGE_SIZE;

            renderFeedItems();

        });

    }



    function showFeedToast(message) {

        const oldToast = document.getElementById('uc-feed-toast');

        if (oldToast) oldToast.remove();



        const toast = document.createElement('div');

        toast.id = 'uc-feed-toast';

        toast.className = 'uc-toast';

        toast.innerHTML = '<i class="fas fa-circle-check mr-2 text-emerald-400"></i>'

            + String(message || 'Updated successfully.');



        document.body.appendChild(toast);



        setTimeout(() => {

            toast.remove();

        }, 2200);

    }



    async function toggleFeedLike(button, feedableType, feedableId, displayType) {

        if (!button || button.dataset.busy === '1') {

            return;

        }



        button.dataset.busy = '1';

        button.disabled = true;

        button.classList.add('opacity-70');



        try {

            const response = await fetch(

                @js(route('newsfeed.like')),

                {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': @js(csrf_token())

                    },

                    body: JSON.stringify({

                        feedable_type: feedableType,

                        feedable_id: feedableId

                    })

                }

            );



            if (!response.ok) {

                throw new Error('Unable to update like.');

            }



            const data = await response.json();

            const icon = button.querySelector('i');

            const counter = document.getElementById(

                'like-count-' + displayType + '-' + feedableId

            );



            button.dataset.liked = data.liked ? '1' : '0';

            button.classList.toggle('!text-pink-400', !!data.liked);



            if (icon) {

                icon.className = data.liked

                    ? 'fas fa-heart text-pink-400'

                    : 'far fa-heart';

            }



            if (counter && typeof data.likes_count !== 'undefined') {

                counter.textContent = data.likes_count;

            }

        } catch (error) {

            console.error(error);

            showFeedToast('Like update failed. Please try again.');

        } finally {

            button.dataset.busy = '0';

            button.disabled = false;

            button.classList.remove('opacity-70');

        }

    }



    renderFeedItems();

</script>



<script>

    let ucShareData = {

        type: null,

        id: null,

        title: '',

        url: ''

    };





    /*

    |--------------------------------------------------------------------------

    | OPEN SHARE MODAL

    |--------------------------------------------------------------------------

    */

    function openShareModal(type, id, title, url) {



        ucShareData = {

            type: type,

            id: id,

            title: title,

            url: url

        };



        const modal = document.getElementById('uc-share-modal');



        const titleElement =

            document.getElementById('uc-share-title');



        const status =

            document.getElementById('uc-share-status');





        titleElement.textContent =

            title || 'University Connect Post';





        status.classList.add('hidden');





        modal.classList.remove('hidden');



        modal.classList.add('flex');





        document.body.style.overflow = 'hidden';

    }





    /*

    |--------------------------------------------------------------------------

    | CLOSE SHARE MODAL

    |--------------------------------------------------------------------------

    */

    function closeShareModal() {



        const modal =

            document.getElementById('uc-share-modal');





        modal.classList.add('hidden');



        modal.classList.remove('flex');





        document.body.style.overflow = '';

    }





    /*

    |--------------------------------------------------------------------------

    | RECORD SHARE

    |--------------------------------------------------------------------------

    */

    async function recordShare() {



        if (

            !ucShareData.type

            || !ucShareData.id

        ) {

            return;

        }





        try {



            const response = await fetch(

                @js(route('newsfeed.share')),

                {

                    method: 'POST',



                    headers: {

                        'Content-Type':

                            'application/json',



                        'Accept':

                            'application/json',



                        'X-CSRF-TOKEN':

                            @js(csrf_token())

                    },



                    body: JSON.stringify({

                        feedable_type:

                            ucShareData.type,



                        feedable_id:

                            ucShareData.id

                    })

                }

            );





            if (!response.ok) {

                return;

            }





            const data =

                await response.json();





            if (

                typeof data.shares_count

                !== 'undefined'

            ) {



                const counter =

                    document.getElementById(

                        'share-count-'

                        + getShareTypeSlug()

                        + '-'

                        + ucShareData.id

                    );





                if (counter) {

                    counter.textContent =

                        data.shares_count;

                }

            }



        } catch (error) {



            console.error(

                'Unable to record share.',

                error

            );



        }

    }





    /*

    |--------------------------------------------------------------------------

    | GET DISPLAY TYPE

    |--------------------------------------------------------------------------

    */

    function getShareTypeSlug() {



        const type =

            ucShareData.type || '';





        if (

            type.toLowerCase().includes('job')

        ) {

            return 'job';

        }





        if (

            type.toLowerCase().includes('event')

        ) {

            return 'event';

        }





        if (

            type.toLowerCase().includes('donation')

        ) {

            return 'donation';

        }





        return 'post';

    }





    /*

    |--------------------------------------------------------------------------

    | COPY LINK

    |--------------------------------------------------------------------------

    */

    async function shareCopyLink() {



        try {



            await navigator.clipboard.writeText(

                ucShareData.url

            );





            await recordShare();





            const status =

                document.getElementById(

                    'uc-share-status'

                );





            status.innerHTML =

                '<i class="fas fa-circle-check mr-1"></i> Link copied successfully.';





            status.classList.remove('hidden');





            setTimeout(() => {



                status.classList.add(

                    'hidden'

                );



            }, 2500);



        } catch (error) {



            /*

             * Clipboard fallback

             */

            const textarea =

                document.createElement(

                    'textarea'

                );





            textarea.value =

                ucShareData.url;





            textarea.style.position =

                'fixed';



            textarea.style.opacity =

                '0';





            document.body.appendChild(

                textarea

            );





            textarea.focus();



            textarea.select();





            document.execCommand(

                'copy'

            );





            textarea.remove();





            await recordShare();





            const status =

                document.getElementById(

                    'uc-share-status'

                );





            status.textContent =

                'Link copied successfully.';





            status.classList.remove(

                'hidden'

            );

        }

    }





    /*

    |--------------------------------------------------------------------------

    | WHATSAPP

    |--------------------------------------------------------------------------

    */

    async function shareWhatsApp() {



        await recordShare();





        const text =

            ucShareData.title

            + '\n'

            + ucShareData.url;





        const url =

            'https://wa.me/?text='

            + encodeURIComponent(text);





        window.open(

            url,

            '_blank',

            'noopener,noreferrer'

        );

    }





    /*

    |--------------------------------------------------------------------------

    | FACEBOOK

    |--------------------------------------------------------------------------

    */

    async function shareFacebook() {



        await recordShare();





        const url =

            'https://www.facebook.com/sharer/sharer.php?u='

            + encodeURIComponent(

                ucShareData.url

            );





        window.open(

            url,

            '_blank',

            'noopener,noreferrer'

        );

    }





    /*

    |--------------------------------------------------------------------------

    | ESC CLOSE

    |--------------------------------------------------------------------------

    */

    document.addEventListener(

        'keydown',

        function (event) {



            if (event.key === 'Escape') {

                closeShareModal();

            }



        }

    );

    // Re-fetch cards when returning to a previously opened feed.
    window.addEventListener('pageshow', function (event) {
        const navigation = performance.getEntriesByType('navigation')[0];
        if (event.persisted || (navigation && navigation.type === 'back_forward')) {
            window.location.reload();
        }
    });

</script>



</x-app-layout>
