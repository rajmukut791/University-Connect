<x-app-layout>
<div class="mx-auto max-w-7xl space-y-6 p-6 text-white">
    <div class="uc-compact-header flex flex-wrap items-center justify-between gap-4"><div><h1 class="text-2xl font-bold">Donation Management</h1><p class="uc-header-context mt-2 text-slate-300">{{ $donation->title }}</p></div><a href="{{ route('donations.show', $donation) }}" class="rounded-xl bg-indigo-600 px-4 py-3">Back to Campaign</a></div>
    @if(session('success'))<p role="status" class="text-green-300">{{ session('success') }}</p>@endif
    @if(session('error'))<p class="text-red-400">{{ session('error') }}</p>@endif
    @if($errors->any())<p class="text-red-400">{{ $errors->first() }}</p>@endif
    <div class="rounded-xl bg-slate-900 p-5"><p class="font-bold">Collected: {{ number_format((float) $donation->collected_amount, 2) }} / Target: {{ number_format((float) $donation->target_amount, 2) }}</p><p class="mt-2 text-slate-300">bKash / Nagad payments count after campaign owner confirmation. Successful card payments are automatically confirmed.</p></div>
    <section class="rounded-2xl border border-white/10 bg-slate-900 p-5">
        <h2 class="text-xl font-bold">bKash / Nagad Transactions</h2>
        @foreach($payments as $payment)
            <article class="mt-4 space-y-2 border-t border-white/10 pt-4">
                <p class="font-bold">{{ $payment->user?->name ?? 'Account unavailable' }} - {{ $payment->payment_method }} - {{ number_format((float) $payment->amount, 2) }}</p>
                <p>Email: {{ $payment->user?->email ?: 'Not provided' }} | Sender number: {{ $payment->account_number }}</p>
                <p>Transaction ID: {{ $payment->transaction_id }} | Submitted: {{ $payment->created_at?->format('d M Y, h:i A') }}</p>
                <p>Status: {{ $payment->status === 'approved' ? 'Confirmed / Checked' : ucfirst($payment->status) }}</p>
                @if($payment->note)<p>{{ $payment->note }}</p>@endif
                @if($payment->rejection_reason)<p class="text-red-400">{{ $payment->rejection_reason }}</p>@endif
                @if($isOwner && $payment->screenshot)<a class="text-cyan-300 underline" href="{{ route('secure.donation-payments.screenshot', $payment) }}" target="_blank" rel="noopener">View Payment Proof</a>@endif
                @if($payment->status === 'pending' && $isOwner)
                    <div class="flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('donation-payments.approve', $payment) }}" onsubmit="return confirm('Confirm that this payment was received?')">@csrf @method('PATCH')<button class="rounded-xl bg-green-700 px-4 py-2">Confirm Payment</button></form>
                        <form method="POST" action="{{ route('donation-payments.reject', $payment) }}" class="flex flex-wrap gap-2">@csrf @method('PATCH')<input name="rejection_reason" required maxlength="1000" placeholder="Reason for rejection" class="rounded-xl bg-slate-800 p-2"><button class="rounded-xl bg-red-700 px-4 py-2">Reject Payment</button></form>
                    </div>
                @elseif($payment->status === 'pending')<p class="text-amber-300">Awaiting campaign owner verification.</p>@endif
            </article>
        @endforeach
        @if($payments->isEmpty())<p class="mt-3 text-slate-400">No manual transactions.</p>@endif
        {{ $payments->links() }}
    </section>
    <section class="rounded-2xl border border-white/10 bg-slate-900 p-5"><h2 class="text-xl font-bold">Card Transactions</h2>
        @forelse($cardPayments as $payment)<div class="mt-4 border-t border-white/10 pt-4"><p>{{ $payment->donor_name ?? 'Account unavailable' }} - {{ number_format((float) $payment->amount, 2) }}</p><p class="break-all">Transaction: {{ $payment->session_id }}</p><p class="text-green-300">Confirmed automatically - {{ $payment->created_at }}</p></div>@empty<p class="mt-3 text-slate-400">No card transactions.</p>@endforelse
        {{ $cardPayments->links() }}
    </section>
    <section class="rounded-2xl border border-white/10 bg-slate-900 p-5"><h2 class="text-xl font-bold">Confirmed Donors</h2>
        <div class="overflow-x-auto"><table class="mt-4 w-full text-left text-sm"><thead><tr>@foreach(['Name','Role','Email','Amount','Method','Status','Date'] as $heading)<th class="p-3">{{ $heading }}</th>@endforeach</tr></thead><tbody>
            @forelse($donors as $donor)<tr class="border-t border-white/10"><td class="p-3">{{ $donor->donor_name ?: ($donor->user?->name ?? 'Anonymous Donor') }}</td><td class="p-3">{{ $donor->user ? ucfirst($donor->user->role) : 'Not provided' }}</td><td class="p-3">{{ $donor->donor_email ?: ($donor->user?->email ?? 'Not provided') }}</td><td class="p-3">{{ number_format((float) $donor->amount, 2) }}</td><td class="p-3">{{ $donor->message === 'Verified VISA/Card payment' ? 'Card' : ucfirst($donor->payment_method) }}</td><td class="p-3 text-green-300">Confirmed / Checked</td><td class="p-3">{{ $donor->created_at?->format('d M Y, h:i A') }}</td></tr>@empty<tr><td colspan="7" class="p-3 text-slate-400">No confirmed donors.</td></tr>@endforelse
        </tbody></table></div>{{ $donors->links() }}
    </section>
</div>
</x-app-layout>
