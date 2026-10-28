<x-app-layout>
    <x-slot name="header">
        @php
            $role = auth()->user()->role;
            $isAdmin = in_array($role, ['admin', 'super_admin']);
            $isStudentOrAlumni = in_array($role, ['student', 'alumni']);
        @endphp

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.30),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        @if($isAdmin)
                            Manage your administrator profile and platform access information.
                        @elseif($role === 'alumni')
                            Build your professional alumni profile for mentoring, networking and opportunity sharing.
                        @else
                            Build a complete academic and career profile to unlock better job, mentorship and networking opportunities.
                        @endif
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-5 text-center">
                    <p class="text-xs text-slate-300">Profile Status</p>

                    <p class="uc-header-context text-4xl font-black text-emerald-300">
                        {{ $profileScore ?? 0 }}%
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        @if($isAdmin)
                            Administrator Profile
                        @elseif($role === 'alumni')
                            Professional Impact Profile
                        @else
                            Student Career Profile
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if (session('status') === 'profile-updated')
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-600 font-bold">
                Profile updated successfully.
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Left Sidebar --}}
            <div class="space-y-8">

                {{-- Profile Card --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                    <div class="text-center">
                        <x-profile-photo :user="$user" />

                        <h3 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                            {{ $user->name }}
                        </h3>

                        <p class="text-slate-500 mt-1">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </p>

                        <div class="mt-5 h-3 w-full rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"
                                 style="width: {{ $profileScore ?? 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Suggestions --}}
                @if(!$isAdmin)
                    <div class="rounded-[2rem] border border-white/15 bg-white/10 backdrop-blur-2xl p-7 shadow-2xl">
    <p class="text-sm uppercase tracking-[0.35em] text-cyan-400 font-black">
        Profile Suggestions
    </p>

    <h3 class="mt-3 text-3xl font-black text-white">
        Profile Improvement
    </h3>

    @php
        $score = $profileStrength['score'] ?? 0;
        $level = $profileStrength['level'] ?? 'Improve';
        $message = $profileStrength['message'] ?? 'Complete your profile to improve profile completeness.';
        $missingItems = $profileStrength['missing'] ?? [];
        $completedItems = $profileStrength['completed'] ?? [];
    @endphp

    <div class="mt-6 rounded-3xl bg-slate-950/70 border border-white/10 p-5">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-slate-400">Profile Completion Score</p>
                <h4 class="text-4xl font-black text-white mt-1">
                    {{ $score }}%
                </h4>
                <p class="text-sm text-cyan-300 font-bold mt-1">
                    {{ $level }}
                </p>
            </div>

            <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-purple-600 flex items-center justify-center shadow-xl">
                <i class="fas fa-robot text-white text-2xl"></i>
            </div>
        </div>

        <div class="mt-5 h-3 rounded-full bg-white/10 overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500"
                 style="width: {{ $score }}%">
            </div>
        </div>

        <p class="mt-4 text-sm text-slate-300 leading-relaxed">
            {{ $message }}
        </p>
    </div>

    <div class="mt-6 space-y-3">
        @forelse($missingItems as $item)
            <div class="rounded-2xl bg-cyan-500/10 border border-cyan-400/20 p-4 flex items-start gap-3">
                <div class="h-9 w-9 rounded-xl bg-cyan-400/15 flex items-center justify-center shrink-0">
                    <i class="fas fa-wand-magic-sparkles text-cyan-300"></i>
                </div>

                <div>
                    <p class="font-black text-white">
                        Add {{ $item }}
                    </p>
                    <p class="text-sm text-slate-400 mt-1">
                        This information is missing. Adding it will improve your profile strength and recommendation quality.
                    </p>
                </div>
            </div>
        @empty
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/20 p-5 text-center">
                <i class="fas fa-circle-check text-emerald-300 text-2xl"></i>
                <h4 class="mt-3 text-white font-black">
                    Excellent Profile
                </h4>
                <p class="mt-2 text-sm text-slate-300">
                    Your important profile fields are completed.
                </p>
            </div>
        @endforelse
    </div>

    @if(!empty($completedItems))
        <div class="mt-6 rounded-2xl bg-white/5 border border-white/10 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-300 font-black mb-3">
                Completed
            </p>

            <div class="flex flex-wrap gap-2">
                @foreach($completedItems as $item)
                    <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 text-xs font-bold">
                        {{ $item }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</div>
                @endif

                {{-- Role Card --}}
                <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-7 shadow-2xl text-white">
                    <p class="text-sm uppercase tracking-[0.25em] text-purple-300 font-black">
                        Role Profile
                    </p>

                    <h3 class="mt-2 text-2xl font-black">
                        @if($role === 'super_admin')
                            Super Administrator
                        @elseif($role === 'admin')
                            Administrator
                        @elseif($role === 'alumni')
                            Alumni Professional
                        @else
                            Student Career Builder
                        @endif
                    </h3>

                    <p class="mt-3 text-slate-300 text-sm leading-relaxed">
                        @if($role === 'super_admin')
                            Create admins, manage users, verify accounts, moderate posts and control the full platform.
                        @elseif($role === 'admin')
                            Manage users, verify accounts, moderate content and support the university community.
                        @elseif($role === 'alumni')
                            Share your professional experience, post opportunities and guide students through mentorship.
                        @else
                            Complete your academic profile, skills and career goal to get better job and mentorship recommendations.
                        @endif
                    </p>
                </div>

            </div>

            {{-- Main Profile Form --}}
            <div class="xl:col-span-2 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                <div class="mb-8">
                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                        Edit Information
                    </p>

                    <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        @if($isAdmin)
                            Administrator Details
                        @else
                            Personal & Career Details
                        @endif
                    </h3>

                    <p class="mt-2 text-slate-500">
                        @if($isAdmin)
                            Update your admin profile information.
                        @else
                            Update your profile information for better networking and profile completeness.
                        @endif
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Common Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Email</label>
                            <input type="email"
                                   value="{{ $user->email }}"
                                   disabled
                                   class="mt-2 w-full rounded-2xl border-slate-300 bg-slate-100 dark:border-white/10 dark:bg-slate-800 dark:text-slate-400">
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Phone</label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="Example: +8801XXXXXXXXX"
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Address</label>
                            <input type="text"
                                   name="address"
                                   value="{{ old('address', $user->address) }}"
                                   placeholder="Example: Dhaka, Bangladesh"
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Profile Image</label>
                            <input type="file"
                                   name="profile_image"
                                   accept="image/*"
                                   class="mt-2 w-full rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white">
                            @error('profile_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Student / Alumni Fields --}}
                    @if($isStudentOrAlumni)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">Department</label>
                                <input type="text"
                                       name="department"
                                       value="{{ old('department', $user->department) }}"
                                       placeholder="Example: CSE"
                                       class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                @error('department')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ $role === 'alumni' ? 'Passing Year / Batch' : 'Batch' }}
                                </label>
                                <input type="text"
                                       name="batch"
                                       value="{{ old('batch', $user->batch) }}"
                                       placeholder="Example: 2025 / 56th Batch"
                                       class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                @error('batch')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Skills</label>
                            <input type="text"
                                   name="skills"
                                   value="{{ old('skills', $user->skills) }}"
                                   placeholder="Example: Laravel, PHP, JavaScript, MySQL, Communication"
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            <p class="text-xs text-slate-500 mt-2">Separate skills with comma.</p>
                            @error('skills')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-xs font-black text-slate-300 mb-2">
            GitHub Profile
        </label>
        <input type="url"
               name="github_url"
               value="{{ old('github_url', auth()->user()->github_url) }}"
               placeholder="https://github.com/username"
               class="w-full rounded-2xl border-white/10 bg-slate-950 text-white">
    </div>

    <div>
        <label class="block text-xs font-black text-slate-300 mb-2">
            LinkedIn Profile
        </label>
        <input type="url"
               name="linkedin_url"
               value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}"
               placeholder="https://linkedin.com/in/username"
               class="w-full rounded-2xl border-white/10 bg-slate-950 text-white">
    </div>

    <div>
        <label class="block text-xs font-black text-slate-300 mb-2">
            Portfolio / Website
        </label>
        <input type="url"
               name="portfolio_url"
               value="{{ old('portfolio_url', auth()->user()->portfolio_url) }}"
               placeholder="https://yourwebsite.com"
               class="w-full rounded-2xl border-white/10 bg-slate-950 text-white">
    </div>
</div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                {{ $role === 'alumni' ? 'Professional Bio' : 'Career Goal / Bio' }}
                            </label>
                            <textarea name="bio"
                                      rows="5"
                                      placeholder="{{ $role === 'alumni' ? 'Write about your professional experience and mentorship interest...' : 'Write about your academic goal and career plan...' }}"
                                      class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Work Experience Section --}}
