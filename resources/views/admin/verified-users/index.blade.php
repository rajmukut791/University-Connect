<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        Admin Verification Center
                    </p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Verified Users AI Panel
                    </h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Upload CSV or manually add official Student and Alumni records. AI smart formatting will clean short or incorrect inputs.
                    </p>
                </div>

                <a href="{{ url('/dashboard') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/15 px-5 py-3 text-white font-black hover:bg-white/20 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .v-card {
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.12);
            background: linear-gradient(135deg, rgba(255,255,255,.12), rgba(255,255,255,.05));
            backdrop-filter: blur(20px);
            box-shadow: 0 24px 70px rgba(15,23,42,.22);
        }

        .v-input {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgba(148,163,184,.35);
            background: rgba(2,6,23,.75);
            color: white;
            padding: .85rem 1rem;
            outline: none;
        }

        .v-input:focus {
            border-color: rgba(34,211,238,.75);
            box-shadow: 0 0 0 4px rgba(34,211,238,.12);
        }

        .v-label {
            display: block;
            margin-bottom: .45rem;
            color: rgb(203,213,225);
            font-size: .85rem;
            font-weight: 900;
        }
    </style>

    <div class="uc-verified-db space-y-8">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-500 font-black">
                <i class="fas fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-500/15 border border-red-500/30 p-4 text-red-500 font-black">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="uc-verified-stats grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="v-card p-6">
                <i class="uc-verified-stat-icon fas fa-users" aria-hidden="true"></i>
                <p class="text-sm text-slate-400 font-bold">Total</p>
                <h3 class="mt-3 text-4xl font-black text-cyan-400">{{ $stats['total'] ?? 0 }}</h3>
            </div>

            <div class="v-card p-6">
                <i class="uc-verified-stat-icon fas fa-user-graduate" aria-hidden="true"></i>
                <p class="text-sm text-slate-400 font-bold">Students</p>
                <h3 class="mt-3 text-4xl font-black text-blue-400">{{ $stats['students'] ?? 0 }}</h3>
            </div>

            <div class="v-card p-6">
                <i class="uc-verified-stat-icon fas fa-graduation-cap" aria-hidden="true"></i>
                <p class="text-sm text-slate-400 font-bold">Alumni</p>
                <h3 class="mt-3 text-4xl font-black text-purple-400">{{ $stats['alumni'] ?? 0 }}</h3>
            </div>

            <div class="v-card p-6">
                <i class="uc-verified-stat-icon fas fa-circle-check" aria-hidden="true"></i>
                <p class="text-sm text-slate-400 font-bold">Active</p>
                <h3 class="mt-3 text-4xl font-black text-emerald-400">{{ $stats['active'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Manual AI Input --}}
            <div class="v-card p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-300 font-black">
                    Manual AI Input
                </p>

                <h3 class="mt-2 text-2xl font-black text-white">
                    Add Verified User
                </h3>

                <p class="mt-2 text-sm text-slate-400">
                    Example: role = stu, dept = cse, batch = 56. AI will format it correctly.
                </p>

                <form method="POST" action="{{ route('admin.verified-users.store') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="v-label">Name</label>
                        <input class="v-input" name="name" value="{{ old('name') }}" placeholder="Example: Raj Mukut">
                    </div>

                    <div>
                        <label class="v-label">Email *</label>
                        <input class="v-input" name="email" value="{{ old('email') }}" placeholder="example@gmail.com" required>
                    </div>

                    <div>
                        <label class="v-label">Phone</label>
                        <input class="v-input" name="phone" value="{{ old('phone') }}" placeholder="017xxxxxxxx">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="v-label">Student ID</label>
                            <input class="v-input" name="student_id" value="{{ old('student_id') }}" placeholder="Student ID">
                        </div>

                        <div>
                            <label class="v-label">Alumni ID</label>
                            <input class="v-input" name="alumni_id" value="{{ old('alumni_id') }}" placeholder="Alumni ID">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="v-label">Role *</label>
                            <input class="v-input" name="role" value="{{ old('role', 'student') }}" placeholder="student / alumni" required>
                        </div>

                        <div>
                            <label class="v-label">Status</label>
                            <input class="v-input" name="status" value="{{ old('status', 'active') }}" placeholder="active">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="v-label">Department</label>
                            <input class="v-input" name="department" value="{{ old('department') }}" placeholder="cse">
                        </div>

                        <div>
                            <label class="v-label">Batch</label>
                            <input class="v-input" name="batch" value="{{ old('batch') }}" placeholder="56">
                        </div>
                    </div>

                    <button class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 py-4 text-white font-black shadow-xl hover:scale-[1.02] transition">
                        <i class="fas fa-wand-magic-sparkles mr-2"></i>
                        Save Formatting
                    </button>
                </form>
            </div>

            <div class="xl:col-span-2 space-y-8">

                {{-- CSV Upload --}}
                <div class="v-card p-8">
                    <h3 class="text-2xl font-black text-white">
                        Upload Verified Users CSV
                    </h3>

                    <p class="mt-2 text-slate-400">
                        Required columns: student_id, alumni_id, name, email, department, batch, role, status
                    </p>

                    <form method="POST"
                          action="{{ route('admin.verified-users.import') }}"
                          enctype="multipart/form-data"
                          class="mt-6 flex flex-col md:flex-row gap-4">
                        @csrf

                        <input type="file"
                               name="file"
                               accept=".csv,.txt"
                               required
                               class="flex-1 rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white">

                        <button class="rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 px-8 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                            <i class="fas fa-upload mr-2"></i>
                            Import CSV
                        </button>
                    </form>
                </div>

                {{-- Verified Users List --}}
                <div class="v-card p-8 overflow-x-auto">
                    <h3 class="text-2xl font-black text-white mb-6">
                        Verified Users List
                    </h3>

                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/10 text-slate-400">
                                <th class="py-4 px-3">Name</th>
                                <th class="py-4 px-3">Email</th>
                                <th class="py-4 px-3">Official ID</th>
                                <th class="py-4 px-3">Department</th>
                                <th class="py-4 px-3">Batch</th>
                                <th class="py-4 px-3">Role</th>
                                <th class="py-4 px-3">Status</th>
                                <th class="py-4 px-3 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($verifiedUsers as $verifiedUser)
                                <tr class="border-b border-white/5">
                                    <td class="py-4 px-3 font-black text-white">
                                        {{ $verifiedUser->name ?? 'N/A' }}
                                    </td>

                                    <td class="py-4 px-3 text-slate-400">
                                        {{ $verifiedUser->email ?? 'N/A' }}
                                    </td>

                                    <td class="py-4 px-3 text-slate-400">
                                        {{ $verifiedUser->student_id ?? $verifiedUser->alumni_id ?? $verifiedUser->unique_id ?? 'N/A' }}
                                    </td>

                                    <td class="py-4 px-3 text-slate-400">
                                        {{ $verifiedUser->department ?? 'N/A' }}
                                    </td>

                                    <td class="py-4 px-3 text-slate-400">
                                        {{ $verifiedUser->batch ?? 'N/A' }}
                                    </td>

                                    <td class="py-4 px-3">
                                        <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-400 text-xs font-black">
                                            {{ ucfirst($verifiedUser->role ?? 'user') }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-3">
                                        <span class="px-3 py-1 rounded-full {{ ($verifiedUser->status ?? 'active') === 'active' ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400' }} text-xs font-black">
                                            {{ ucfirst($verifiedUser->status ?? 'active') }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-3 text-right">
                                        <form method="POST"
                                              action="{{ route('admin.verified-users.destroy', $verifiedUser) }}"
                                              onsubmit="return confirm('Delete this verified user?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="rounded-xl bg-red-500/15 px-4 py-2 text-red-400 font-bold">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-10 text-center text-slate-400 font-bold">
                                        No verified users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $verifiedUsers->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('components.verified-db-style')
</x-app-layout>