<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-indigo-950 p-6 lg:p-7 shadow-xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(59,130,246,.25),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-purple-300 font-black">
                        Alumni Professional Profile
                    </p>

                    <h2 class="mt-3 text-3xl lg:text-4xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-3xl">
                        Build a complete professional history for networking,
                        mentorship and career opportunities.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-4 text-center">
                    <p class="text-xs text-slate-300">
                        Profile Completion
                    </p>

                    <p class="uc-header-context mt-1 text-3xl font-black text-emerald-300">
                        {{ $profileScore ?? 0 }}%
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Alumni Professional Profile
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Status --}}
        @if(session('status') === 'profile-updated')
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-emerald-600 dark:text-emerald-300 font-bold">
                Profile updated successfully.
            </div>
        @endif

        @if(session('status') === 'experience-added')
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-emerald-600 dark:text-emerald-300 font-bold">
                Work experience added successfully.
            </div>
        @endif

        @if(session('status') === 'experience-updated')
            <div class="rounded-2xl bg-blue-500/10 border border-blue-500/30 p-4 text-blue-600 dark:text-blue-300 font-bold">
                Work experience updated successfully.
            </div>
        @endif

        @if(session('status') === 'experience-deleted')
            <div class="rounded-2xl bg-amber-500/10 border border-amber-500/30 p-4 text-amber-600 dark:text-amber-300 font-bold">
                Work experience removed successfully.
            </div>
        @endif

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

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

            {{-- Left --}}
            <div class="xl:col-span-4 space-y-5 xl:sticky xl:top-6">

                {{-- Profile Card --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                    <div class="text-center">

                        <x-profile-photo :user="$user" />

                        <h3 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                            {{ $user->name }}
                        </h3>

                        <p class="mt-1 font-bold text-purple-500">
                            {{ $user->current_designation ?: 'Position not added' }}
                        </p>

                        <p class="mt-2 text-sm text-slate-500">
                            {{ $user->current_company ?: 'Add your professional experience' }}
                        </p>

                        <div class="mt-6 h-3 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-purple-500 to-indigo-500"
                                style="width: {{ min(100, max(0, $profileScore ?? 0)) }}%"
                            ></div>
                        </div>
                    </div>
                </div>

                {{-- Alumni Identity --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-xl">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                        Alumni Information
                    </h3>

                    <div class="mt-5 space-y-4">

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">
                                Alumni ID
                            </p>

                            <p class="mt-1 font-black text-purple-500">
                                {{ $user->official_id ?: ($user->alumni_id ?: 'Not available') }}
                            </p>
                        </div>

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
                                Department
                            </p>

                            <p class="mt-1 font-bold text-slate-900 dark:text-white">
                                {{ $user->department ?: 'Not specified' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">
                                Total Experience
                            </p>

                            <p class="mt-1 font-bold text-slate-900 dark:text-white">
                                {{ $user->work_experience_years ?? 'Not added yet' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Suggestions --}}
                @if(!empty($profileSuggestions))
                    <div class="rounded-3xl bg-slate-950 border border-white/10 p-6 shadow-2xl">
                        <p class="text-xs uppercase tracking-[0.25em] text-purple-300 font-black">
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
            <div class="xl:col-span-8 space-y-6">

                <div class="rounded-2xl bg-white/80 dark:bg-slate-900/90 border border-slate-200 dark:border-white/10 p-3 shadow-lg backdrop-blur">
                    <div class="flex flex-wrap gap-2 text-sm font-bold">
                        <a href="#alumni-basic" class="px-4 py-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-300">Basic</a>
                        <a href="#alumni-academic" class="px-4 py-2 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-300">Academic</a>
                        <a href="#alumni-professional" class="px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-300">Professional</a>
                        <a href="#alumni-links" class="px-4 py-2 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-300">Links</a>
                        <a href="#alumni-experience" class="px-4 py-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-300">Experience</a>
                    </div>
                </div>

                {{-- Main Alumni Profile Form --}}
                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    @csrf
                    @method('PATCH')

                    {{-- Personal --}}
                    <div id="alumni-basic" class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 lg:p-7 shadow-xl">

                        <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">
                            Personal Information
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                            Basic Details
                        </h3>

                        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">

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
                            </div>
                        </div>
                    </div>

                    {{-- Academic --}}
                    <div id="alumni-academic" class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 lg:p-7 shadow-xl">

                        <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                            University Background
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                            Academic Information
                        </h3>

                        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">

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
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Batch / Passing Year
                                </label>

                                <input
                                    type="text"
                                    name="batch"
                                    value="{{ old('batch', $user->batch) }}"
                                    placeholder="Example: 56th Batch / 2025"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Professional Summary --}}
                    <div id="alumni-professional" class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 lg:p-7 shadow-xl">

                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                            Professional Profile
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                            Expertise & Professional Bio
                        </h3>

                        <div class="mt-5 space-y-5">

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Skills & Expertise
                                </label>

                                <input
                                    type="text"
                                    name="skills"
                                    value="{{ old('skills', $user->skills) }}"
                                    placeholder="Laravel, Software Engineering, Leadership, Project Management"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >

                                <p class="mt-2 text-xs text-slate-500">
                                    Separate each skill with a comma.
                                </p>
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Professional Bio
                                </label>

                                <textarea
                                    name="bio"
                                    rows="6"
                                    placeholder="Write about your professional background, expertise, achievements, industry experience and mentorship interests..."
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Professional Links --}}
                    <div id="alumni-links" class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 lg:p-7 shadow-xl">

                        <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">
                            Professional Presence
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                            Social & Portfolio Links
                        </h3>

                        <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-5">

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

                    <section class="my-5 rounded-2xl border border-white/10 p-5">
    <input type="hidden" name="requires_message_request" value="0">
    <label class="flex items-center gap-3 font-bold"><input type="checkbox" name="requires_message_request" value="1" @checked((bool) old('requires_message_request', $user->requires_message_request ?? true))>Require request before messaging</label>
    <p class="mt-2 text-sm text-slate-400">New contacts must have an accepted request when enabled. Existing conversations remain available.</p>
</section>
@include('profile.partials.professional-summary')

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            type="submit"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-black shadow-xl hover:scale-[1.02] transition"
                        >
                            Save Alumni Profile
                        </button>

                        <a
                            href="{{ route('dashboard') }}"
                            class="px-8 py-4 rounded-2xl bg-slate-950 text-white text-center font-black shadow-xl"
                        >
                            Back to Dashboard
                        </a>
                    </div>
                </form>

                {{-- Work Experience Heading --}}
                <div id="alumni-experience" class="rounded-3xl bg-gradient-to-br from-slate-950 via-purple-950 to-indigo-950 border border-white/10 p-6 lg:p-7 shadow-xl text-white">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-300 font-black">
                                Career History
                            </p>

                            <h3 class="mt-2 text-3xl font-black">
                                Work Experience
                            </h3>

                            <p class="mt-3 text-slate-300 max-w-2xl">
                                Add every important position you have held.
                                Your current workplace and total experience will
                                be calculated automatically.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                            <p class="text-xs text-slate-400">
                                Total Experience
                            </p>

                            <p class="mt-1 text-xl font-black text-emerald-300">
                                {{ $user->work_experience_years ?? 'Not added yet' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Existing Experiences --}}
                @forelse($workExperiences as $experience)

                    <div
                        x-data="{ editing: false }"
                        class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden"
                    >

                        {{-- View --}}
                        <div class="p-6 lg:p-7">

                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                                <div class="flex-1">

                                    <div class="flex flex-wrap items-center gap-3">

                                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                            {{ $experience->designation }}
                                        </h3>

                                        @if($experience->is_current)
                                            <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-300 text-xs font-black">
                                                Current
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-2 text-lg font-bold text-purple-600 dark:text-purple-300">
                                        {{ $experience->company_name }}
                                    </p>

                                    <div class="mt-4 flex flex-wrap gap-2">

                                        @if($experience->employment_type)
                                            <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-300 text-xs font-bold">
                                                {{ $experience->employment_type }}
                                            </span>
                                        @endif

                                        @if($experience->work_mode)
                                            <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-300 text-xs font-bold">
                                                {{ $experience->work_mode }}
                                            </span>
                                        @endif

                                        @if($experience->location)
                                            <span class="px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-600 dark:text-cyan-300 text-xs font-bold">
                                                {{ $experience->location }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-4 text-sm font-bold text-slate-500">
                                        {{ $experience->start_date?->format('M Y') ?: 'Unknown' }}

                                        —

                                        @if($experience->is_current)
                                            Present
                                        @else
                                            {{ $experience->end_date?->format('M Y') ?: 'Not specified' }}
                                        @endif
                                    </p>

                                    @if($experience->description)
                                        <p class="mt-5 text-slate-600 dark:text-slate-300 whitespace-pre-line leading-relaxed">
                                            {{ $experience->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-3">

                                    <button
                                        type="button"
                                        @click="editing = !editing"
                                        class="px-5 py-3 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-300 font-black"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        method="POST"
                                        action="{{ route('profile.work-experiences.destroy', $experience) }}"
                                        onsubmit="return confirm('Are you sure you want to remove this work experience?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-5 py-3 rounded-xl bg-red-500/10 text-red-600 dark:text-red-300 font-black"
                                        >
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>

                        {{-- Edit --}}
                        <div
                            x-show="editing"
                            x-cloak
                            class="border-t border-slate-200 dark:border-white/10 p-6 lg:p-7 bg-slate-50 dark:bg-slate-950/60"
                        >

                            <form
                                method="POST"
                                action="{{ route('profile.work-experiences.update', $experience) }}"
                                class="space-y-6"
                            >
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            Company Name
                                        </label>

                                        <input
                                            type="text"
                                            name="company_name"
                                            value="{{ $experience->company_name }}"
                                            required
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            Designation
                                        </label>

                                        <input
                                            type="text"
                                            name="designation"
                                            value="{{ $experience->designation }}"
                                            required
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            Employment Type
                                        </label>

                                        <select
                                            name="employment_type"
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                            <option value="">Select Type</option>

                                            @foreach([
                                                'Full-time',
                                                'Part-time',
                                                'Contract',
                                                'Internship',
                                                'Freelance',
                                                'Self-employed',
                                                'Volunteer',
                                                'Other'
                                            ] as $type)
                                                <option
                                                    value="{{ $type }}"
                                                    {{ $experience->employment_type === $type ? 'selected' : '' }}
                                                >
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            Work Mode
                                        </label>

                                        <select
                                            name="work_mode"
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                            <option value="">Select Work Mode</option>

                                            @foreach(['On-site', 'Hybrid', 'Remote'] as $mode)
                                                <option
                                                    value="{{ $mode }}"
                                                    {{ $experience->work_mode === $mode ? 'selected' : '' }}
                                                >
                                                    {{ $mode }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            Location
                                        </label>

                                        <input
                                            type="text"
                                            name="location"
                                            value="{{ $experience->location }}"
                                            placeholder="Dhaka, Bangladesh"
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            Start Date
                                        </label>

                                        <input
                                            type="date"
                                            name="start_date"
                                            value="{{ $experience->start_date?->format('Y-m-d') }}"
                                            required
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="font-bold text-slate-700 dark:text-slate-300">
                                            End Date
                                        </label>

                                        <input
                                            type="date"
                                            name="end_date"
                                            value="{{ $experience->end_date?->format('Y-m-d') }}"
                                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                        >
                                    </div>

                                    <div class="flex items-center">
                                        <label class="flex items-center gap-3 cursor-pointer mt-7">
                                            <input
                                                type="checkbox"
                                                name="is_current"
                                                value="1"
                                                {{ $experience->is_current ? 'checked' : '' }}
                                                class="rounded border-slate-300 text-purple-600"
                                            >

                                            <span class="font-bold text-slate-700 dark:text-slate-300">
                                                I currently work here
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="font-bold text-slate-700 dark:text-slate-300">
                                        Description
                                    </label>

                                    <textarea
                                        name="description"
                                        rows="5"
                                        placeholder="Responsibilities, achievements, projects or important experience..."
                                        class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                    >{{ $experience->description }}</textarea>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button
                                        type="submit"
                                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black"
                                    >
                                        Update Experience
                                    </button>

                                    <button
                                        type="button"
                                        @click="editing = false"
                                        class="px-6 py-3 rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-white font-black"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                @empty

                    <div class="rounded-3xl border-2 border-dashed border-slate-300 dark:border-white/10 bg-white dark:bg-slate-900 p-10 text-center">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                            No Work Experience Added Yet
                        </h3>

                        <p class="mt-3 text-slate-500 max-w-xl mx-auto">
                            Add your current or previous jobs, internships,
                            freelance work or other professional experience.
                        </p>
                    </div>

                @endforelse

                {{-- Add New Experience --}}
                <div
                    x-data="{
                        current: {{ old('is_current') ? 'true' : 'false' }},
                        open: {{ ($errors->has('company_name') || $errors->has('designation') || $errors->has('start_date') || old('company_name')) ? 'true' : 'false' }}
                    }"
                    class="rounded-3xl bg-white dark:bg-slate-900 border border-purple-500/20 p-6 lg:p-7 shadow-xl"
                >
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">
                                Add Experience
                            </p>

                            <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                                Add a New Position
                            </h3>

                            <p class="mt-2 text-sm text-slate-500">
                                Add a current role, previous job, internship or freelance experience.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="open = !open"
                            class="shrink-0 px-5 py-3 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-600 dark:text-purple-300 font-black"
                        >
                            <span x-text="open ? 'Hide Form' : '+ Add Position'"></span>
                        </button>
                    </div>

                    <form
                        x-show="open"
                        x-cloak
                        method="POST"
                        action="{{ route('profile.work-experiences.store') }}"
                        class="mt-8 space-y-6"
                    >
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Company / Organization *
                                </label>

                                <input
                                    type="text"
                                    name="company_name"
                                    value="{{ old('company_name') }}"
                                    placeholder="Example: Brain Station 23"
                                    required
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Designation *
                                </label>

                                <input
                                    type="text"
                                    name="designation"
                                    value="{{ old('designation') }}"
                                    placeholder="Example: Software Engineer"
                                    required
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Employment Type
                                </label>

                                <select
                                    name="employment_type"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                                    <option value="">Select Type</option>

                                    @foreach([
                                        'Full-time',
                                        'Part-time',
                                        'Contract',
                                        'Internship',
                                        'Freelance',
                                        'Self-employed',
                                        'Volunteer',
                                        'Other'
                                    ] as $type)

                                        <option
                                            value="{{ $type }}"
                                            {{ old('employment_type') === $type ? 'selected' : '' }}
                                        >
                                            {{ $type }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Work Mode
                                </label>

                                <select
                                    name="work_mode"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                                    <option value="">Select Work Mode</option>

                                    @foreach(['On-site', 'Hybrid', 'Remote'] as $mode)

                                        <option
                                            value="{{ $mode }}"
                                            {{ old('work_mode') === $mode ? 'selected' : '' }}
                                        >
                                            {{ $mode }}
                                        </option>

                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Location
                                </label>

                                <input
                                    type="text"
                                    name="location"
                                    value="{{ old('location') }}"
                                    placeholder="Dhaka, Bangladesh"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    Start Date *
                                </label>

                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ old('start_date') }}"
                                    required
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div x-show="!current">
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    End Date
                                </label>

                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ old('end_date') }}"
                                    class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                                >
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center gap-3 cursor-pointer mt-7">
                                    <input
                                        type="checkbox"
                                        name="is_current"
                                        value="1"
                                        x-model="current"
                                        class="rounded border-slate-300 text-purple-600"
                                    >

                                    <span class="font-bold text-slate-700 dark:text-slate-300">
                                        I currently work here
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                Experience Description
                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                placeholder="Write about your responsibilities, achievements, projects, technologies or contributions..."
                                class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                            >{{ old('description') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-black shadow-xl hover:scale-[1.02] transition"
                        >
                            + Add Work Experience
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
