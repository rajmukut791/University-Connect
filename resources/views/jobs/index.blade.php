<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-purple-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]"></div>
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl animate-pulse"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        AI Career Portal
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Job Opportunities
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Discover internships, remote jobs, alumni-posted opportunities and smart career matches.
                    </p>
                </div>

                @if(
    auth()->check() &&
    in_array(
        auth()->user()->role,
        ['alumni', 'admin', 'super_admin'],
        true
    )
)
    <div class="flex flex-wrap gap-3">

        <a
            href="{{ route('jobs.create') }}"
            class="px-6 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-fuchsia-500 text-white font-black shadow-2xl hover:scale-105 transition"
        >
            <i class="fas fa-plus mr-2"></i>

            @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))
                Add Job
            @else
                Post Job
            @endif
        </a>

        @if(auth()->user()->isAlumni())
            <a
                href="{{ route('jobs.my') }}"
                class="px-6 py-4 rounded-2xl border border-white/10 bg-white/10 text-white font-black shadow-xl hover:scale-105 hover:bg-white/15 transition"
            >
                <i class="fas fa-folder-open mr-2"></i>
                Manage My Jobs
            </a>
        @endif

    </div>
@endif
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes ucFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        @keyframes ucScan {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(120%); }
        }

        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-8px) scale(1.01);
        }

        .uc-card::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 45%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
            transform: translateX(-120%);
        }

        .uc-card:hover::before {
            animation: ucScan 1.1s ease;
        }

        .uc-float {
            animation: ucFloat 5s ease-in-out infinite;
        }
    </style>

    <div class="space-y-8">

        @if(session('success'))
            <div class="uc-card p-5 border-emerald-400/30">
                <div class="relative z-10 flex items-center gap-3 text-emerald-500 font-black">
                    <i class="fas fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @php
            $isAdmin = auth()->check() && auth()->user()->isAdmin();

            if ($isAdmin) {
                $cards = [
                    [
                        'title' => 'Total Jobs',
                        'value' => $stats['total_jobs'] ?? 0,
                        'icon'  => 'fa-briefcase',
                        'from'  => 'from-cyan-400',
                        'to'    => 'to-blue-600',
                    ],
                    [
                        'title' => 'Approved',
                        'value' => $stats['approved_jobs'] ?? 0,
                        'icon'  => 'fa-circle-check',
                        'from'  => 'from-emerald-400',
                        'to'    => 'to-green-600',
                    ],
                    [
                        'title' => 'Pending',
                        'value' => $stats['pending_jobs'] ?? 0,
                        'icon'  => 'fa-clock',
                        'from'  => 'from-amber-400',
                        'to'    => 'to-orange-600',
                    ],

                               [
                             'title' => 'Rejected',
                             'value' => $stats['rejected_jobs'] ?? 0,
                           'icon'  => 'fa-circle-xmark',
                          'from'  => 'from-red-400',
                          'to'    => 'to-rose-600',
                           ],
                ];
            } else {
                $cards = [
                    [
                        'title' => 'Total Jobs',
                        'value' => $stats['total_jobs'] ?? 0,
                        'icon'  => 'fa-briefcase',
                        'from'  => 'from-cyan-400',
                        'to'    => 'to-blue-600',
                    ],
                ];
            }
        @endphp

        <div class="grid grid-cols-1 {{ $isAdmin ? 'md:grid-cols-4' : 'md:grid-cols-1' }} gap-6">
            @foreach($cards as $card)
                <div class="uc-card p-4 {{ !$isAdmin ? 'max-w-sm' : '' }}">
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                                {{ $card['title'] }}
                            </p>

                            <h3 class="mt-3 text-4xl font-black bg-gradient-to-r {{ $card['from'] }} {{ $card['to'] }} bg-clip-text text-transparent">
                                {{ $card['value'] }}
                            </h3>
                        </div>

                        <div class="h-11 w-11 rounded-2xl bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} flex items-center justify-center shadow-xl uc-float">
                            <i class="fas {{ $card['icon'] }} text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="uc-card p-4">
            <form method="GET"
                  action="{{ route('jobs.index') }}"
                  class="relative z-10 grid grid-cols-1 lg:grid-cols-4 gap-4">

                <div class="lg:col-span-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search title, company, location, skill..."
                        class="w-full rounded-2xl border border-white/10 bg-slate-900 px-5 py-4 text-sm font-black text-white placeholder:text-slate-400 outline-none focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10"
                    >
                </div>

                <div>
                    <select
                        name="type"
                        class="w-full rounded-2xl border border-cyan-400/40 bg-slate-900 px-5 py-4 text-sm font-black text-white outline-none focus:border-cyan-400 focus:ring-4 focus:ring-cyan-400/10"
                    >
                        <option class="bg-slate-900 text-white" value="">All Types</option>
                        <option class="bg-slate-900 text-white" value="full_time" @selected(request('type') === 'full_time')>Full Time</option>
                        <option class="bg-slate-900 text-white" value="part_time" @selected(request('type') === 'part_time')>Part Time</option>
                        <option class="bg-slate-900 text-white" value="internship" @selected(request('type') === 'internship')>Internship</option>
                        <option class="bg-slate-900 text-white" value="remote" @selected(request('type') === 'remote')>Remote</option>
                        <option class="bg-slate-900 text-white" value="hybrid" @selected(request('type') === 'hybrid')>Hybrid</option>
                        <option class="bg-slate-900 text-white" value="contract" @selected(request('type') === 'contract')>Contract</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="rounded-2xl bg-gradient-to-r from-cyan-500 to-fuchsia-500 px-6 py-4 text-sm font-black text-white shadow-xl hover:scale-105 transition"
                >
                    <i class="fas fa-magnifying-glass mr-2"></i>
                    Search Jobs
                </button>
            </form>
        </div>
                <div class="uc-post-grid grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($jobs as $job)
                <div class="uc-card p-4">
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-start justify-between gap-4">
                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-cyan-500 to-fuchsia-500 flex items-center justify-center shadow-xl">
                                <i class="fas fa-briefcase text-2xl text-white"></i>
                            </div>

                            @if(auth()->check() && auth()->user()->isAdmin())
                                <span class="px-3 py-1 rounded-full text-xs font-black
                                    @if($job->status === 'approved') bg-emerald-500/15 text-emerald-600
                                    @elseif($job->status === 'pending') bg-amber-500/15 text-amber-600
                                    @else bg-red-500/15 text-red-600 @endif">
                                    {{ strtoupper($job->status) }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-5">
                            <h3 class="text-base font-black text-slate-900 dark:text-white">
                                {{ $job->title }}
                            </h3>

                            <p class="mt-2 text-sm text-slate-500">
                                <i class="fas fa-building mr-1"></i>
                                {{ $job->company_name ?? $job->company ?? 'Company' }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-600 text-xs font-bold">
                                    {{ str_replace('_', ' ', ucfirst($job->type)) }}
                                </span>

                                @if($job->location)
                                    <span class="px-3 py-1 rounded-full bg-purple-500/15 text-purple-600 text-xs font-bold">
                                        <i class="fas fa-location-dot mr-1"></i>
                                        {{ $job->location }}
                                    </span>
                                @endif

                                @if($job->salary_range)
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-bold">
                                        {{ $job->salary_range }}
                                    </span>
                                @endif
                            </div>

                            <p class="mt-5 text-sm text-slate-500 leading-relaxed">
                                {{ Str::limit($job->description, 70) }}
                            </p>
                        </div>

                        <div class="mt-6 pt-5 border-t border-white/10 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs text-slate-500">Posted by</p>
                                <p class="font-bold text-sm text-slate-900 dark:text-white">
                                    {{ $job->postedBy?->name ?? 'University Alumni' }}
                                </p>
                            </div>

                            <a href="{{ route('jobs.show', $job) }}"
                               class="px-4 py-2 rounded-xl bg-slate-950 text-white font-bold hover:bg-cyan-600 transition">
                                View
                            </a>
                        </div>
                        @if(auth()->check() && auth()->user()->isAdmin())

    <form
        method="POST"
        action="{{ route('jobs.destroy', $job) }}"
        onsubmit="return confirm('Are you sure you want to delete this job post? This action cannot be undone.')"
        class="mt-3"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="w-full rounded-xl bg-red-500/10 border border-red-500/20 px-4 py-2 text-red-600 dark:text-red-300 font-black hover:bg-red-500 hover:text-white transition"
        >
            <i class="fas fa-trash mr-2"></i>
            Delete Job
        </button>

    </form>

@endif

                        @if(auth()->check() && auth()->user()->isAdmin() && $job->status === 'pending')
                            <div class="mt-4 flex gap-2">
                                <form method="POST" action="{{ route('jobs.approve', $job) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="w-full rounded-xl bg-emerald-500 py-2 text-white font-black hover:bg-emerald-600 transition">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('jobs.reject', $job) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="w-full rounded-xl bg-red-500 py-2 text-white font-black hover:bg-red-600 transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 uc-card p-10 text-center">
                    <div class="relative z-10">
                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-cyan-500 to-fuchsia-500 flex items-center justify-center">
                            <i class="fas fa-magnifying-glass text-3xl text-white"></i>
                        </div>

                        <h3 class="mt-6 text-2xl font-black text-slate-900 dark:text-white">
                            No jobs found
                        </h3>

                        <p class="mt-2 text-slate-500">
                            Try another search or filter.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <div>
            {{ $jobs->links() }}
        </div>
    </div>
</x-app-layout>