<div class="rounded-3xl bg-gradient-to-br from-emerald-500/10 via-cyan-500/10 to-blue-500/10 border border-emerald-500/20 p-6 shadow-xl">
    <div class="mb-6">
        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
            Work Experience
        </p>

        <h4 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
            Current & Previous Job Details
        </h4>

        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Add your current workplace, designation and previous job experience for better networking and mentor matching.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="font-bold text-slate-700 dark:text-slate-300">
                Current Company / Workplace
            </label>
            <input type="text"
                   name="current_company"
                   value="{{ old('current_company', $user->current_company) }}"
                   placeholder="Example: Google, Brain Station 23, University Lab"
                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
            @error('current_company')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="font-bold text-slate-700 dark:text-slate-300">
                Current Designation
            </label>
            <input type="text"
                   name="current_designation"
                   value="{{ old('current_designation', $user->current_designation) }}"
                   placeholder="Example: Software Engineer, Intern, Lecturer"
                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
            @error('current_designation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="font-bold text-slate-700 dark:text-slate-300">
                Job Type
            </label>
            <select name="current_job_type"
                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                <option value="">Select Job Type</option>
                <option value="Full-time" {{ old('current_job_type', $user->current_job_type) === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                <option value="Part-time" {{ old('current_job_type', $user->current_job_type) === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                <option value="Internship" {{ old('current_job_type', $user->current_job_type) === 'Internship' ? 'selected' : '' }}>Internship</option>
                <option value="Freelance" {{ old('current_job_type', $user->current_job_type) === 'Freelance' ? 'selected' : '' }}>Freelance</option>
                <option value="Remote" {{ old('current_job_type', $user->current_job_type) === 'Remote' ? 'selected' : '' }}>Remote</option>
                <option value="Not Working Yet" {{ old('current_job_type', $user->current_job_type) === 'Not Working Yet' ? 'selected' : '' }}>Not Working Yet</option>
            </select>
            @error('current_job_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="font-bold text-slate-700 dark:text-slate-300">
                Total Experience
            </label>
            <input type="text"
                   name="work_experience_years"
                   value="{{ old('work_experience_years', $user->work_experience_years) }}"
                   placeholder="Example: 2 years, 6 months, Fresher"
                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
            @error('work_experience_years')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="font-bold text-slate-700 dark:text-slate-300">
                Previous Company
            </label>
            <input type="text"
                   name="previous_company"
                   value="{{ old('previous_company', $user->previous_company) }}"
                   placeholder="Example: Previous company name"
                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
            @error('previous_company')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="font-bold text-slate-700 dark:text-slate-300">
                Previous Designation
            </label>
            <input type="text"
                   name="previous_designation"
                   value="{{ old('previous_designation', $user->previous_designation) }}"
                   placeholder="Example: Junior Developer, Teaching Assistant"
                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
            @error('previous_designation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6">
        <label class="font-bold text-slate-700 dark:text-slate-300">
            Previous Job / Experience Details
        </label>
        <textarea name="previous_job_details"
                  rows="4"
                  placeholder="Write about your previous job, internship, project, responsibility, achievement..."
                  class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">{{ old('previous_job_details', $user->previous_job_details) }}</textarea>
        @error('previous_job_details')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

                        @if($role === 'alumni')
                            <div class="rounded-3xl bg-purple-500/10 border border-purple-500/20 p-6">
                                <h4 class="text-xl font-black text-purple-600">
                                    Alumni Professional Profile
                                </h4>
                                <p class="text-sm text-slate-500 mt-2">
                                    Add professional experience, company name and expertise to help students find better mentors.
                                </p>
                            </div>
                        @else
                            <div class="rounded-3xl bg-blue-500/10 border border-blue-500/20 p-6">
                                <h4 class="text-xl font-black text-blue-600">
                                    Student Career Profile
                                </h4>
                                <p class="text-sm text-slate-500 mt-2">
                                    Add skills, department, batch and career goal to get better job and mentorship opportunities.
                                </p>
                            </div>
                        @endif
                    @endif

                    {{-- Admin / Super Admin Fields --}}
                    @if($isAdmin)
                        @if($role === 'super_admin')
                            <div class="rounded-3xl bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 border border-emerald-500/20 p-6 shadow-xl">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                                            Super Admin Control
                                        </p>

                                        <h4 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                                            Add Another Admin
                                        </h4>

                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                            Create and manage additional administrator accounts.
                                        </p>
                                    </div>

                                    <a href="{{ route('superadmin.admins.create') }}"
                                       class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-black shadow-xl hover:scale-105 transition duration-300">
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Add Another Admin
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="rounded-3xl bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/20 p-6 shadow-xl">
                            <p class="text-sm uppercase tracking-[0.25em] text-amber-500 font-black">
                                Administrator Profile
                            </p>

                            <h4 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                                University Management Console
                            </h4>

                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                Manage users, verify accounts, moderate posts, publish announcements,
                                monitor donations, oversee alumni mentoring and control the university ecosystem.
                            </p>
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex flex-col md:flex-row gap-4 pt-4">
                        <button type="submit"
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black shadow-xl hover:scale-105 transition">
                            <i class="fas fa-save mr-2"></i>
                            Save Profile
                        </button>

                        <a href="{{ route('dashboard') }}"
                           class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black shadow-xl hover:scale-105 transition text-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>