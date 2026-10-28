<x-app-layout>

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-cyan-950 to-indigo-950 p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,.35),transparent_35%)]"></div>

            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>

                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        Job Details
                    </p>

                    <h2 class="uc-header-context mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $job->title }}
                    </h2>

                    <p class="uc-header-context mt-3 text-slate-300 max-w-2xl">
                        {{ $job->company_name }}
                        •
                        {{ $job->location ?: 'Location not specified' }}
                    </p>

                </div>


                <a
                    href="{{ route('jobs.index') }}"
                    class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Jobs
                </a>

            </div>

        </div>

    </x-slot>
    <div class="mx-auto max-w-7xl px-4"><x-post-lifecycle-status :post="$job" /><x-post-actions :post="$job" type="job" :post-id="$job->id" :owner-id="$job->posted_by" /></div>



    <style>

        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(
                135deg,
                rgba(255,255,255,.16),
                rgba(255,255,255,.06)
            );
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
        }

        .uc-info-card {
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.07);
            padding: 1rem;
        }

    </style>


    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">


        {{-- ========================================================= --}}
        {{-- LEFT SIDE --}}
        {{-- ========================================================= --}}

        <div class="xl:col-span-2 space-y-6">


            {{-- Success Message --}}

            @if(session('success'))

                <div class="uc-card p-5 text-emerald-500 font-black">

                    <i class="fas fa-circle-check mr-2"></i>

                    {{ session('success') }}

                </div>

            @endif


            {{-- Errors --}}

            @if($errors->any())

                <div class="uc-card p-5 text-red-400 font-black">

                    @foreach($errors->all() as $error)

                        <p>
                            <i class="fas fa-triangle-exclamation mr-2"></i>
                            {{ $error }}
                        </p>

                    @endforeach

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- MAIN JOB INFORMATION --}}
            {{-- ========================================================= --}}

            <div class="uc-card p-8">

                <div class="relative z-10">


                    <div class="flex flex-wrap gap-2 mb-6">


                        {{-- Job Type --}}

                        <span class="px-4 py-2 rounded-full bg-cyan-500/15 text-cyan-600 dark:text-cyan-300 text-sm font-black">

                            <i class="fas fa-briefcase mr-1"></i>

                            {{ ucwords(str_replace('_', ' ', $job->type)) }}

                        </span>


                        {{-- Status --}}

                        @if(auth()->user()->isAdmin() || auth()->id() === $job->posted_by)

                            <span
                                class="px-4 py-2 rounded-full text-sm font-black

                                @if($job->status === 'approved')
                                    bg-emerald-500/15 text-emerald-600 dark:text-emerald-300

                                @elseif($job->status === 'pending')
                                    bg-amber-500/15 text-amber-600 dark:text-amber-300

                                @elseif($job->status === 'rejected')
                                    bg-red-500/15 text-red-600 dark:text-red-300

                                @elseif($job->status === 'closed')
                                    bg-slate-500/15 text-slate-600 dark:text-slate-300

                                @else
                                    bg-purple-500/15 text-purple-600 dark:text-purple-300
                                @endif
                            "
                            >

                                {{ strtoupper($job->status) }}

                            </span>

                        @endif


                        {{-- Salary --}}

                        @if($job->salary_range)

                            <span class="px-4 py-2 rounded-full bg-purple-500/15 text-purple-600 dark:text-purple-300 text-sm font-black">

                                <i class="fas fa-money-bill-wave mr-1"></i>

                                {{ $job->salary_range }}

                            </span>

                        @endif


                        {{-- Vacancy --}}

                        <span class="px-4 py-2 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-300 text-sm font-black">

                            <i class="fas fa-users mr-1"></i>

                            {{ $job->positions_available ?? 1 }}
                            {{ ($job->positions_available ?? 1) == 1 ? 'Vacancy' : 'Vacancies' }}

                        </span>


                    </div>


                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">

                        About this opportunity

                    </h3>


                    <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">

                        {{ $job->description }}

                    </p>


                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- REQUIREMENTS --}}
            {{-- ========================================================= --}}

            <div class="uc-card p-8">

                <div class="relative z-10">

                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">

                        <i class="fas fa-list-check mr-2 text-cyan-500"></i>

                        Requirements

                    </h3>


                    <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">

                        {{ $job->requirements ?: 'No specific requirements added.' }}

                    </p>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- BENEFITS --}}
            {{-- ========================================================= --}}

            @if($job->benefits)

                <div class="uc-card p-8">

                    <div class="relative z-10">

                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">

                            <i class="fas fa-gift mr-2 text-fuchsia-500"></i>

                            Benefits

                        </h3>


                        <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">

                            {{ $job->benefits }}

                        </p>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- CONTACT / APPLICATION INFORMATION --}}
            {{-- ========================================================= --}}

            @php
                $canApply = in_array(auth()->user()->role, ['student', 'alumni'], true)
                    && (int) $job->posted_by !== (int) auth()->id()
                    && $job->status === 'approved'
                    && \App\Services\PostLifecycleService::accepting($job);
            @endphp
            @if($job->contact_email || $job->contact_phone || $job->application_url)

                <div class="uc-card p-8">

                    <div class="relative z-10">


                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">

                            Application Information

                        </p>


                        <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">

                            {{ $canApply ? 'How to Apply' : 'Contact Details' }}

                        </h3>


                        <p class="mt-3 text-slate-500 dark:text-slate-400">

                            {{ $canApply ? 'Use the contact information provided by the employer to apply or ask about this opportunity.' : 'Contact information supplied by the job poster.' }}

                        </p>


                        <div class="mt-7 grid grid-cols-1 md:grid-cols-2 gap-4">


                            {{-- Contact Email --}}

                            @if($job->contact_email)

                                <div class="rounded-2xl bg-cyan-500/10 border border-cyan-500/20 p-5">

                                    <div class="flex items-start gap-4">

                                        <div class="h-12 w-12 rounded-2xl bg-cyan-500/15 flex items-center justify-center shrink-0">

                                            <i class="fas fa-envelope text-cyan-500 text-xl"></i>

                                        </div>


                                        <div class="min-w-0">

                                            <p class="text-xs uppercase tracking-wider text-slate-500 font-bold">

                                                Contact Email

                                            </p>


                                            <a
                                                href="mailto:{{ $job->contact_email }}"
                                                class="mt-1 block font-black text-cyan-600 dark:text-cyan-300 break-all hover:underline"
                                            >

                                                {{ $job->contact_email }}

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- Contact Phone --}}

                            @if($job->contact_phone)

                                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-5">

                                    <div class="flex items-start gap-4">

                                        <div class="h-12 w-12 rounded-2xl bg-emerald-500/15 flex items-center justify-center shrink-0">

                                            <i class="fas fa-phone text-emerald-500 text-xl"></i>

                                        </div>


                                        <div>

                                            <p class="text-xs uppercase tracking-wider text-slate-500 font-bold">

                                                Contact Phone

                                            </p>


                                            <a
                                                href="tel:{{ $job->contact_phone }}"
                                                class="mt-1 block font-black text-emerald-600 dark:text-emerald-300 hover:underline"
                                            >

                                                {{ $job->contact_phone }}

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            @endif


                        </div>


                        {{-- External Application Link --}}

                        @if($job->application_url)

                            <div class="mt-5">

                                <a
                                    href="{{ $job->application_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-black shadow-xl hover:scale-[1.02] transition"
                                >

                                    <i class="fas fa-arrow-up-right-from-square mr-2"></i>

                                    {{ $canApply ? 'Apply on Company Website' : 'Company Website' }}

                                </a>

                            </div>

                        @endif


                    </div>

                </div>

            @else

                <div class="uc-card p-8">

                    <div class="relative z-10">

                        <div class="rounded-2xl bg-amber-500/10 border border-amber-500/20 p-5">

                            <div class="flex gap-4">

                                <i class="fas fa-circle-info text-amber-500 text-xl mt-1"></i>

                                <div>

                                    <h4 class="font-black text-slate-900 dark:text-white">

                                        Contact information not provided

                                    </h4>

                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                                        The job poster has not provided application contact information for this opportunity.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- STUDENT INFORMATION --}}
            {{-- ========================================================= --}}

            @if(auth()->user()->isStudent() && $job->status === 'approved')

                <div class="uc-card p-7">

                    <div class="relative z-10">

                        <div class="flex items-start gap-4">

                            <div class="h-12 w-12 rounded-2xl bg-blue-500/15 flex items-center justify-center shrink-0">

                                <i class="fas fa-eye text-blue-500 text-xl"></i>

                            </div>


                            <div>

                                <h4 class="font-black text-slate-900 dark:text-white">

                                    Job Opportunity

                                </h4>


                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                                    Review the job requirements and use the employer's contact information or external application link above if you are interested.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            @endif


        </div>


        {{-- ========================================================= --}}
        {{-- RIGHT SIDE --}}
        {{-- ========================================================= --}}

        <div class="space-y-6">


            {{-- Job Snapshot --}}

            <div class="uc-card p-7">

                <div class="relative z-10">

                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">

                        Job Snapshot

                    </h3>


                    <div class="mt-6 space-y-4">


                        {{-- Company --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Company
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->company_name }}

                            </p>

                        </div>


                        {{-- Location --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Location
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->location ?: 'Not specified' }}

                            </p>

                        </div>


                        {{-- Job Type --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Job Type
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ ucwords(str_replace('_', ' ', $job->type)) }}

                            </p>

                        </div>


                        {{-- Experience --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Experience Level
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->experience_level
                                    ? ucfirst($job->experience_level)
                                    : 'Not specified'
                                }}

                            </p>

                        </div>


                        {{-- Vacancy --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Vacancy
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->positions_available ?? 1 }}

                            </p>

                        </div>


                        {{-- Salary --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Salary
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->salary_range ?: 'Negotiable / Not specified' }}

                            </p>

                        </div>


                        {{-- Deadline --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Application Deadline
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->deadline
                                    ? $job->deadline->format('d M Y')
                                    : 'Open'
                                }}

                            </p>

                        </div>


                        {{-- Posted By --}}

                        <div class="uc-info-card">

                            <p class="text-xs text-slate-500 font-bold">
                                Posted By
                            </p>

                            <p class="mt-1 font-black text-slate-900 dark:text-white">

                                {{ $job->postedBy?->name ?? 'University Connect' }}

                            </p>


                            @if($job->postedBy)

                                <p class="mt-1 text-xs uppercase tracking-wider text-cyan-500 font-bold">

                                    {{ str_replace('_', ' ', $job->postedBy->role) }}

                                </p>

                            @endif

                        </div>


                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- ADMIN APPROVAL --}}
            {{-- ========================================================= --}}

            @if(
                auth()->user()->isAdmin() &&
                $job->status === 'pending'
            )

                <div class="uc-card p-7">

                    <div class="relative z-10">

                        <p class="text-xs uppercase tracking-[0.25em] text-amber-500 font-black">

                            Management

                        </p>


                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">

                            Admin Approval

                        </h3>

                        @if(auth()->user()->isAdmin())

    <div class="uc-card p-7">

        <div class="relative z-10">

            <p class="text-xs uppercase tracking-[0.25em] text-red-500 font-black">
                Danger Zone
            </p>

            <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                Delete Job Post
            </h3>

            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                Permanently remove this job post from the platform.
            </p>

            <form
                method="POST"
                action="{{ route('jobs.destroy', $job) }}"
                onsubmit="return confirm('Are you sure you want to permanently delete this job post?')"
                class="mt-6"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-red-500 py-3 text-white font-black hover:bg-red-600 transition"
                >
                    <i class="fas fa-trash mr-2"></i>
                    Delete Job
                </button>

            </form>

        </div>

    </div>

