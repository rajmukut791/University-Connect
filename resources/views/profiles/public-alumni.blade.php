<x-app-layout>
    <div class="mx-auto max-w-7xl px-4"><x-mentorship-contact :user="$profileUser" /></div>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(59,130,246,.25),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-purple-300 font-black">
                        Alumni Public Profile
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $profileUser->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-3xl">
                        Professional alumni profile for mentorship, networking and career guidance.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-5 text-center">
                    <p class="text-xs text-slate-300">
                        Role
                    </p>

                    <p class="text-2xl font-black text-emerald-300">
                        Alumni
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Mentor Profile
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Left Profile Card --}}
            <div class="space-y-8">

                {{-- Main Profile --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                    <div class="text-center">

                        <x-profile-photo :user="$profileUser" />

                        <h3 class="mt-5 text-3xl font-black text-slate-900 dark:text-white">
                            {{ $profileUser->name }}
                        </h3>

                        <p class="text-purple-500 font-bold mt-1">
                            {{ $profileUser->current_designation ?: 'Position not added' }}
                        </p>

                        <p class="text-slate-500 mt-2">
                            {{ $profileUser->current_company ?: 'Organization not added' }}
                        </p>
                    </div>
                </div>

                {{-- Professional Snapshot --}}
                <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-purple-950 to-indigo-950 border border-white/10 p-7 shadow-2xl text-white">

                    <p class="text-xs uppercase tracking-[0.25em] text-purple-300 font-black">
                        Professional Snapshot
                    </p>

                    <div class="mt-5 space-y-5">

                        <div>
                            <p class="text-xs text-slate-400">
                                Current Position
                            </p>

                            <p class="mt-1 font-black">
                                {{ $profileUser->current_designation ?: 'Not specified' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">
                                Current Organization
                            </p>

                            <p class="mt-1 font-black">
                                {{ $profileUser->current_company ?: 'Not specified' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">
                                Total Experience
                            </p>

                            <p class="mt-1 font-black text-emerald-300">
                                {{ $profileUser->work_experience_years ?? 'Not specified' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">
                                Department
                            </p>

                            <p class="mt-1 font-black">
                                {{ $profileUser->department ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Social Links --}}
                @if(
                    $profileUser->github_url ||
                    $profileUser->linkedin_url ||
                    $profileUser->portfolio_url
                )
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">

                        <h4 class="text-xl font-black text-slate-900 dark:text-white mb-4">
                            Professional Links
                        </h4>

                        <div class="space-y-3">

                            @if($profileUser->linkedin_url)
                                <a
                                    href="{{ $profileUser->linkedin_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="block px-4 py-3 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-300 hover:bg-blue-500/20 transition font-bold"
                                >
                                    LinkedIn Profile
                                </a>
                            @endif

                            @if($profileUser->github_url)
                                <a
                                    href="{{ $profileUser->github_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="block px-4 py-3 rounded-2xl bg-slate-100 dark:bg-slate-950 hover:bg-slate-200 dark:hover:bg-slate-800 transition font-bold"
                                >
                                    GitHub Profile
                                </a>
                            @endif

                            @if($profileUser->portfolio_url)
                                <a
                                    href="{{ $profileUser->portfolio_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="block px-4 py-3 rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-300 hover:bg-purple-500/20 transition font-bold"
                                >
                                    Portfolio Website
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Main Content --}}
            <div class="xl:col-span-2 space-y-8">

                {{-- Academic Information --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                        University Background
                    </p>

                    <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                        Academic Information
                    </h3>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">
                                Department
                            </p>

                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->department ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">
                                Batch / Passing Year
                            </p>

                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->batch ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Professional Experience --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">
                                Career Journey
                            </p>

                            <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                                Professional Experience
                            </h3>
                        </div>

                        @if(filled($profileUser->work_experience_years))
                            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 px-5 py-3">
                                <p class="text-xs text-slate-500">
                                    Total Experience
                                </p>

                                <p class="font-black text-emerald-600 dark:text-emerald-300">
                                    {{ $profileUser->work_experience_years }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($profileUser->workExperiences->isNotEmpty())

                        <div class="mt-8 space-y-0">

                            @foreach($profileUser->workExperiences as $experience)

                                <div class="relative pl-10 pb-10 last:pb-0">

                                    {{-- Timeline Line --}}
                                    @if(!$loop->last)
                                        <div class="absolute left-[11px] top-7 bottom-0 w-px bg-purple-500/30"></div>
                                    @endif

                                    {{-- Timeline Dot --}}
                                    <div class="absolute left-0 top-1 h-6 w-6 rounded-full bg-purple-500 ring-4 ring-purple-500/15"></div>

                                    <div class="rounded-3xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-white/10 p-6">

                                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                                            <div>

                                                <div class="flex flex-wrap items-center gap-3">

                                                    <h4 class="text-xl font-black text-slate-900 dark:text-white">
                                                        {{ $experience->designation }}
                                                    </h4>

                                                    @if($experience->is_current)
                                                        <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-300 text-xs font-black">
                                                            Current
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="mt-2 text-lg font-bold text-purple-600 dark:text-purple-300">
                                                    {{ $experience->company_name }}
                                                </p>

                                                <p class="mt-3 text-sm font-bold text-slate-500">
                                                    {{ $experience->start_date?->format('M Y') ?: 'Start date not specified' }}

                                                    —

                                                    @if($experience->is_current)
                                                        Present
                                                    @else
                                                        {{ $experience->end_date?->format('M Y') ?: 'End date not specified' }}
                                                    @endif
                                                </p>

                                            </div>

                                            <div class="flex flex-wrap gap-2">

                                                @if($experience->employment_type)
                                                    <span class="px-3 py-1 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-300 text-xs font-bold">
                                                        {{ $experience->employment_type }}
                                                    </span>
                                                @endif

                                                @if($experience->work_mode)
                                                    <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-300 text-xs font-bold">
                                                        {{ $experience->work_mode }}
                                                    </span>
                                                @endif

                                            </div>
                                        </div>

                                        @if($experience->location)
                                            <p class="mt-4 text-sm text-slate-500">
                                                {{ $experience->location }}
                                            </p>
                                        @endif

                                        @if($experience->description)
                                            <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                                                {{ $experience->description }}
                                            </p>
                                        @endif

                                    </div>
                                </div>

                            @endforeach
                        </div>

                    @else

                        {{-- Legacy Fallback --}}
                        @if(
                            $profileUser->current_company ||
                            $profileUser->current_designation ||
                            $profileUser->previous_company ||
                            $profileUser->previous_designation
                        )

                            <div class="mt-8 rounded-3xl bg-amber-500/10 border border-amber-500/20 p-6">

                                <p class="text-xs uppercase tracking-[0.25em] text-amber-600 dark:text-amber-300 font-black">
                                    Legacy Profile Information
                                </p>

                                @if($profileUser->current_company || $profileUser->current_designation)

                                    <div class="mt-5">

                                        <h4 class="text-xl font-black text-slate-900 dark:text-white">
                                            {{ $profileUser->current_designation ?: 'Current Position' }}
                                        </h4>

                                        @if($profileUser->current_company)
                                            <p class="mt-1 font-bold text-purple-600 dark:text-purple-300">
                                                {{ $profileUser->current_company }}
                                            </p>
                                        @endif

                                        @if($profileUser->current_job_type)
                                            <p class="mt-2 text-sm text-slate-500">
                                                {{ $profileUser->current_job_type }}
                                            </p>
                                        @endif
                                    </div>

                                @endif

                                @if($profileUser->previous_company || $profileUser->previous_designation)

                                    <div class="mt-6 border-t border-amber-500/20 pt-6">

                                        <h4 class="text-lg font-black text-slate-900 dark:text-white">
                                            {{ $profileUser->previous_designation ?: 'Previous Position' }}
                                        </h4>

                                        @if($profileUser->previous_company)
                                            <p class="mt-1 font-bold text-slate-600 dark:text-slate-300">
                                                {{ $profileUser->previous_company }}
                                            </p>
                                        @endif

                                        @if($profileUser->previous_job_details)
                                            <p class="mt-3 text-slate-600 dark:text-slate-300 leading-relaxed">
                                                {{ $profileUser->previous_job_details }}
                                            </p>
                                        @endif

                                    </div>

                                @endif

                            </div>

                        @else

                            <div class="mt-8 rounded-3xl border-2 border-dashed border-slate-300 dark:border-white/10 p-8 text-center">

                                <h4 class="text-xl font-black text-slate-900 dark:text-white">
                                    No Professional Experience Added Yet
                                </h4>

                                <p class="mt-2 text-sm text-slate-500">
                                    This alumni has not added professional experience to the profile yet.
                                </p>

                            </div>

                        @endif

                    @endif
                </div>

                {{-- Professional Bio --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                    <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">
                        About
                    </p>

                    <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                        Professional Bio
                    </h3>

                    <p class="mt-6 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $profileUser->bio ?: 'No professional biography provided yet.' }}
                    </p>
                </div>

                {{-- Skills --}}
                @if($profileUser->skills)

                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                            Expertise
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                            Skills & Expertise
                        </h3>

                        <div class="mt-6 flex flex-wrap gap-3">

                            @foreach(explode(',', $profileUser->skills) as $skill)

                                @if(trim($skill) !== '')
                                    <span class="px-4 py-2 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-300 font-bold">
                                        {{ trim($skill) }}
                                    </span>
                                @endif

                            @endforeach

                        </div>
                    </div>

                @endif

                {{-- Actions --}}
                <div class="flex flex-wrap gap-4">

                    <a
                        href="{{ route('mentors.index') }}"
                        class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black shadow-xl hover:scale-105 transition"
                    >
                        Back to Mentors
                    </a>

                    @if(auth()->id() !== $profileUser->id)

                    @endif

                </div>

            </div>
        </div>
    </div>
</x-app-layout>