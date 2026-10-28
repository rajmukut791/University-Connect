<x-app-layout>
<div class="mx-auto max-w-6xl space-y-6 p-6 text-white">
    <div class="uc-compact-header flex flex-wrap items-center justify-between gap-4"><h1 class="text-2xl font-bold">{{ $title }}</h1><a class="rounded-xl bg-indigo-600 px-4 py-3" href="{{ route(auth()->user()->role === 'student' ? 'student.dashboard' : 'alumni.dashboard') }}">Back to Dashboard</a></div>
    @if($section === 'mentorships' && auth()->user()->role === 'alumni')
        <div class="flex gap-4"><a href="{{ route('dashboard.activity',['section'=>'mentorships']) }}" class="rounded-xl bg-slate-800 px-4 py-2">My Mentees</a><a href="{{ route('dashboard.activity',['section'=>'mentorships','status'=>'pending']) }}" class="rounded-xl bg-slate-800 px-4 py-2">Pending Requests</a></div>
    @endif
    <div class="space-y-4">
        @forelse($items as $item)
            <article class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-white/10 bg-slate-900 p-5">
                @if($section === 'events')
                    <div><p class="font-bold">{{ $item->title }}</p><p class="mt-2 text-slate-400">{{ $item->location }} | Registered</p></div>
                    <a class="rounded-xl bg-indigo-600 px-4 py-2" href="{{ route('events.show', $item) }}">View Event</a>
                @elseif($section === 'mentorships')
                    <div><span class="mr-3 text-slate-400">{{ $items->firstItem() + $loop->index }}.</span><span class="font-bold">{{ $item->name }}</span><p class="mt-2 text-slate-400">{{ $item->department ?: 'Department not provided' }}</p></div>
                    <div class="flex gap-3"><a class="rounded-xl bg-slate-700 px-4 py-2" href="{{ route($item->role === 'alumni' ? 'profiles.alumni.show' : 'profiles.student.show', $item) }}">View Profile</a>
                    @if(!$pending && auth()->user()->canMessage($item))<a class="rounded-xl bg-indigo-600 px-4 py-2" href="{{ route('messages.show',$item) }}">Message</a>@endif
                    @if($pending)<a class="rounded-xl bg-indigo-600 px-4 py-2" href="{{ route('mentors.requests') }}">Review Request</a>@endif</div>
                @else
                    <div><p class="font-bold">{{ $item->sender?->name ?? 'Account unavailable' }}</p><p class="mt-2 text-slate-400">{{ $item->unread_count }} unread messages</p></div>
                    @if($item->sender)<a class="rounded-xl bg-indigo-600 px-4 py-2" href="{{ route('messages.show',$item->sender) }}">Open Unread Conversation</a>@endif
                @endif
            </article>
        @empty
            <p class="rounded-2xl border border-white/10 bg-slate-900 p-6 text-slate-400">{{ match($section) { 'events'=>'You have no registered events.', 'mentorships'=>$pending ? 'You have no pending mentorship requests.' : 'You have no accepted or completed mentorship connections.', default=>'You have no unread messages.' } }}</p>
        @endforelse
    </div>
    {{ $items->links() }}
</div>
</x-app-layout>
