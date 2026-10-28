<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.25),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        Student Profile
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-3xl">
                        Build your academic and career profile for mentorship,
                        networking and future opportunities.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-7 py-5 text-center">
                    <p class="text-xs text-slate-300">
                        Profile Completion
                    </p>

                    <p class="uc-header-context mt-1 text-4xl font-black text-emerald-300">
                        {{ $profileScore ?? 0 }}%
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Student Career Profile
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Success --}}
        @if(session('status') === 'profile-updated')
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-emerald-600 dark:text-emerald-300 font-bold">
                Profile updated successfully.
            </div>
        @endif

        {{-- Validation --}}
        @if($errors->any())
            <div class="rounded-2xl bg-red-500/10 border border-red-500/30 p-5">
                <h4 class="font-black text-red-500">
                    Please correct the following errors:
                </h4>

                <ul class="mt-3 list-disc list-inside text-sm text-red-500 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Left --}}
            <div class="space-y-8">

                {{-- Profile Card --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                    <div class="text-center">

                        <x-profile-photo :user="$user" />

                        <h3 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                            {{ $user->name }}
                        </h3>

                        <p class="mt-1 font-bold text-cyan-500">
                            Student
                        </p>

                        <p class="mt-2 text-sm text-slate-500">
                            {{ $user->department ?: 'Department not specified' }}
                        </p>

                        <div class="mt-6 h-3 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            <div
                                class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full"
                                style="width: {{ min(100, max(0, $profileScore ?? 0)) }}%"
                            ></div>
                        </div>
                    </div>
                </div>

                {{-- Account Information --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-xl">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                        Account Information
                    </h3>

                    <div class="mt-5 space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">
                                Email
                            </p>

                            <p class="mt-1 font-bold text-slate-900 dark:text-white break-all">
                                {{ $user->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">
                                Official ID
                            </p>

                            <p class="mt-1 font-bold text-slate-900 dark:text-white">
                                {{ $user->official_id ?: ($user->student_id ?: 'Not available') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">
                                Role
                            </p>

                            <p class="mt-1 font-bold text-cyan-500">
                                Student
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Suggestions --}}
                @if(!empty($profileSuggestions))
                    <div class="rounded-3xl bg-slate-950 border border-white/10 p-6 shadow-2xl">
                        <p class="text-xs uppercase tracking-[0.25em] text-cyan-300 font-black">
                            Profile Suggestions
                        </p>

                        <div class="mt-5 space-y-3">
                            @foreach($profileSuggestions as $suggestion)
                                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                    <p class="text-sm text-slate-300 leading-relaxed">
                                        {{ $suggestion }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Main --}}
            <div class="xl:col-span-2">

                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                    enctype="multipart/form-data"
                    class="space-y-8"
                >
                    @csrf
                    @method('PATCH')

                    {{-- Personal Information --}}
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                        <div class="mb-7">
                            <p class="text-sm uppercase tracking-[0.25em] text-blue-500 font-black">
                                Personal Information
                            </p>

                            <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                                Basic Details
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    value="{{ $user->name }}"
                                    disabled
                                    aria-label="Full Name (managed by admin)"
                                    class="mt-2 w-full rounded-2xl border-slate-300 bg-slate-100 dark:border-white/10 dark:bg-slate-800 dark:text-slate-400"
                                >
                                <p class="mt-2 text-xs text-slate-500">Name, email and university ID can only be changed by an administrator.</p>

                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    value="{{ $user->email }}"
                                    disabled
                                    class="mt-2 w-full rounded-2xl border-slate-300 bg-slate-100 dark:border-white/10 dark:bg-slate-800 dark:text-slate-400"
                                >
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="+8801XXXXXXXXX"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >

                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Address
                                </label>

                                <input
                                    type="text"
                                    name="address"
                                    value="{{ old('address', $user->address) }}"
                                    placeholder="Dhaka, Bangladesh"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >

                                @error('address')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Profile Image
                                </label>

                                <input
                                    type="file"
                                    name="profile_image"
                                    accept=".jpg,.jpeg,.png,.webp"
                                    class="mt-2 w-full rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white"
                                >

                                <p class="mt-2 text-xs text-slate-500">
                                    JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                </p>

                                @error('profile_image')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Academic --}}
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                        <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">
                            Academic Profile
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                            University Information
                        </h3>

                        <div class="mt-7 grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Department
                                </label>

                                <input
                                    type="text"
                                    name="department"
                                    value="{{ old('department', $user->department) }}"
                                    placeholder="Example: CSE"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >

                                @error('department')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Batch
                                </label>

                                <input
                                    type="text"
                                    name="batch"
                                    value="{{ old('batch', $user->batch) }}"
                                    placeholder="Example: 56th Batch"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >

                                @error('batch')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Career --}}
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                        <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                            Career Development
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                            Skills & Career Goal
                        </h3>

                        <div class="mt-7 space-y-6">

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Skills
                                </label>

                                <input
                                    type="text"
                                    name="skills"
                                    value="{{ old('skills', $user->skills) }}"
                                    placeholder="Laravel, PHP, JavaScript, React, Communication"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >

                                <p class="mt-2 text-xs text-slate-500">
                                    Separate each skill using a comma.
                                </p>

                                @error('skills')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Career Goal / Bio
                                </label>

                                <textarea
                                    name="bio"
                                    rows="6"
                                    placeholder="Write about your academic interests, goals, career plan and the areas you want to develop..."
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >{{ old('bio', $user->bio) }}</textarea>

                                @error('bio')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Links --}}
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                        <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">
                            Online Presence
                        </p>

                        <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                            Professional Links
                        </h3>

                        <div class="mt-7 space-y-6">

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    GitHub
                                </label>

                                <input
                                    type="url"
                                    name="github_url"
                                    value="{{ old('github_url', $user->github_url) }}"
                                    placeholder="https://github.com/username"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    LinkedIn
                                </label>

                                <input
                                    type="url"
                                    name="linkedin_url"
                                    value="{{ old('linkedin_url', $user->linkedin_url) }}"
                                    placeholder="https://linkedin.com/in/username"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Portfolio / Website
                                </label>

                                <input
                                    type="url"
                                    name="portfolio_url"
                                    value="{{ old('portfolio_url', $user->portfolio_url) }}"
                                    placeholder="https://yourwebsite.com"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            type="submit"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-black shadow-xl hover:scale-[1.02] transition"
                        >
                            Save Student Profile
                        </button>

                        <a
                            href="{{ route('dashboard') }}"
                            class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black text-center shadow-xl"
                        >
                            Back to Dashboard
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
