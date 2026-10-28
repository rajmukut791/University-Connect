@props(['type', 'postId', 'ownerId', 'post' => null])
@if(auth()->check() && ((int) auth()->id() === (int) $ownerId || in_array(auth()->user()->role, ['admin', 'super_admin'], true)))
    @php
        $managedPost = $post ?? (\App\Services\PostLifecycleService::TYPES[$type])::findOrFail($postId);
        $isOwner = (int) auth()->id() === (int) $ownerId;
        $extensionsReady = \Illuminate\Support\Facades\Schema::hasTable('post_extension_requests');
        $archiveReady = \Illuminate\Support\Facades\Schema::hasColumn($managedPost->getTable(), 'archived_at');
        $pendingExtension = $extensionsReady ? \App\Models\PostExtensionRequest::where('post_type', $type)->where('post_id', $postId)->where('status', 'pending')->first() : null;
    @endphp
    <div class="flex flex-wrap items-center gap-3 py-3">
        <a href="{{ route('posts.edit', ['type' => $type, 'id' => $postId]) }}" class="rounded-xl bg-indigo-600 px-4 py-2 font-bold text-white">Edit / Update</a>
        @if($isOwner && $extensionsReady && $archiveReady && !$pendingExtension)
        <a href="{{ route('posts.extension.form', ['type'=>$type,'id'=>$postId]) }}" class="rounded-xl bg-amber-600 px-4 py-2 font-bold text-white">Apply Date Extension</a>
        @elseif($isOwner && $pendingExtension)
            <span class="rounded-xl bg-amber-500/10 px-4 py-2 text-amber-300">Extension Pending</span>
        @endif
        <details class="rounded-xl border border-white/10 p-3"><summary class="cursor-pointer font-bold">Manage Post</summary><div class="mt-3 flex flex-wrap gap-3">
        @if($archiveReady && !$managedPost->archived_at)
        <form method="POST" action="{{ route('posts.archive.store', ['type'=>$type,'id'=>$postId]) }}" onsubmit="return confirm('Move this post to archive?')">@csrf @method('PATCH')<button type="submit" class="rounded-xl bg-slate-700 px-4 py-2 font-bold text-white">Move to Archive</button></form>
        @elseif($managedPost->archived_at)
            <span class="rounded-xl bg-slate-700 px-4 py-2 text-white">Already Archived</span>
        @endif
        <form method="POST" action="{{ route('posts.destroy', ['type' => $type, 'id' => $postId]) }}" onsubmit="return confirm('Delete this post permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 font-bold text-white">Delete</button>
        </form>
        </div></details>
    </div>
    @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true) && $pendingExtension)
        <section class="my-4 rounded-xl border border-amber-500/30 p-4 space-y-3">
            <h2 class="font-bold">Pending Date Extension Request</h2>
            <p class="whitespace-pre-line">{{ $pendingExtension->reason }}</p>
            @foreach($pendingExtension->dates as $field => $value)<p>{{ ucwords(str_replace('_', ' ', $field)) }}: {{ $value }}</p>@endforeach
            @if((int)$pendingExtension->user_id === (int)auth()->id() || $isOwner)
                <p class="text-xs text-amber-300">Another administrator must review your own request.</p>
            @else
                <x-extension-review-form :extension="$pendingExtension" />
            @endif
        </section>
    @endif
@endif
