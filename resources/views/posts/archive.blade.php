<x-app-layout>
<div class="mx-auto max-w-6xl space-y-4 text-white">
<h1 class="sr-only">Archive & Date Extension Requests</h1>
<nav class="flex gap-2" aria-label="Post sections">@foreach(['job','event','donation'] as $section)<a class="rounded-xl border border-white/10 px-4 py-2 text-sm {{ $type === $section ? 'bg-indigo-600' : 'bg-white/5' }}" href="{{ route('posts.archive',['type'=>$section,'status'=>$status]) }}">{{ ucfirst($section) }}</a>@endforeach</nav>
@if(session('success'))<p role="status" class="text-sm text-emerald-300">{{ session('success') }}</p>@endif
@if($errors->any())<p role="alert" class="text-sm text-red-300">{{ $errors->first() }}</p>@endif
@if(!$management)
<h2 class="font-bold">Archived Posts</h2>
@forelse($posts as $post)<div class="rounded-xl border border-white/10 bg-slate-900/70 p-4"><a class="font-bold text-indigo-200" href="{{ route(match($type){'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'},$post) }}">{{ $post->title }}</a><p class="mt-1 text-xs text-slate-400">Archived: {{ $post->archived_at }}</p></div>@empty<p>No archived posts.</p>@endforelse
{{ $posts->links() }}
@endif
<div class="flex flex-wrap items-center justify-between gap-3">
<h2 class="font-bold">{{ $management ? 'Section Requests' : 'My Requests' }}</h2>
<nav class="flex flex-wrap gap-2" aria-label="Request status">
@foreach(['all','pending','approved','rejected'] as $filter)<a class="rounded-lg border border-white/10 px-3 py-2 text-xs {{ $status === $filter ? 'bg-indigo-600' : 'bg-white/5' }}" href="{{ route('posts.archive',['type'=>$type,'status'=>$filter]) }}">{{ ucfirst($filter) }} <span class="ml-1 text-indigo-200">{{ $filter === 'all' ? $counts->sum() : ($counts[$filter] ?? 0) }}</span></a>@endforeach
</nav></div>
@forelse($requests as $extension)
@php
$linkedPost = $requestPosts->get($extension->post_id);
$isOwnRequest = (int)$extension->user_id === (int)auth()->id() || ($linkedPost && \App\Support\PostAccess::ownerId($type,$linkedPost) === (int)auth()->id());
$badge = match($extension->status) {'approved'=>'text-emerald-300 bg-emerald-500/10','rejected'=>'text-rose-300 bg-rose-500/10',default=>'text-amber-300 bg-amber-500/10'};
@endphp
<article class="space-y-3 rounded-2xl border border-white/10 bg-slate-900/70 p-4">
<header class="flex flex-wrap justify-between gap-3"><div><h3 class="font-bold">{{ $linkedPost?->title ?? 'Post no longer available' }}</h3><p class="mt-1 text-xs text-slate-400">{{ $extension->user?->name ?? 'Former account' }} · Submitted {{ $extension->created_at?->format('d M Y, h:i A') }}</p></div><span class="rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">{{ ucfirst($extension->status) }}</span></header>
@if($linkedPost)<a class="inline-block text-xs font-bold text-indigo-300" href="{{ route(match($type){'job'=>'jobs.show','event'=>'events.show','donation'=>'donations.show'},$linkedPost) }}">View Post →</a>@endif
<p class="whitespace-pre-line text-sm text-slate-300">{{ $extension->reason }}</p>
<div class="overflow-x-auto rounded-xl border border-white/10"><table class="w-full text-left text-xs"><thead class="bg-white/5 text-slate-400"><tr><th class="p-3">Schedule</th><th class="p-3">{{ $extension->original_dates ? 'At Application' : 'Current Post' }}</th><th class="p-3">Requested Date</th></tr></thead><tbody>
@foreach($extension->dates as $field=>$value)
@php
$current = $extension->original_dates ? ($extension->original_dates[$field] ?? null) : $linkedPost?->getAttribute($field);
$current = $current instanceof \DateTimeInterface ? $current->format('Y-m-d') : $current;
@endphp
<tr class="border-t border-white/5"><th class="p-3 font-medium">{{ ucwords(str_replace('_',' ',$field)) }}</th><td class="p-3 text-slate-400">{{ $current ?: 'Not provided' }}</td><td class="p-3 font-bold text-indigo-200">{{ $value }}</td></tr>
@endforeach
</tbody></table></div>
@if(!$extension->original_dates)<p class="text-[11px] text-slate-500">Earlier requests have no saved original schedule; current post dates are shown.</p>@endif
@if($extension->reviewed_at)<p class="text-xs text-slate-400">Reviewed by {{ $extension->reviewer?->name ?? 'Former administrator' }} · {{ $extension->reviewed_at->format('d M Y, h:i A') }}</p>@endif
@if($extension->review_note)<p class="rounded-lg bg-white/5 p-3 text-xs">Admin note: {{ $extension->review_note }}</p>@endif
@if($management && $extension->status === 'pending')
@if($isOwnRequest)<p class="text-xs text-amber-300">Another administrator must review your own request.</p>@elseif($linkedPost)<x-extension-review-form :extension="$extension" />@endif
@endif
</article>
@empty<p class="rounded-xl border border-dashed border-white/10 p-5 text-sm text-slate-400">No extension requests.</p>@endforelse
{{ $requests->links() }}
</div>
</x-app-layout>
