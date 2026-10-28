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
