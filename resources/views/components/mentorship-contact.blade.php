@props(['user'])
@if(auth()->check() && auth()->id() !== $user->id && in_array(auth()->user()->role, ['student','alumni'], true) && $user->is_active && !$user->is_blocked)
<div class="my-4 space-y-3 rounded-2xl border border-white/10 p-4">
    <p class="font-bold text-slate-300">{{ ($user->requires_message_request ?? true) ? 'Request First - acceptance required before messaging' : 'Direct messaging available' }}</p>
    @if(auth()->user()->canMessage($user))
        <a class="inline-block rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white" href="{{ route('messages.show',$user) }}">Message</a>
    @else
        @php $pending = \App\Models\Mentorship::where('student_id',auth()->id())->where('mentor_id',$user->id)->where('status','pending')->exists(); @endphp
        @if($pending)<span class="text-amber-300">Request Pending</span>
        @else<form method="POST" action="{{ route('mentors.request',$user) }}">@csrf<button class="rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white">Request Mentorship</button></form>@endif
    @endif
</div>
@endif
