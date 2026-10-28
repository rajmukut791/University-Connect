@props(['extension'])
<form method="POST" action="{{ route('posts.extension.review',$extension) }}" class="flex flex-wrap items-center gap-2">
    @csrf @method('PATCH')
    <label class="min-w-0 flex-1 text-xs text-slate-400">Review note
        <input name="review_note" maxlength="3000" placeholder="Required when rejecting" class="mt-1 w-full rounded-xl border border-white/10 bg-slate-950 px-3 py-2 text-sm text-white">
    </label>
    <button name="decision" value="approved" class="rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white">Approve & Publish</button>
    <button name="decision" value="rejected" class="rounded-xl bg-rose-700 px-4 py-2 text-xs font-bold text-white">Reject</button>
</form>
