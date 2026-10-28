<x-app-layout>
    <x-slot name="header">
        <div
            class="relative overflow-hidden rounded-3xl
                   bg-gradient-to-r from-slate-950 via-purple-950 to-indigo-950
                   p-8 shadow-2xl border border-white/10">

            <div class="relative z-10">

                <p class="text-sm uppercase tracking-[0.35em]
                          text-cyan-300 font-black">
                    Alumni Conversion Admin Panel
                </p>

                <h2 class="mt-3 text-4xl font-black text-white">
                    Conversion Requests
                </h2>

                <p class="mt-3 text-slate-300">
                    Review student requests and convert eligible students
                    to alumni accounts.
                </p>

            </div>
        </div>
    </x-slot>


    <div class="uc-conversion-review space-y-6">

        {{-- ================================================================
        | Success Message
        ================================================================= --}}

        @if(session('success'))
            <div
                class="rounded-2xl bg-emerald-500/15
                       border border-emerald-500/30
                       p-4 text-emerald-600 font-bold">

                {{ session('success') }}

            </div>
        @endif


        {{-- ================================================================
        | Error Message
        ================================================================= --}}

        @if(session('error'))
            <div
                class="rounded-2xl bg-red-500/15
                       border border-red-500/30
                       p-4 text-red-600 font-bold">

                {{ session('error') }}

            </div>
        @endif


        {{-- ================================================================
        | Validation Errors
        ================================================================= --}}

        @if($errors->any())
            <div
                class="rounded-2xl bg-red-500/15
                       border border-red-500/30
                       p-4 text-red-600">

                <p class="font-black mb-2">
                    Please check the following:
                </p>

                <ul class="list-disc pl-5 space-y-1 text-sm font-semibold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        {{-- ================================================================
        | Category Summary
        ================================================================= --}}

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Pending --}}
            <a
                href="{{ route('alumni-conversion.index', [
                    'status' => 'pending',
                    'search' => $search ?: null
                ]) }}"
                class="group rounded-2xl border p-5 transition
                       {{ $status === 'pending'
                            ? 'bg-amber-500/10 border-amber-500/40 shadow-lg'
                            : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-white/10 hover:border-amber-500/30'
                       }}">

                <div class="flex items-center justify-between gap-3">

                    <div>
                        <p class="text-xs uppercase tracking-widest
                                  text-slate-500 font-black">
                            Pending
                        </p>

                        <p class="mt-2 text-3xl font-black
                                  text-slate-900 dark:text-white">
                            {{ $counts['pending'] }}
                        </p>
                    </div>

                    <div
                        class="w-11 h-11 rounded-xl flex items-center
                               justify-center bg-amber-500/10
                               text-amber-500 font-black text-lg">
                        P
                    </div>

                </div>

                <p class="mt-3 text-xs text-slate-500">
                    Waiting for review
                </p>

            </a>


            {{-- Approved --}}
            <a
                href="{{ route('alumni-conversion.index', [
                    'status' => 'approved',
                    'search' => $search ?: null
                ]) }}"
                class="group rounded-2xl border p-5 transition
                       {{ $status === 'approved'
                            ? 'bg-emerald-500/10 border-emerald-500/40 shadow-lg'
                            : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-white/10 hover:border-emerald-500/30'
                       }}">

                <div class="flex items-center justify-between gap-3">

                    <div>
                        <p class="text-xs uppercase tracking-widest
                                  text-slate-500 font-black">
                            Approved
                        </p>

                        <p class="mt-2 text-3xl font-black
                                  text-slate-900 dark:text-white">
                            {{ $counts['approved'] }}
                        </p>
                    </div>

                    <div
                        class="w-11 h-11 rounded-xl flex items-center
                               justify-center bg-emerald-500/10
                               text-emerald-500 font-black text-lg">
                        A
                    </div>

                </div>

                <p class="mt-3 text-xs text-slate-500">
                    Successfully converted
                </p>

            </a>


            {{-- Rejected --}}
            <a
                href="{{ route('alumni-conversion.index', [
                    'status' => 'rejected',
                    'search' => $search ?: null
                ]) }}"
                class="group rounded-2xl border p-5 transition
                       {{ $status === 'rejected'
                            ? 'bg-red-500/10 border-red-500/40 shadow-lg'
                            : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-white/10 hover:border-red-500/30'
                       }}">

                <div class="flex items-center justify-between gap-3">

                    <div>
                        <p class="text-xs uppercase tracking-widest
                                  text-slate-500 font-black">
                            Rejected
                        </p>

                        <p class="mt-2 text-3xl font-black
                                  text-slate-900 dark:text-white">
                            {{ $counts['rejected'] }}
                        </p>
                    </div>

                    <div
                        class="w-11 h-11 rounded-xl flex items-center
                               justify-center bg-red-500/10
                               text-red-500 font-black text-lg">
                        R
                    </div>

                </div>

                <p class="mt-3 text-xs text-slate-500">
                    Rejected requests
                </p>

            </a>


            {{-- All --}}
            <a
                href="{{ route('alumni-conversion.index', [
                    'status' => 'all',
                    'search' => $search ?: null
                ]) }}"
                class="group rounded-2xl border p-5 transition
                       {{ $status === 'all'
                            ? 'bg-cyan-500/10 border-cyan-500/40 shadow-lg'
                            : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-white/10 hover:border-cyan-500/30'
                       }}">

                <div class="flex items-center justify-between gap-3">

                    <div>
                        <p class="text-xs uppercase tracking-widest
                                  text-slate-500 font-black">
                            All Requests
                        </p>

                        <p class="mt-2 text-3xl font-black
                                  text-slate-900 dark:text-white">
                            {{ $counts['all'] }}
                        </p>
                    </div>

                    <div
                        class="w-11 h-11 rounded-xl flex items-center
                               justify-center bg-cyan-500/10
                               text-cyan-500 font-black text-lg">
                        ALL
                    </div>

                </div>

                <p class="mt-3 text-xs text-slate-500">
                    Complete request history
                </p>

            </a>

        </div>


        {{-- ================================================================
        | Filter + Search Panel
        ================================================================= --}}

        <div
            class="rounded-3xl bg-white dark:bg-slate-900
                   border border-slate-200 dark:border-white/10
                   shadow-xl p-5">

            <div
                class="flex flex-col xl:flex-row
                       xl:items-center xl:justify-between
                       gap-5">


                {{-- Category Tabs --}}
                <div
                    class="flex flex-wrap items-center gap-2">

                    <a
                        href="{{ route('alumni-conversion.index', [
                            'status' => 'pending',
                            'search' => $search ?: null
                        ]) }}"
                        class="px-4 py-2 rounded-xl
                               text-sm font-black transition
                               {{ $status === 'pending'
                                    ? 'bg-amber-500 text-white shadow-lg'
                                    : 'bg-slate-100 dark:bg-slate-950 text-slate-500 hover:text-amber-500'
                               }}">

                        Pending
                        <span class="ml-1">
                            {{ $counts['pending'] }}
                        </span>

                    </a>


                    <a
                        href="{{ route('alumni-conversion.index', [
                            'status' => 'approved',
                            'search' => $search ?: null
                        ]) }}"
                        class="px-4 py-2 rounded-xl
                               text-sm font-black transition
                               {{ $status === 'approved'
                                    ? 'bg-emerald-500 text-white shadow-lg'
                                    : 'bg-slate-100 dark:bg-slate-950 text-slate-500 hover:text-emerald-500'
                               }}">

                        Approved
                        <span class="ml-1">
                            {{ $counts['approved'] }}
                        </span>

                    </a>


                    <a
                        href="{{ route('alumni-conversion.index', [
                            'status' => 'rejected',
                            'search' => $search ?: null
                        ]) }}"
                        class="px-4 py-2 rounded-xl
                               text-sm font-black transition
                               {{ $status === 'rejected'
                                    ? 'bg-red-500 text-white shadow-lg'
                                    : 'bg-slate-100 dark:bg-slate-950 text-slate-500 hover:text-red-500'
                               }}">

                        Rejected
                        <span class="ml-1">
                            {{ $counts['rejected'] }}
                        </span>

                    </a>


                    <a
                        href="{{ route('alumni-conversion.index', [
                            'status' => 'all',
                            'search' => $search ?: null
                        ]) }}"
                        class="px-4 py-2 rounded-xl
                               text-sm font-black transition
                               {{ $status === 'all'
                                    ? 'bg-cyan-500 text-white shadow-lg'
                                    : 'bg-slate-100 dark:bg-slate-950 text-slate-500 hover:text-cyan-500'
                               }}">

                        All
                        <span class="ml-1">
                            {{ $counts['all'] }}
                        </span>

                    </a>

                </div>


                {{-- Search --}}
                <form
                    method="GET"
                    action="{{ route('alumni-conversion.index') }}"
                    class="flex flex-col sm:flex-row gap-2
                           w-full xl:w-auto">

                    <input
                        type="hidden"
                        name="status"
                        value="{{ $status }}">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search name, email, ID, company..."
                        class="w-full xl:w-80 rounded-xl
                               border-slate-300
                               dark:border-white/10
                               dark:bg-slate-950
                               dark:text-white
                               focus:border-cyan-500
                               focus:ring-cyan-500">

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl
                               bg-cyan-600 hover:bg-cyan-700
                               text-white font-black transition">

                        Search

                    </button>


                    @if($search !== '')
                        <a
                            href="{{ route(
                                'alumni-conversion.index',
                                ['status' => $status]
                            ) }}"
                            class="px-5 py-2.5 rounded-xl
                                   bg-slate-200
                                   dark:bg-slate-800
                                   text-slate-700
                                   dark:text-slate-200
                                   font-black text-center
                                   hover:bg-slate-300
                                   dark:hover:bg-slate-700
                                   transition">

                            Clear

                        </a>
                    @endif

                </form>

            </div>


            {{-- Current Filter Information --}}
            <div
                class="mt-5 pt-4
                       border-t border-slate-200
                       dark:border-white/10
                       flex flex-wrap items-center
                       justify-between gap-3">

                <p class="text-sm text-slate-500">

                    Showing

                    <span
                        class="font-black
                               text-slate-900 dark:text-white">

                        {{ ucfirst($status) }}

                    </span>

                    requests

                    @if($search !== '')
                        matching

                        <span
                            class="font-black text-cyan-600">
                            "{{ $search }}"
                        </span>
                    @endif

                </p>


                <p class="text-sm text-slate-500">

                    Results:

                    <span
                        class="font-black
                               text-slate-900 dark:text-white">

                        {{ $requests->total() }}

                    </span>

                </p>

            </div>

        </div>


        {{-- ================================================================
        | Requests Table
        ================================================================= --}}

        <div
            class="rounded-3xl bg-white dark:bg-slate-900
                   border border-slate-200 dark:border-white/10
                   shadow-2xl overflow-hidden">


            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead
                        class="bg-slate-100 dark:bg-slate-950">

                        <tr>

                            <th
                                class="px-5 py-4 text-left text-xs
                                       uppercase tracking-widest
                                       text-slate-500">

                                Student

                            </th>


                            <th
                                class="px-5 py-4 text-left text-xs
                                       uppercase tracking-widest
                                       text-slate-500">

                                Graduation

                            </th>


                            <th
                                class="px-5 py-4 text-left text-xs
                                       uppercase tracking-widest
                                       text-slate-500">

                                Career

                            </th>


                            <th
                                class="px-5 py-4 text-left text-xs
                                       uppercase tracking-widest
                                       text-slate-500">

                                Status

                            </th>


                            <th
                                class="px-5 py-4 text-right text-xs
                                       uppercase tracking-widest
                                       text-slate-500">

                                Action

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y divide-slate-200
                               dark:divide-white/10">


                        @forelse($requests as $item)

                            <tr
                                class="hover:bg-slate-50
                                       dark:hover:bg-white/5
                                       transition">


                                {{-- Student --}}
                                <td class="px-5 py-5">

                                    <p
                                        class="font-black
                                               text-slate-900
                                               dark:text-white">

                                        {{ $item->user->name ?? 'Unknown User' }}

                                    </p>


                                    <p
                                        class="text-sm text-slate-500">

                                        {{ $item->user->email ?? 'No email' }}

                                    </p>


                                    <p
                                        class="text-xs text-cyan-600
                                               font-bold mt-1">

                                        Student ID:
                                        {{ $item->student_id ?? 'N/A' }}

                                    </p>

                                </td>


                                {{-- Graduation --}}
                                <td class="px-5 py-5">

                                    <p
                                        class="font-black
                                               text-slate-900
                                               dark:text-white">

                                        {{ $item->graduation_year }}

                                    </p>


                                    <p
                                        class="text-xs
                                               text-slate-500">

                                        Requested
                                        {{ $item->created_at->diffForHumans() }}

                                    </p>

                                </td>


                                {{-- Career --}}
                                <td
                                    class="px-5 py-5
                                           text-sm text-slate-500">

                                    <p>
                                        {{ $item->current_company ?: 'No company' }}
                                    </p>

                                    <p>
                                        {{ $item->designation ?: 'No designation' }}
                                    </p>


                                    @if($item->supporting_document)

                                        <a
                                            href="{{ asset(
                                                'storage/' .
                                                $item->supporting_document
                                            ) }}"
                                            target="_blank"
                                            class="inline-block mt-2
                                                   text-cyan-600
                                                   font-black
                                                   hover:underline">

                                            View Document

                                        </a>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-5">

                                    @if($item->status === 'pending')

                                        <span
                                            class="inline-flex
                                                   px-4 py-2
                                                   rounded-full
                                                   text-xs font-black
                                                   bg-amber-500/10
                                                   text-amber-600">

                                            Pending

                                        </span>

                                    @elseif($item->status === 'approved')

                                        <span
                                            class="inline-flex
                                                   px-4 py-2
                                                   rounded-full
                                                   text-xs font-black
                                                   bg-emerald-500/10
                                                   text-emerald-600">

                                            Approved

                                        </span>

                                    @else

                                        <span
                                            class="inline-flex
                                                   px-4 py-2
                                                   rounded-full
                                                   text-xs font-black
                                                   bg-red-500/10
                                                   text-red-600">

                                            Rejected

                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-5">

                                    @if($item->status === 'pending')

                                        <div
                                            class="flex flex-col
                                                   gap-3 items-end">


                                            {{-- Approve Form --}}
                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'alumni-conversion.approve',
                                                    $item
                                                ) }}"
                                                class="w-full max-w-xs">

                                                @csrf
                                                @method('PATCH')


                                                <textarea
                                                    name="admin_notes"
                                                    rows="2"
                                                    placeholder="Admin note optional..."
                                                    class="w-full rounded-xl
                                                           border-slate-300
                                                           dark:border-white/10
                                                           dark:bg-slate-950
                                                           dark:text-white
                                                           text-sm"></textarea>


                                                <button
                                                    type="submit"
                                                    onclick="return confirm(
                                                        'Convert this student to alumni?'
                                                    )"
                                                    class="mt-2 w-full
                                                           px-4 py-2
                                                           rounded-xl
                                                           bg-emerald-500
                                                           text-white
                                                           font-black
                                                           hover:bg-emerald-600
                                                           transition">

                                                    Approve & Convert

                                                </button>

                                            </form>


                                            {{-- Reject Form --}}
                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'alumni-conversion.reject',
                                                    $item
                                                ) }}"
                                                class="w-full max-w-xs">

                                                @csrf
                                                @method('PATCH')


                                                <textarea
                                                    name="admin_notes"
                                                    rows="2"
                                                    required
                                                    placeholder="Rejection reason required..."
                                                    class="w-full rounded-xl
                                                           border-slate-300
                                                           dark:border-white/10
                                                           dark:bg-slate-950
                                                           dark:text-white
                                                           text-sm"></textarea>


                                                <button
                                                    type="submit"
                                                    onclick="return confirm(
                                                        'Reject this request?'
                                                    )"
                                                    class="mt-2 w-full
                                                           px-4 py-2
                                                           rounded-xl
                                                           bg-red-500
                                                           text-white
                                                           font-black
                                                           hover:bg-red-600
                                                           transition">

                                                    Reject

                                                </button>

                                            </form>

                                        </div>


                                    @else

                                        <div
                                            class="text-right text-sm">

                                            <p
                                                class="font-black
                                                       text-slate-700
                                                       dark:text-slate-200">

                                                Processed

                                            </p>


                                            <p class="text-slate-500">

                                                {{ $item->approved_at?->format(
                                                    'd M Y h:i A'
                                                ) }}

                                            </p>


                                            <p class="text-slate-500">

                                                By:
                                                {{ $item->approvedBy->name ?? 'N/A' }}

                                            </p>


                                            @if($item->admin_notes)

                                                <div
                                                    class="mt-3
                                                           max-w-xs
                                                           ml-auto
                                                           rounded-xl
                                                           bg-slate-100
                                                           dark:bg-slate-950
                                                           p-3
                                                           text-left">

                                                    <p
                                                        class="text-xs
                                                               uppercase
                                                               tracking-wider
                                                               font-black
                                                               text-slate-400">

                                                        Admin Note

                                                    </p>

                                                    <p
                                                        class="mt-1
                                                               text-xs
                                                               text-slate-600
                                                               dark:text-slate-300">

                                                        {{ $item->admin_notes }}

                                                    </p>

                                                </div>

                                            @endif

                                        </div>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="px-6 py-16
                                           text-center">

                                    <p
                                        class="text-lg font-black
                                               text-slate-700
                                               dark:text-slate-300">

                                        No conversion requests found.

                                    </p>


                                    <p
                                        class="mt-2 text-sm
                                               text-slate-500">

                                        @if($search !== '')

                                            No request matches
                                            "{{ $search }}".

                                        @elseif($status === 'pending')

                                            There are no pending requests
                                            right now.

                                        @elseif($status === 'approved')

                                            No approved requests found.

                                        @elseif($status === 'rejected')

                                            No rejected requests found.

                                        @else

                                            No alumni conversion request
                                            has been submitted yet.

                                        @endif

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ============================================================
            | Pagination
            ============================================================= --}}

            @if($requests->hasPages())

                <div
                    class="p-6 border-t
                           border-slate-200
                           dark:border-white/10">

                    {{ $requests->links() }}

                </div>

            @endif

        </div>

    </div>

    <style>
        .uc-conversion-review > .grid { gap: 12px; }
        .uc-conversion-review a[class*="p-6"] { padding: 14px !important; border-radius: 16px; }
        .uc-conversion-review :is(h3,.text-3xl) { font-size: 22px; }
        .uc-conversion-review textarea { border-radius: 10px; }
        .uc-conversion-review td { vertical-align: top; }
        .uc-conversion-review button[type=submit]:focus-visible { outline: 2px solid #a5b4fc; outline-offset: 3px; }
    </style>
</x-app-layout>