@endif
                        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">

                            Review this alumni-submitted opportunity before publishing it.

                        </p>


                        <div class="mt-6 flex flex-col sm:flex-row gap-3">


                            <form
                                method="POST"
                                action="{{ route('jobs.approve', $job) }}"
                                class="flex-1"
                            >

                                @csrf
                                @method('PATCH')


                                <button
                                    type="submit"
                                    class="w-full rounded-2xl bg-emerald-500 py-3 text-white font-black hover:bg-emerald-600 transition"
                                >

                                    <i class="fas fa-check mr-2"></i>

                                    Approve

                                </button>

                            </form>


                            <form
                                method="POST"
                                action="{{ route('jobs.reject', $job) }}"
                                class="flex-1"
                            >

                                @csrf
                                @method('PATCH')


                                <button
                                    type="submit"
                                    class="w-full rounded-2xl bg-red-500 py-3 text-white font-black hover:bg-red-600 transition"
                                >

                                    <i class="fas fa-xmark mr-2"></i>

                                    Reject

                                </button>

                            </form>


                        </div>

                    </div>

                </div>

            @endif


            {{-- Alumni Pending Information --}}

            @if(
                auth()->user()->isAlumni() &&
                auth()->id() === $job->posted_by &&
                $job->status === 'pending'
            )

                <div class="uc-card p-7">

                    <div class="relative z-10">

                        <div class="rounded-2xl bg-amber-500/10 border border-amber-500/20 p-5">

                            <i class="fas fa-clock text-amber-500 text-2xl"></i>

                            <h4 class="mt-3 font-black text-slate-900 dark:text-white">

                                Waiting for Approval

                            </h4>

                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                                This job is currently waiting for Admin or Super Admin approval.

                            </p>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Alumni Rejected Information --}}

            @if(
                auth()->user()->isAlumni() &&
                auth()->id() === $job->posted_by &&
                $job->status === 'rejected'
            )

                <div class="uc-card p-7">

                    <div class="relative z-10">

                        <div class="rounded-2xl bg-red-500/10 border border-red-500/20 p-5">

                            <i class="fas fa-circle-xmark text-red-500 text-2xl"></i>

                            <h4 class="mt-3 font-black text-slate-900 dark:text-white">

                                Job Not Approved

                            </h4>

                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                                This job submission was rejected by management and is not visible to students.

                            </p>

                        </div>

                    </div>

                </div>

            @endif


        </div>

    </div>

</x-app-layout>