<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 text-white">
        <h1 class="sr-only">Apply for Date Extension</h1>
        <p class="my-4">{{ $post->title }} — Explain why more time is needed. Your {{ $type }} request will be reviewed by an admin. Archived posts return to the newsfeed only after approval.</p>
        @if($errors->any()) <div class="text-red-400">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('posts.extension.apply', ['type'=>$type, 'id'=>$post->id]) }}" class="space-y-4">
            @csrf
            @foreach($type === 'event' ? ['start_date','start_time','end_date','end_time'] : ['deadline'] as $field)
                <label class="block">{{ ucwords(str_replace('_',' ', $field)) }}
                    <input required name="{{ $field }}" type="{{ str_contains($field, 'time') ? 'time' : 'date' }}" value="{{ old($field) }}" class="block w-full rounded-lg bg-slate-900 p-3" />
                </label>
            @endforeach
            <label class="block">Reason / Full information<textarea required minlength="10" maxlength="3000" name="reason" rows="5" class="block w-full rounded-lg bg-slate-900 p-3">{{ old('reason') }}</textarea></label>
            <button class="rounded-lg bg-indigo-600 px-5 py-3">Send to Admin</button>
        </form>
    </div>
</x-app-layout>
