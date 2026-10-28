<x-app-layout>

    <x-slot name="header">

        <div
            class="relative overflow-hidden rounded-3xl
                   bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-950
                   p-8 shadow-2xl border border-white/10">

            <div class="relative z-10">

                <p
                    class="text-sm uppercase tracking-[0.35em]
                           text-cyan-300 font-black">

                    Student Lifecycle

                </p>

                <h2 class="mt-3 text-4xl font-black text-white">

                    Apply for Alumni Status

                </h2>

                <p class="mt-3 text-slate-300 max-w-3xl">

                    Submit your graduation information and supporting details.
                    An Admin or Super Admin will review your request before
                    converting your student account to an alumni account.

                </p>

            </div>

        </div>

    </x-slot>


    <div class="max-w-4xl mx-auto space-y-6">


        {{-- ===============================================================
        | Success Message
        ================================================================ --}}

        @if(session('success'))

            <div
                class="rounded-2xl
                       bg-emerald-500/15
                       border border-emerald-500/30
                       p-4 text-emerald-600
                       font-bold">

                {{ session('success') }}

            </div>

        @endif


        {{-- ===============================================================
        | Error Message
        ================================================================ --}}

        @if(session('error'))

            <div
                class="rounded-2xl
                       bg-red-500/15
                       border border-red-500/30
                       p-4 text-red-600
                       font-bold">

                {{ session('error') }}

            </div>

        @endif


        {{-- ===============================================================
        | Validation Error Summary
        ================================================================ --}}

        @if($errors->any())

            <div
                class="rounded-2xl
                       bg-red-500/10
                       border border-red-500/30
                       p-5">

                <p
                    class="font-black
                           text-red-600
                           mb-3">

                    Please correct the following information:

                </p>

                <ul
                    class="list-disc pl-5
                           space-y-1
                           text-sm
                           text-red-500
                           font-semibold">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ===============================================================
        | Current Student Information
        ================================================================ --}}

        <div
            class="rounded-3xl
                   bg-white dark:bg-slate-900
                   border border-slate-200 dark:border-white/10
                   shadow-xl p-6">

            <div
                class="flex flex-col
                       md:flex-row
                       md:items-center
                       md:justify-between
                       gap-5">

                <div>

                    <p
                        class="text-xs uppercase
                               tracking-[0.25em]
                               text-cyan-600
                               font-black">

                        Applicant Information

                    </p>

                    <h3
                        class="mt-2 text-xl
                               font-black
                               text-slate-900
                               dark:text-white">

                        {{ auth()->user()->name }}

                    </h3>

                    <p
                        class="mt-1 text-sm
                               text-slate-500">

                        {{ auth()->user()->email }}

                    </p>

                </div>


                <div
                    class="grid grid-cols-1
                           sm:grid-cols-2 gap-3">

                    <div
                        class="rounded-2xl
                               bg-slate-100
                               dark:bg-slate-950
                               px-5 py-3">

                        <p
                            class="text-xs
                                   text-slate-500
                                   font-bold">

                            Official ID

                        </p>

                        <p
                            class="mt-1
                                   text-sm
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            {{ auth()->user()->official_id ?? 'N/A' }}

                        </p>

                    </div>


                    <div
                        class="rounded-2xl
                               bg-slate-100
                               dark:bg-slate-950
                               px-5 py-3">

                        <p
                            class="text-xs
                                   text-slate-500
                                   font-bold">

                            Current Role

                        </p>

                        <p
                            class="mt-1
                                   text-sm
                                   font-black
                                   text-cyan-600">

                            {{ ucfirst(auth()->user()->role) }}

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===============================================================
        | Pending Request State
        ================================================================ --}}

        @if(!$existingRequest && $latestApplication)
            <section class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                <h3 class="font-bold text-white">Previous Application: {{ ucfirst($latestApplication->status) }}</h3>
                @if($latestApplication->admin_notes)<p class="mt-2 whitespace-pre-line text-sm text-slate-300">{{ $latestApplication->admin_notes }}</p>@endif
                @if($latestApplication->status === 'rejected')<p class="mt-2 text-xs text-amber-300">Review the feedback and submit a corrected application below.</p>@endif
            </section>
        @endif
        @if($existingRequest)

            <div
                class="rounded-3xl
                       bg-amber-500/10
                       border border-amber-500/30
                       p-8 shadow-2xl">

                <div
                    class="flex flex-col
                           md:flex-row
                           md:items-start
                           md:justify-between
                           gap-6">

                    <div>

                        <p
                            class="text-xs uppercase
                                   tracking-[0.25em]
                                   text-amber-600
                                   font-black">

                            Application Status

                        </p>

                        <h3
                            class="mt-2 text-2xl
                                   font-black
                                   text-amber-500">

                            Request Already Pending

                        </h3>

                        <p
                            class="mt-3
                                   text-slate-600
                                   dark:text-slate-300
                                   max-w-2xl">

                            You already submitted an alumni conversion request.
                            Please wait until an Admin or Super Admin reviews
                            your application.

                        </p>

                    </div>


                    <span
                        class="inline-flex items-center
                               justify-center
                               px-5 py-2
                               rounded-full
                               bg-amber-500/15
                               text-amber-600
                               text-sm font-black">

                        Pending Review

                    </span>

                </div>


                <div
                    class="mt-6
                           grid grid-cols-1
                           md:grid-cols-3
                           gap-4">

                    <div
                        class="rounded-2xl
                               bg-white/50
                               dark:bg-slate-950/50
                               p-4">

                        <p
                            class="text-xs
                                   text-slate-500
                                   font-bold">

                            Graduation Year

                        </p>

                        <p
                            class="mt-1
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            {{ $existingRequest->graduation_year }}

                        </p>

                    </div>


                    <div
                        class="rounded-2xl
                               bg-white/50
                               dark:bg-slate-950/50
                               p-4">

                        <p
                            class="text-xs
                                   text-slate-500
                                   font-bold">

                            Submitted

                        </p>

                        <p
                            class="mt-1
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            {{ $existingRequest->created_at?->format('d M Y') }}

                        </p>

                    </div>


                    <div
                        class="rounded-2xl
                               bg-white/50
                               dark:bg-slate-950/50
                               p-4">

                        <p
                            class="text-xs
                                   text-slate-500
                                   font-bold">

                            Status

                        </p>

                        <p
                            class="mt-1
                                   font-black
                                   text-amber-600">

                            Pending

                        </p>

                    </div>

                </div>

            </div>


        @else


            {{-- ===========================================================
            | Application Instructions
            ============================================================ --}}

            <div
                class="rounded-3xl
                       bg-cyan-500/5
                       border border-cyan-500/20
                       p-6">

                <h3
                    class="text-lg font-black
                           text-slate-900
                           dark:text-white">

                    Before You Submit

                </h3>

                <div
                    class="mt-4 grid
                           grid-cols-1
                           md:grid-cols-3
                           gap-4">

                    <div>

                        <p
                            class="text-sm
                                   font-black
                                   text-cyan-600">

                            01. Graduation

                        </p>

                        <p
                            class="mt-1
                                   text-sm
                                   text-slate-500">

                            Enter your correct graduation year.

                        </p>

                    </div>


                    <div>

                        <p
                            class="text-sm
                                   font-black
                                   text-cyan-600">

                            02. Career Details

                        </p>

                        <p
                            class="mt-1
                                   text-sm
                                   text-slate-500">

                            Company and designation are optional.

                        </p>

                    </div>


                    <div>

                        <p
                            class="text-sm
                                   font-black
                                   text-cyan-600">

                            03. Verification

                        </p>

                        <p
                            class="mt-1
                                   text-sm
                                   text-slate-500">

                            Supporting documents may help the review process.

                        </p>

                    </div>

                </div>

            </div>


            {{-- ===========================================================
            | Alumni Conversion Form
            ============================================================ --}}

            <form
                method="POST"
                action="{{ route('alumni-conversion.store') }}"
                enctype="multipart/form-data"
                class="rounded-3xl
                       bg-white dark:bg-slate-900
                       border border-slate-200
                       dark:border-white/10
                       p-8 shadow-2xl
                       space-y-7"
                onsubmit="
                    const button = this.querySelector(
                        '[data-submit-button]'
                    );

                    if (button) {
                        button.disabled = true;
                        button.innerText = 'Submitting Request...';
                    }
                ">

                @csrf


                {{-- Graduation Year --}}
                <div>

                    <label
                        for="graduation_year"
                        class="block text-sm
                               font-black
                               text-slate-700
                               dark:text-slate-200
                               mb-2">

                        Graduation Year

                        <span class="text-red-500">
                            *
                        </span>

                    </label>


                    <input
                        id="graduation_year"
                        type="text"
                        name="graduation_year"
                        value="{{ old('graduation_year') }}"
                        placeholder="Example: 2026"
                        inputmode="numeric"
                        maxlength="4"
                        required
                        class="w-full rounded-2xl
                               border-slate-300
                               dark:border-white/10
                               dark:bg-slate-950
                               dark:text-white
                               focus:border-cyan-500
                               focus:ring-cyan-500">


                    <p
                        class="mt-2
                               text-xs
                               text-slate-500">

                        Enter your four-digit graduation year.

                    </p>


                    <x-input-error
                        :messages="$errors->get('graduation_year')"
                        class="mt-2 text-red-500"
                    />

                </div>


                {{-- Career Section --}}
                <div
                    class="grid grid-cols-1
                           md:grid-cols-2
                           gap-6">


                    {{-- Company --}}
                    <div>

                        <label
                            for="current_company"
                            class="block text-sm
                                   font-black
                                   text-slate-700
                                   dark:text-slate-200
                                   mb-2">

                            Current Company

                            <span
                                class="text-slate-400
                                       font-medium">

                                (Optional)

                            </span>

                        </label>


                        <input
                            id="current_company"
                            type="text"
                            name="current_company"
                            value="{{ old('current_company') }}"
                            placeholder="Example: ABC Technologies"
                            maxlength="255"
                            class="w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-cyan-500
                                   focus:ring-cyan-500">


                        <x-input-error
                            :messages="$errors->get('current_company')"
                            class="mt-2 text-red-500"
                        />

                    </div>


                    {{-- Designation --}}
                    <div>

                        <label
                            for="designation"
                            class="block text-sm
                                   font-black
                                   text-slate-700
                                   dark:text-slate-200
                                   mb-2">

                            Designation

                            <span
                                class="text-slate-400
                                       font-medium">

                                (Optional)

                            </span>

                        </label>


                        <input
                            id="designation"
                            type="text"
                            name="designation"
                            value="{{ old('designation') }}"
                            placeholder="Example: Software Engineer"
                            maxlength="255"
                            class="w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-cyan-500
                                   focus:ring-cyan-500">


                        <x-input-error
                            :messages="$errors->get('designation')"
                            class="mt-2 text-red-500"
                        />

                    </div>

                </div>


                {{-- Supporting Document --}}
                <div>

                    <label
                        for="supporting_document"
                        class="block text-sm
                               font-black
                               text-slate-700
                               dark:text-slate-200
                               mb-2">

                        Supporting Document

                        <span
                            class="text-slate-400
                                   font-medium">

                            (Optional)

                        </span>

                    </label>


                    <input
                        id="supporting_document"
                        type="file"
                        name="supporting_document"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                        class="w-full
                               rounded-2xl
                               border
                               border-slate-300
                               dark:border-white/10
                               dark:bg-slate-950
                               dark:text-white
                               p-3">


                    <div
                        class="mt-3
                               rounded-xl
                               bg-slate-100
                               dark:bg-slate-950
                               p-3">

                        <p
                            class="text-xs
                                   text-slate-500">

                            Allowed formats:
                            PDF, JPG, JPEG, PNG, DOC, DOCX

                        </p>

                        <p
                            class="mt-1
                                   text-xs
                                   text-slate-500">

                            Maximum file size: 5 MB

                        </p>

                    </div>


                    <x-input-error
                        :messages="$errors->get('supporting_document')"
                        class="mt-2 text-red-500"
                    />

                </div>


                {{-- Student Note --}}
                <div>

                    <label
                        for="student_note"
                        class="block text-sm
                               font-black
                               text-slate-700
                               dark:text-slate-200
                               mb-2">

                        Student Note

                        <span
                            class="text-slate-400
                                   font-medium">

                            (Optional)

                        </span>

                    </label>


                    <textarea
                        id="student_note"
                        name="student_note"
                        rows="5"
                        maxlength="2000"
                        placeholder="Write any additional information you want the administrator to know..."
                        class="w-full
                               rounded-2xl
                               border-slate-300
                               dark:border-white/10
                               dark:bg-slate-950
                               dark:text-white
                               focus:border-cyan-500
                               focus:ring-cyan-500">{{ old('student_note') }}</textarea>


                    <p
                        class="mt-2
                               text-xs
                               text-slate-500">

                        Maximum 2,000 characters.

                    </p>


                    <x-input-error
                        :messages="$errors->get('student_note')"
                        class="mt-2 text-red-500"
                    />

                </div>


                {{-- Confirmation --}}
                <div
                    class="rounded-2xl
                           bg-indigo-500/5
                           border border-indigo-500/20
                           p-5">

                    <p
                        class="text-sm
                               text-slate-600
                               dark:text-slate-300">

                        By submitting this request, you confirm that the
                        information provided is correct. Your account will
                        remain a Student account until the request is approved.

                    </p>

                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    data-submit-button
                    class="w-full
                           rounded-2xl
                           bg-gradient-to-r
                           from-cyan-600
                           to-indigo-600
                           py-4
                           text-white
                           font-black
                           shadow-xl
                           hover:scale-[1.01]
                           hover:shadow-2xl
                           disabled:opacity-60
                           disabled:cursor-not-allowed
                           disabled:hover:scale-100
                           transition">

                    Submit Alumni Conversion Request

                </button>

            </form>

        @endif

    </div>

</x-app-layout>