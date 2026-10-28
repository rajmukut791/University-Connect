<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,.40),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.30),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-emerald-300 font-black">Donation Hub</p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Campus Donation Campaigns
                    </h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Students, alumni and admins can create donation campaigns for student support, events, scholarship and emergency funds.
                    </p>
                </div>

                <a href="{{ route('donations.create') }}"
                   class="px-6 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl hover:scale-105 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Create Campaign
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes donationFloat {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .donation-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
            transition: .35s ease;
        }

        .donation-card:hover {
            transform: translateY(-8px) scale(1.01);
        }

        .donation-float {
            animation: donationFloat 5s ease-in-out infinite;
        }
    </style>

    <div class="space-y-8">

        @if(session('success'))
            <div class="donation-card p-5 text-emerald-500 font-black">
                <i class="fas fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(auth()->user()->role === 'admin')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="rounded-[1.5rem] bg-white/10 border border-white/10 p-5 shadow-xl">
            <p class="text-slate-300 font-bold">Total Campaigns</p>
            <h3 class="mt-4 text-4xl font-black text-emerald-400">
                {{ $stats['total'] }}
            </h3>
        </div>

        <div class="rounded-[1.5rem] bg-white/10 border border-white/10 p-5 shadow-xl">
            <p class="text-slate-300 font-bold">Approved</p>
            <h3 class="mt-4 text-4xl font-black text-cyan-400">
                {{ $stats['approved'] }}
            </h3>
        </div>

        <div class="rounded-[1.5rem] bg-white/10 border border-white/10 p-5 shadow-xl">
            <p class="text-slate-300 font-bold">Pending Review</p>
            <h3 class="mt-4 text-4xl font-black text-amber-400">
                {{ $stats['pending'] }}
            </h3>
        </div>
    </div>
@endif

        <div class="uc-post-grid grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($donations as $donation)
                <div class="donation-card p-4 ">
                    <div class="relative z-10">
                        @if($donation->image)
                            <img src="{{ asset('storage/' . $donation->image) }}"
                                 class="h-32 w-full rounded-3xl object-cover mb-5">
                        @else
                            <div class="h-32 w-full rounded-3xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center mb-5 donation-float">
                                <i class="fas fa-hand-holding-heart text-5xl text-white"></i>
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-black
                                @if($donation->status === 'approved') bg-emerald-500/15 text-emerald-600
                                @elseif($donation->status === 'rejected') bg-red-500/15 text-red-600
                                @else bg-amber-500/15 text-amber-600 @endif">
                                {{ ucfirst($donation->status) }}
                            </span>

                            <span class="px-3 py-1 rounded-full bg-cyan-500/15 text-cyan-600 text-xs font-black">
                                {{ $donation->category ?? 'General' }}
                            </span>
                        </div>

                        <h3 class="mt-4 text-lg font-black text-slate-900 dark:text-white">
                            {{ $donation->title }}
                        </h3>

                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            {{ \Illuminate\Support\Str::limit($donation->description, 120) }}
                        </p>

                        <div class="mt-5">
                            <div class="flex justify-between text-sm font-bold mb-2">
                                <span>৳{{ number_format($donation->collected_amount, 2) }}</span>
                                <span>৳{{ number_format($donation->target_amount, 2) }}</span>
                            </div>

                            <div class="h-3 w-full rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"
                                     style="width: {{ $donation->progress }}%">
                                </div>
                            </div>

                            <p class="mt-2 text-xs text-slate-500 font-bold">
                                {{ $donation->progress }}% funded
                            </p>
                        </div>

                        <div class="mt-5 text-sm text-slate-500">
                            <p>
                                <i class="fas fa-user mr-2 text-emerald-500"></i>
                                Posted by {{ $donation->user->name ?? 'Unknown User' }}
                            </p>

                            @if($donation->deadline)
                                <p class="mt-1">
                                    <i class="fas fa-calendar mr-2 text-cyan-500"></i>
                                    Deadline: {{ $donation->deadline->format('d M, Y') }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <a href="{{ route('donations.show', $donation) }}"
                               class="flex-1 text-center rounded-2xl bg-slate-950 px-4 py-3 text-white font-black hover:bg-emerald-600 transition">
                                Details
                            </a>

                            @if(auth()->user()->isAdmin() && $donation->status === 'pending')
                                <form method="POST" action="{{ route('donations.approve', $donation) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded-2xl bg-emerald-500/15 px-4 py-3 text-emerald-600 font-black">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('donations.reject', $donation) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded-2xl bg-red-500/15 px-4 py-3 text-red-600 font-black">
                                        Reject
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="xl:col-span-3 donation-card p-12 text-center">
                    <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-3xl text-white"></i>
                    </div>

                    <h3 class="mt-6 text-lg font-black text-slate-900 dark:text-white">
                        No Donation Campaign Found
                    </h3>

                    <p class="mt-2 text-slate-500">
                        Create the first campus donation campaign.
                    </p>
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $donations->links() }}</div>
    </div>
</x-app-layout>