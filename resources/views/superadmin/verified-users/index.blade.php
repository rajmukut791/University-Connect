<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-cyan-950 p-8 border border-white/10 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.35),transparent_35%)]"></div>

            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                    Super Admin Verification
                </p>

                <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                    Verified Users AI Panel
                </h2>

                <p class="mt-3 text-slate-300 max-w-3xl">
                    Manually add Student and Alumni verified information. Smart AI formatting will clean role, department, batch, phone and status before saving.
                </p>
            </div>
        </div>
    </x-slot>

    <style>
        .ai-card {
            border-radius: 1.8rem;
            border: 1px solid rgba(255,255,255,.14);
            background: linear-gradient(135deg, rgba(255,255,255,.14), rgba(255,255,255,.05));
            backdrop-filter: blur(22px);
            box-shadow: 0 25px 80px rgba(15,23,42,.35);
        }

        .ai-input {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(2,6,23,.75);
            color: white;
            padding: .85rem 1rem;
            outline: none;
        }

        .ai-input:focus {
            border-color: rgba(34,211,238,.65);
            box-shadow: 0 0 0 4px rgba(34,211,238,.12);
        }

        .ai-label {
            display: block;
            margin-bottom: .45rem;
            color: rgb(203,213,225);
            font-size: .85rem;
            font-weight: 900;
        }
    </style>

    <div class="uc-verified-db space-y-8">

        @if(session('success'))
            <div class="ai-card p-5 text-emerald-300 font-black">
                <i class="fas fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="ai-card p-5 text-red-300 font-black">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Stats --}}
        <div class="uc-verified-stats grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="ai-card p-6">
                <i class="uc-verified-stat-icon fas fa-users" aria-hidden="true"></i>
                <p class="text-slate-400 font-bold">Total Verified</p>
                <h3 class="mt-3 text-4xl font-black text-cyan-300">{{ $stats['total'] ?? 0 }}</h3>
            </div>

            <div class="ai-card p-6">
                <i class="uc-verified-stat-icon fas fa-user-graduate" aria-hidden="true"></i>
                <p class="text-slate-400 font-bold">Students</p>
                <h3 class="mt-3 text-4xl font-black text-emerald-300">{{ $stats['students'] ?? 0 }}</h3>
            </div>

            <div class="ai-card p-6">
                <i class="uc-verified-stat-icon fas fa-graduation-cap" aria-hidden="true"></i>
                <p class="text-slate-400 font-bold">Alumni</p>
                <h3 class="mt-3 text-4xl font-black text-purple-300">{{ $stats['alumni'] ?? 0 }}</h3>
            </div>

            <div class="ai-card p-6">
                <i class="uc-verified-stat-icon fas fa-circle-check" aria-hidden="true"></i>
                <p class="text-slate-400 font-bold">Active</p>
                <h3 class="mt-3 text-4xl font-black text-pink-300">{{ $stats['active'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Manual Input --}}
            <div class="xl:col-span-1 ai-card p-7">
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-300 font-black">
                    AI Manual Input
                </p>

                <h3 class="mt-2 text-2xl font-black text-white">
                    Add Verified User
                </h3>

                <p class="mt-2 text-sm text-slate-400">
                    Short or messy input allowed. Example: role = stu, dept = computer science, batch = 56.
                </p>

                <form method="POST" action="{{ route((auth()->user()->role === 'super_admin' ? 'superadmin' : 'admin') . '.verified-users.store') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="ai-label">Name</label>
                        <input class="ai-input" name="name" placeholder="Example: raj mukut" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label class="ai-label">Email *</label>
                        <input class="ai-input" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label class="ai-label">Phone</label>
                        <input class="ai-input" name="phone" placeholder="017xxxxxxxx" value="{{ old('phone') }}">
                    </div>

                    <div>
                        <label class="ai-label">Student / Alumni ID</label>
                        <input class="ai-input" name="unique_id" placeholder="NUB-CSE-56-001" value="{{ old('unique_id') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="ai-label">Role *</label>
                            <input class="ai-input" name="role" placeholder="stu / alumni" value="{{ old('role', 'student') }}" required>
                        </div>

                        <div>
                            <label class="ai-label">Status</label>
                            <input class="ai-input" name="status" placeholder="active" value="{{ old('status', 'active') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="ai-label">Department</label>
                            <input class="ai-input" name="department" placeholder="cse / computer science" value="{{ old('department') }}">
                        </div>

                        <div>
                            <label class="ai-label">Batch</label>
                            <input class="ai-input" name="batch" placeholder="56" value="{{ old('batch') }}">
                        </div>
                    </div>

                    <div>
                        <label class="ai-label">Notes</label>
                        <textarea class="ai-input min-h-[100px]" name="notes" placeholder="Optional notes">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 to-purple-600 py-4 text-white font-black shadow-xl hover:scale-[1.02] transition">
                        <i class="fas fa-wand-magic-sparkles mr-2"></i>
                        Save Formatting
                    </button>
                </form>
            </div>

            {{-- Bulk Input + List --}}
            <div class="xl:col-span-2 space-y-8">

                {{-- Bulk --}}
                <div class="ai-card p-7">
                    <p class="text-xs uppercase tracking-[0.3em] text-purple-300 font-black">
                        AI Bulk Import
                    </p>

                    <h3 class="mt-2 text-2xl font-black text-white">
                        Paste Multiple Users
                    </h3>

                    <p class="mt-2 text-sm text-slate-400">
                        Format per line: name, email, phone, unique_id, role, department, batch, status, notes
                    </p>

                    <form method="POST"
      action="{{ route((auth()->user()->role === 'super_admin' ? 'superadmin' : 'admin') . '.verified-users.bulk-preview') }}"
      enctype="multipart/form-data">
    @csrf

    <textarea name="bulk_text"
              rows="4"
              class="w-full rounded-2xl bg-slate-950 text-white border border-white/10 p-4"
              placeholder="Raj Mukut, rajmukut@gmail.com, 01700000000, 412201000000, student, CSE, 56, active">{{ old('bulk_text') }}</textarea>

    <div>
        <label class="block text-sm font-bold text-slate-300 mb-2">
            Import File: PDF, Word, PPT, CSV, TXT, XLSX
        </label>

        <input type="file"
       name="import_file"
       accept=".pdf,.doc,.docx,.csv,.txt"
       class="w-full rounded-2xl bg-slate-950 text-white border border-white/10 p-4">
    </div>

    <button type="submit"
            class="px-6 py-3 rounded-2xl bg-gradient-to-r from-violet-600 to-pink-600 text-white font-black">
        <i class="fas fa-file-import mr-2"></i>
        Import Bulk Data
    </button>
</form>
                </div>

                {{-- Search --}}
                <div class="ai-card p-6">
                    <form method="GET" action="{{ route((auth()->user()->role === 'super_admin' ? 'superadmin' : 'admin') . '.verified-users.index') }}"
                          class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <input class="ai-input md:col-span-2" name="search" value="{{ request('search') }}" placeholder="Search name, email, ID, dept">

                        <select class="ai-input" name="role">
                            <option value="">All Roles</option>
                            <option value="student" @selected(request('role') === 'student')>Student</option>
                            <option value="alumni" @selected(request('role') === 'alumni')>Alumni</option>
                        </select>

                        <button class="rounded-2xl bg-cyan-500 text-white font-black">
                            Search
                        </button>
                    </form>
                </div>

                {{-- Users List --}}
                <div class="ai-card p-7">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-300 font-black">
                                Database Records
                            </p>
                            <h3 class="mt-2 text-2xl font-black text-white">
                                Verified Users
                            </h3>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($verifiedUsers as $verifiedUser)
                            <div class="rounded-3xl bg-slate-950/70 border border-white/10 p-5">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-black text-white">
                                            {{ $verifiedUser->name ?? 'No Name' }}
                                        </h4>

                                        <p class="text-sm text-slate-400 mt-1">
                                            {{ $verifiedUser->email }}
                                            @if($verifiedUser->phone)
                                                • {{ $verifiedUser->phone }}
                                            @endif
                                        </p>

                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span class="px-3 py-1 rounded-full bg-cyan-500/15 text-cyan-300 text-xs font-bold">
                                                {{ strtoupper($verifiedUser->role) }}
                                            </span>

                                            <span class="px-3 py-1 rounded-full bg-purple-500/15 text-purple-300 text-xs font-bold">
                                                {{ $verifiedUser->department ?? 'No Dept' }}
                                            </span>

                                            <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 text-xs font-bold">
                                                {{ $verifiedUser->batch ?? 'No Batch' }}
                                            </span>

                                            <span class="px-3 py-1 rounded-full bg-amber-500/15 text-amber-300 text-xs font-bold">
                                                {{ strtoupper($verifiedUser->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route((auth()->user()->role === 'super_admin' ? 'superadmin' : 'admin') . '.verified-users.destroy', $verifiedUser) }}"
                                          onsubmit="return confirm('Delete this verified user?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="rounded-xl bg-red-500/15 text-red-300 px-4 py-2 font-black">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-white/15 p-8 text-center">
                                <p class="text-slate-400 font-bold">No verified user found.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $verifiedUsers->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('components.verified-db-style')
</x-app-layout>