<x-app-layout>
    <section class="mx-auto max-w-7xl space-y-5 p-6 text-white">
        <div class="uc-compact-header flex flex-wrap items-center justify-between gap-4">
            <div><h1 class="text-2xl font-bold">Registered Participants</h1><p class="uc-header-context mt-2 text-slate-300">{{ $event->title }} · {{ $participants->total() }} registrations</p></div>
            <a href="{{ route('events.show', $event) }}" class="rounded-xl bg-indigo-600 px-4 py-3 font-bold">Back to Event</a>
        </div>
        @if(session('success'))<p role="status" class="rounded-xl bg-green-500/10 p-4 text-green-300">{{ session('success') }}</p>@endif
        <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-900 p-4">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-white/10 text-slate-400">@foreach(['Name','Role','University ID','Phone Number','Email','Status','Action'] as $heading)<th class="p-3">{{ $heading }}</th>@endforeach</tr></thead>
                <tbody>
                    @forelse($participants as $participant)
                        @php
                            $registrant = $participant->user;
                            $universityId = $registrant?->official_id ?: ($registrant?->role === 'student' ? $registrant?->student_id : ($registrant?->role === 'alumni' ? $registrant?->alumni_id : null));
                        @endphp
                        <tr class="border-b border-white/10">
                            <td class="p-3">{{ $registrant?->name ?? 'Account unavailable' }}</td>
                            <td class="p-3">{{ $registrant ? ucfirst(str_replace('_', ' ', $registrant->role)) : 'Not available' }}</td>
                            <td class="p-3">{{ $universityId ?: 'Not provided' }}</td>
                            <td class="p-3">{{ $registrant?->phone ?: 'Not provided' }}</td>
                            <td class="p-3">{{ $registrant?->email ?: 'Not provided' }}</td>
                            <td class="p-3">{{ $participant->status === 'approved' ? 'Registered' : ($participant->status === 'pending' ? 'Incomplete registration' : ucfirst($participant->status)) }}</td>
                            <td class="p-3">
                                @if(in_array($participant->status, ['pending','approved'], true))
                                    <form method="POST" action="{{ route('events.participants.cancel', [$event, $participant->id]) }}" onsubmit="return confirm('Cancel this user participation and notify them?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-xl bg-red-700 px-4 py-2 font-bold text-white">Cancel Participation</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-4 text-slate-400">No registered participants.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $participants->links() }}
    </section>
</x-app-layout>
