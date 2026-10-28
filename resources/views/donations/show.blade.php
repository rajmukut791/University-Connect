<x-app-layout>
    <div class="mx-auto max-w-7xl px-4"><x-post-lifecycle-status :post="$donation" /><x-post-actions :post="$donation" type="donation" :post-id="$donation->id" :owner-id="$donation->user_id" /></div>


    @php
        $user = auth()->user();

        $isManagement = $user &&
            in_array($user->role, ['admin', 'super_admin'], true);

        $isOwner = $user &&
            (int) $donation->user_id === (int) $user->id;

        $targetAmount = (float) ($donation->target_amount ?? 0);
        $collectedAmount = (float) ($donation->collected_amount ?? 0);

        $progress = $targetAmount > 0
            ? min(100, ($collectedAmount / $targetAmount) * 100)
            : 0;

        $remainingAmount = max(
            0,
            $targetAmount - $collectedAmount
        );

        $status = strtolower($donation->status ?? 'pending');

        $category = $donation->category
            ? ucwords(str_replace(['_', '-'], ' ', $donation->category))
            : 'Donation Campaign';

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Receiving numbers belong to this campaign and are supplied by its owner.
        */

        $bkashNumber = $donation->bkash_number;
        $nagadNumber = $donation->nagad_number;
    @endphp
    @if($isManagement || $isOwner)
        <div class="mx-auto my-5 max-w-7xl px-4"><a href="{{ route('donations.management', $donation) }}" class="inline-block rounded-xl bg-indigo-600 px-5 py-3 font-bold text-white">Donation Management / Transactions</a></div>
    @endif



    {{-- ============================================================= --}}
    {{-- HEADER --}}
    {{-- ============================================================= --}}

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-[30px]
                    bg-gradient-to-r
                    from-slate-950
                    via-indigo-950
                    to-purple-950
                    border border-white/10
                    shadow-2xl
                    p-6 md:p-8">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.20),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.20),transparent_35%)]">
            </div>

            <div class="relative z-10
                        flex flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-6">

                <div>

                    <div class="flex flex-wrap items-center gap-2">

                        <span class="inline-flex items-center
                                     rounded-full
                                     bg-cyan-500/15
                                     border border-cyan-400/20
                                     px-3 py-1
                                     text-xs font-black
                                     uppercase tracking-wider
                                     text-cyan-300">

                            Donation Campaign

                        </span>


                        @if($status === 'approved')

                            <span class="uc-header-context inline-flex items-center
                                         rounded-full
                                         bg-emerald-500/15
                                         border border-emerald-400/20
                                         px-3 py-1
                                         text-xs font-black
                                         uppercase
                                         text-emerald-300">

                                Approved

                            </span>

                        @elseif($status === 'pending')

                            <span class="uc-header-context inline-flex items-center
                                         rounded-full
                                         bg-amber-500/15
                                         border border-amber-400/20
                                         px-3 py-1
                                         text-xs font-black
                                         uppercase
                                         text-amber-300">

                                Pending

                            </span>

                        @elseif($status === 'rejected')

                            <span class="uc-header-context inline-flex items-center
                                         rounded-full
                                         bg-red-500/15
                                         border border-red-400/20
                                         px-3 py-1
                                         text-xs font-black
                                         uppercase
                                         text-red-300">

                                Rejected

                            </span>

                        @endif

                    </div>


                    <h1 class="uc-header-context mt-4
                               text-3xl md:text-4xl
                               font-black text-white">

                        {{ $donation->title }}

                    </h1>


                    <p class="uc-header-context mt-2 text-slate-300">

                        {{ $category }}

                    </p>

                </div>


                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('donations.index') }}"
                       class="inline-flex items-center
                              justify-center gap-2
                              rounded-2xl
                              bg-white/10
                              border border-white/10
                              px-5 py-3
                              text-sm font-black
                              text-white
                              hover:bg-white/20
                              transition">

                        <i class="fas fa-arrow-left"></i>

                        All Campaigns

                    </a>


                    @if($isOwner)

                        <a href="{{ route('donations.management', $donation) }}"
                           class="inline-flex items-center
                                  justify-center gap-2
                                  rounded-2xl
                                  bg-gradient-to-r
                                  from-cyan-500
                                  to-blue-600
                                  px-5 py-3
                                  text-sm font-black
                                  text-white
                                  shadow-xl
                                  hover:scale-[1.02]
                                  transition">

                            <i class="fas fa-receipt"></i>

                            Payment Verification

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </x-slot>



    {{-- ============================================================= --}}
    {{-- PAGE --}}
    {{-- ============================================================= --}}

    <div class="max-w-6xl mx-auto space-y-6">


        {{-- ========================================================= --}}
        {{-- FLASH MESSAGES --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="rounded-2xl
                        border border-emerald-500/30
                        bg-emerald-500/10
                        p-4
                        text-emerald-600
                        dark:text-emerald-300
                        font-bold">

                <i class="fas fa-circle-check mr-2"></i>

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="rounded-2xl
                        border border-red-500/30
                        bg-red-500/10
                        p-4
                        text-red-600
                        dark:text-red-300
                        font-bold">

                <i class="fas fa-circle-exclamation mr-2"></i>

                {{ session('error') }}

            </div>

        @endif


        @if($errors->any())

            <div class="rounded-2xl
                        border border-red-500/30
                        bg-red-500/10
                        p-5">

                <p class="font-black text-red-500">

                    Please correct the following:

                </p>

                <ul class="mt-2 list-disc list-inside
                           text-sm text-red-500 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- CAMPAIGN IMAGE --}}
        {{-- ========================================================= --}}

        @if($donation->image)

            <div class="relative overflow-hidden
                        rounded-[30px]
                        bg-slate-950
                        border border-white/10
                        shadow-2xl">

                <img
                    src="{{ route('secure.donations.image', $donation) }}"
                    alt="{{ $donation->title }}"
                    class="w-full
                           max-h-[500px]
                           object-contain
                           bg-slate-950"
                >


                <div class="absolute top-5 left-5">

                    <span class="rounded-full
                                 bg-black/60
                                 backdrop-blur-xl
                                 border border-white/20
                                 px-4 py-2
                                 text-xs font-black
                                 text-white">

                        {{ $category }}

                    </span>

                </div>


                <div class="absolute top-5 right-5">

                    <span class="rounded-full
                                 bg-emerald-500
                                 px-4 py-2
                                 text-xs font-black
                                 text-white
                                 shadow-lg">

                        {{ number_format($progress, 0) }}%

                    </span>

                </div>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- CAMPAIGN INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="rounded-[30px]
                    border border-slate-200
                    dark:border-white/10
                    bg-white
                    dark:bg-slate-900
                    shadow-2xl
                    overflow-hidden">

            <div class="p-6 md:p-8">


                <div class="flex flex-wrap items-center gap-2">

                    @if($status === 'approved')

                        <span class="uc-header-context rounded-full
                                     bg-emerald-500/15
                                     px-3 py-1
                                     text-xs font-black
                                     text-emerald-500">

                            Approved

                        </span>

                    @elseif($status === 'pending')

                        <span class="rounded-full
                                     bg-amber-500/15
                                     px-3 py-1
                                     text-xs font-black
                                     text-amber-500">

                            Pending Approval

                        </span>

                    @else

                        <span class="rounded-full
                                     bg-red-500/15
                                     px-3 py-1
                                     text-xs font-black
                                     text-red-500">

                            {{ ucfirst($status) }}

                        </span>

                    @endif


                    <span class="rounded-full
                                 bg-cyan-500/15
                                 px-3 py-1
                                 text-xs font-black
                                 text-cyan-500">

                        {{ $category }}

                    </span>

                </div>


                @if($donation->description)

                    <div class="mt-6">

                        <h3 class="text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            About This Campaign

                        </h3>

                        <p class="mt-3
                                  leading-7
                                  whitespace-pre-line
                                  text-slate-600
                                  dark:text-slate-300">

                            {{ $donation->description }}

                        </p>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- FUNDING PROGRESS --}}
                {{-- ================================================= --}}

                <div class="mt-7
                            rounded-3xl
                            bg-slate-100
                            dark:bg-slate-950
                            border
                            border-slate-200
                            dark:border-white/10
                            p-5 md:p-6">

                    <div class="flex flex-col
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                                gap-4">

                        <div>

                            <p class="text-xs
                                      uppercase tracking-wider
                                      font-bold
                                      text-slate-500">

                                Collected

                            </p>

                            <p class="mt-1
                                      text-2xl
                                      font-black
                                      text-emerald-500">

                                ৳{{ number_format($collectedAmount, 2) }}

                            </p>

                        </div>


                        <div class="sm:text-right">

                            <p class="text-xs
                                      uppercase tracking-wider
                                      font-bold
                                      text-slate-500">

                                Target

                            </p>

                            <p class="mt-1
                                      text-2xl
                                      font-black
                                      text-slate-900
                                      dark:text-white">

                                ৳{{ number_format($targetAmount, 2) }}

                            </p>

                        </div>

                    </div>


                    <div class="mt-5 h-3
                                overflow-hidden
                                rounded-full
                                bg-slate-200
                                dark:bg-slate-800">

                        <div
                            class="h-full
                                   rounded-full
                                   bg-gradient-to-r
                                   from-cyan-500
                                   via-blue-500
                                   to-emerald-500
                                   transition-all duration-500"
                            style="width: {{ $progress }}%">
                        </div>

                    </div>


                    <div class="mt-3
                                flex flex-col
                                sm:flex-row
                                sm:justify-between
                                gap-2
                                text-xs font-bold
                                text-slate-500">

                        <span>
                            {{ number_format($progress, 1) }}% funded
                        </span>

                        <span>
                            Remaining:
                            ৳{{ number_format($remainingAmount, 2) }}
                        </span>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- CAMPAIGN META --}}
                {{-- ================================================= --}}

                <div class="mt-5
                            grid grid-cols-1
                            md:grid-cols-2
                            gap-4">


                    <div class="rounded-2xl
                                bg-slate-100
                                dark:bg-slate-950
                                border
                                border-slate-200
                                dark:border-white/10
                                p-5">

                        <p class="text-xs font-bold text-slate-500">
                            Posted By
                        </p>

                        <p class="mt-2 font-black
                                  text-slate-900
                                  dark:text-white">

                            {{ $donation->user->name ?? 'Unknown User' }}

                        </p>

                    </div>


                    <div class="rounded-2xl
                                bg-slate-100
                                dark:bg-slate-950
                                border
                                border-slate-200
                                dark:border-white/10
                                p-5">

                        <p class="text-xs font-bold text-slate-500">
                            Deadline
                        </p>

                        <p class="mt-2 font-black
                                  text-slate-900
                                  dark:text-white">

                            @if($donation->deadline)

                                {{ \Carbon\Carbon::parse($donation->deadline)->format('d M, Y') }}

                            @else

                                No deadline

                            @endif

                        </p>

                    </div>

                </div>


            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- PAYMENT SYSTEM --}}
        {{-- Only approved campaigns can receive donations --}}
        {{-- ========================================================= --}}

        @if($status === 'approved' && !$isOwner && !$isManagement && \App\Services\PostLifecycleService::accepting($donation))

            <div class="relative overflow-hidden
                        rounded-[30px]
                        bg-gradient-to-br
                        from-indigo-500/10
                        via-cyan-500/10
                        to-emerald-500/10
                        border border-cyan-500/20
                        p-5 md:p-8
                        shadow-2xl">

                <div class="absolute -top-24 -right-24
                            w-64 h-64
                            rounded-full
                            bg-cyan-400/10
                            blur-3xl">
                </div>


                <div class="relative z-10">


                    <div class="text-center">

                        <div class="mx-auto
                                    h-16 w-16
                                    rounded-2xl
                                    bg-gradient-to-br
                                    from-indigo-500
                                    via-cyan-500
                                    to-emerald-500
                                    flex items-center
                                    justify-center
                                    text-white
                                    text-2xl
                                    shadow-xl">

                            <i class="fas fa-hand-holding-heart"></i>

                        </div>


                        <h2 class="mt-4
                                   text-2xl md:text-3xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            Make a Donation

                        </h2>


                        <p class="mt-2
                                  text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Choose your preferred payment method

                        </p>

                    </div>



                    {{-- ================================================= --}}
                    {{-- PAYMENT METHOD SELECTOR --}}
                    {{-- ================================================= --}}

                    <div class="mt-7
                                grid grid-cols-1
                                sm:grid-cols-3
                                gap-4">


                        {{-- bKash --}}

                        <button
                            type="button"
                            onclick="selectDonationMethod('bkash')"
                            id="payment-btn-bkash" @disabled(!$bkashNumber) title="{{ $bkashNumber ? 'Pay using bKash' : 'bKash receiving number not provided by campaign owner' }}"
                            class="payment-method-btn
                                   rounded-2xl
                                   border-2
                                   border-transparent
                                   bg-white/80
                                   dark:bg-slate-950/70
                                   p-5
                                   text-center
                                   shadow-sm
                                   hover:-translate-y-1
                                   hover:shadow-lg
                                   transition duration-300"
                        >

                            <div class="mx-auto
                                        h-12 w-12
                                        rounded-2xl
                                        bg-pink-500/15
                                        flex items-center
                                        justify-center">

                                <span class="text-xl
                                             font-black
                                             text-pink-500">

                                    bK

                                </span>

                            </div>


                            <p class="mt-3
                                      text-xl font-black
                                      text-pink-500">

                                bKash

                            </p>

                            <p class="mt-1
                                      text-xs
                                      text-slate-500">

                                Mobile Banking

                            </p>

                        </button>



                        {{-- Nagad --}}

                        <button
                            type="button"
                            onclick="selectDonationMethod('nagad')"
                            id="payment-btn-nagad" @disabled(!$nagadNumber) title="{{ $nagadNumber ? 'Pay using Nagad' : 'Nagad receiving number not provided by campaign owner' }}"
                            class="payment-method-btn
                                   rounded-2xl
                                   border-2
                                   border-transparent
                                   bg-white/80
                                   dark:bg-slate-950/70
                                   p-5
                                   text-center
                                   shadow-sm
                                   hover:-translate-y-1
                                   hover:shadow-lg
                                   transition duration-300"
                        >

                            <div class="mx-auto
                                        h-12 w-12
                                        rounded-2xl
                                        bg-orange-500/15
                                        flex items-center
                                        justify-center">

                                <span class="text-xl
                                             font-black
                                             text-orange-500">

                                    N

                                </span>

                            </div>


                            <p class="mt-3
                                      text-xl font-black
                                      text-orange-500">

                                Nagad

                            </p>

                            <p class="mt-1
                                      text-xs
                                      text-slate-500">

                                Mobile Banking

                            </p>

                        </button>



                        {{-- VISA / Card --}}

                        <button
                            type="button"
                            onclick="selectDonationMethod('visa')"
                            id="payment-btn-visa" @disabled(!config('services.stripe.secret') || !config('services.stripe.webhook_secret')) title="Card payments require verified payment-provider configuration"
                            class="payment-method-btn
                                   rounded-2xl
                                   border-2
                                   border-transparent
                                   bg-white/80
                                   dark:bg-slate-950/70
                                   p-5
                                   text-center
                                   shadow-sm
                                   hover:-translate-y-1
                                   hover:shadow-lg
                                   transition duration-300"
                        >

                            <div class="mx-auto
                                        h-12 w-12
                                        rounded-2xl
                                        bg-blue-500/15
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-credit-card
                                          text-xl
                                          text-blue-500">
                                </i>

                            </div>


                            <p class="mt-3
                                      text-xl font-black
                                      text-blue-500">

                                VISA / Card

                            </p>

                            <p class="mt-1
                                      text-xs
                                      text-slate-500">

                                Secure Card Payment

                            </p>

                        </button>


                    </div>



                    {{-- ================================================= --}}
                    {{-- BKASH PANEL --}}
                    {{-- ================================================= --}}

                    <div
                        id="payment-panel-bkash"
                        class="payment-panel mt-6 hidden"
                    >

                        <div class="rounded-3xl
                                    bg-pink-500/10
                                    border border-pink-500/20
                                    p-5 md:p-6">


                            <div class="flex items-start gap-4">

                                <div class="h-12 w-12
                                            shrink-0
                                            rounded-2xl
                                            bg-pink-500
                                            text-white
                                            flex items-center
                                            justify-center
                                            font-black">

                                    bK

                                </div>


                                <div>

                                    <h3 class="text-xl font-black
                                               text-slate-900
                                               dark:text-white">

                                        Pay with bKash

                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">

                                        Send Money first, then submit your
                                        transaction information for verification.

                                    </p>

                                </div>

                            </div>



                            <div class="mt-5
                                        rounded-2xl
                                        bg-white/80
                                        dark:bg-slate-950/80
                                        border
                                        border-pink-500/10
                                        p-5">

                                <p class="text-xs
                                          uppercase tracking-wider
                                          font-black
                                          text-slate-500">

                                    Send Money To

                                </p>


                                <div class="mt-2
                                            flex flex-wrap
                                            items-center
                                            justify-between
                                            gap-3">

                                    <p class="text-2xl
                                              font-black
                                              text-pink-500">

                                        {{ $bkashNumber ?: 'Not configured by campaign owner' }}

                                    </p>


                                    <span class="rounded-full
                                                 bg-pink-500/15
                                                 px-3 py-1
                                                 text-xs font-black
                                                 text-pink-500">

                                        bKash

                                    </span>

                                </div>


                                <p class="mt-3
                                          text-xs
                                          font-bold
                                          text-amber-500">

                                    Send the donation using Send Money and
                                    save the Transaction ID.

                                </p>

                            </div>



                            <form
                                action="{{ route('donations.manual-payment', $donation) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="mt-6 space-y-5"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="payment_method"
                                    value="bKash"
                                >


                                <div class="grid
                                            grid-cols-1
                                            md:grid-cols-2
                                            gap-5">


                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Your bKash Number

                                        </label>

                                        <input
                                            type="text"
                                            name="account_number"
                                            required
                                            maxlength="30"
                                            placeholder="01XXXXXXXXX"
                                            class="w-full
                                                   rounded-2xl
                                                   border-slate-300
                                                   dark:border-white/10
                                                   dark:bg-slate-950
                                                   dark:text-white
                                                   focus:border-pink-500
                                                   focus:ring-pink-500"
                                        >

                                    </div>



                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Transaction ID

                                        </label>

                                        <input
                                            type="text"
                                            name="transaction_id"
                                            required
                                            maxlength="150"
                                            placeholder="Enter Transaction ID"
                                            class="w-full
                                                   rounded-2xl
                                                   border-slate-300
                                                   dark:border-white/10
                                                   dark:bg-slate-950
                                                   dark:text-white
                                                   focus:border-pink-500
                                                   focus:ring-pink-500"
                                        >

                                    </div>



                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Donation Amount (৳)

                                        </label>

                                        <input
                                            type="number"
                                            name="amount"
                                            required
                                            min="1"
                                            step="0.01"
                                            placeholder="500"
                                            class="w-full
                                                   rounded-2xl
                                                   border-slate-300
                                                   dark:border-white/10
                                                   dark:bg-slate-950
                                                   dark:text-white
                                                   focus:border-pink-500
                                                   focus:ring-pink-500"
                                        >

                                    </div>



                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Payment Screenshot

                                            <span class="text-slate-400
                                                         font-normal">
                                                (optional)
                                            </span>

                                        </label>

                                        <input
                                            type="file"
                                            name="screenshot"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="block w-full
                                                   rounded-2xl
                                                   border
                                                   border-slate-300
                                                   dark:border-white/10
                                                   bg-white
                                                   dark:bg-slate-950
                                                   text-sm
                                                   text-slate-600
                                                   dark:text-slate-300
                                                   file:border-0
                                                   file:bg-pink-500
                                                   file:text-white
                                                   file:font-bold
                                                   file:px-4
                                                   file:py-3
                                                   file:mr-4"
                                        >

                                    </div>

                                </div>



                                <div>

                                    <label class="block
                                                  mb-2
                                                  text-sm font-black
                                                  text-slate-700
                                                  dark:text-slate-300">

                                        Note

                                        <span class="text-slate-400
                                                     font-normal">
                                            (optional)
                                        </span>

                                    </label>

                                    <textarea
                                        name="note"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Add a note if needed..."
                                        class="w-full
                                               rounded-2xl
                                               border-slate-300
                                               dark:border-white/10
                                               dark:bg-slate-950
                                               dark:text-white
                                               focus:border-pink-500
                                               focus:ring-pink-500"
                                    ></textarea>

                                </div>



                                <button
                                    type="submit"
                                    class="w-full
                                           rounded-2xl
                                           bg-gradient-to-r
                                           from-pink-500
                                           to-rose-500
                                           px-5 py-4
                                           text-white
                                           font-black
                                           shadow-lg
                                           hover:scale-[1.01]
                                           transition"
                                >

                                    <i class="fas fa-paper-plane mr-2"></i>

                                    Submit bKash Payment

                                </button>


                                <p class="text-center
                                          text-xs
                                          text-slate-500">

                                    Your payment will be added after
                                    campaign owner verification.

                                </p>

                            </form>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- NAGAD PANEL --}}
                    {{-- ================================================= --}}

                    <div
                        id="payment-panel-nagad"
                        class="payment-panel mt-6 hidden"
                    >

                        <div class="rounded-3xl
                                    bg-orange-500/10
                                    border border-orange-500/20
                                    p-5 md:p-6">


                            <div class="flex items-start gap-4">

                                <div class="h-12 w-12
                                            shrink-0
                                            rounded-2xl
                                            bg-orange-500
                                            text-white
                                            flex items-center
                                            justify-center
                                            font-black">

                                    N

                                </div>


                                <div>

                                    <h3 class="text-xl font-black
                                               text-slate-900
                                               dark:text-white">

                                        Pay with Nagad

                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">

                                        Send Money first, then submit your
                                        transaction information for verification.

                                    </p>

                                </div>

                            </div>



                            <div class="mt-5
                                        rounded-2xl
                                        bg-white/80
                                        dark:bg-slate-950/80
                                        border
                                        border-orange-500/10
                                        p-5">

                                <p class="text-xs
                                          uppercase tracking-wider
                                          font-black
                                          text-slate-500">

                                    Send Money To

                                </p>


                                <div class="mt-2
                                            flex flex-wrap
                                            items-center
                                            justify-between
                                            gap-3">

                                    <p class="text-2xl
                                              font-black
                                              text-orange-500">

                                        {{ $nagadNumber ?: 'Not configured by campaign owner' }}

                                    </p>


                                    <span class="rounded-full
                                                 bg-orange-500/15
                                                 px-3 py-1
                                                 text-xs font-black
                                                 text-orange-500">

                                        Nagad

                                    </span>

                                </div>


                                <p class="mt-3
                                          text-xs
                                          font-bold
                                          text-amber-500">

                                    Send the donation using Send Money and
                                    save the Transaction ID.

                                </p>

                            </div>



                            <form
                                action="{{ route('donations.manual-payment', $donation) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="mt-6 space-y-5"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="payment_method"
                                    value="Nagad"
                                >


                                <div class="grid
                                            grid-cols-1
                                            md:grid-cols-2
                                            gap-5">


                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Your Nagad Number

                                        </label>

                                        <input
                                            type="text"
                                            name="account_number"
                                            required
                                            maxlength="30"
                                            placeholder="01XXXXXXXXX"
                                            class="w-full
                                                   rounded-2xl
                                                   border-slate-300
                                                   dark:border-white/10
                                                   dark:bg-slate-950
                                                   dark:text-white
                                                   focus:border-orange-500
                                                   focus:ring-orange-500"
                                        >

                                    </div>



                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Transaction ID

                                        </label>

                                        <input
                                            type="text"
                                            name="transaction_id"
                                            required
                                            maxlength="150"
                                            placeholder="Enter Transaction ID"
                                            class="w-full
                                                   rounded-2xl
                                                   border-slate-300
                                                   dark:border-white/10
                                                   dark:bg-slate-950
                                                   dark:text-white
                                                   focus:border-orange-500
                                                   focus:ring-orange-500"
                                        >

                                    </div>



                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Donation Amount (৳)

                                        </label>

                                        <input
                                            type="number"
                                            name="amount"
                                            required
                                            min="1"
                                            step="0.01"
                                            placeholder="500"
                                            class="w-full
                                                   rounded-2xl
                                                   border-slate-300
                                                   dark:border-white/10
                                                   dark:bg-slate-950
                                                   dark:text-white
                                                   focus:border-orange-500
                                                   focus:ring-orange-500"
                                        >

                                    </div>



                                    <div>

                                        <label class="block
                                                      mb-2
                                                      text-sm font-black
                                                      text-slate-700
                                                      dark:text-slate-300">

                                            Payment Screenshot

                                            <span class="text-slate-400
                                                         font-normal">
                                                (optional)
                                            </span>

                                        </label>

                                        <input
                                            type="file"
                                            name="screenshot"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="block w-full
                                                   rounded-2xl
                                                   border
                                                   border-slate-300
                                                   dark:border-white/10
                                                   bg-white
                                                   dark:bg-slate-950
                                                   text-sm
                                                   text-slate-600
                                                   dark:text-slate-300
                                                   file:border-0
                                                   file:bg-orange-500
                                                   file:text-white
                                                   file:font-bold
                                                   file:px-4
                                                   file:py-3
                                                   file:mr-4"
                                        >

                                    </div>

                                </div>



                                <div>

                                    <label class="block
                                                  mb-2
                                                  text-sm font-black
                                                  text-slate-700
                                                  dark:text-slate-300">

                                        Note

                                        <span class="text-slate-400
                                                     font-normal">
                                            (optional)
                                        </span>

                                    </label>

                                    <textarea
                                        name="note"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Add a note if needed..."
                                        class="w-full
                                               rounded-2xl
                                               border-slate-300
                                               dark:border-white/10
                                               dark:bg-slate-950
                                               dark:text-white
                                               focus:border-orange-500
                                               focus:ring-orange-500"
                                    ></textarea>

                                </div>



                                <button
                                    type="submit"
                                    class="w-full
                                           rounded-2xl
                                           bg-gradient-to-r
                                           from-orange-500
                                           to-red-500
                                           px-5 py-4
                                           text-white
                                           font-black
                                           shadow-lg
                                           hover:scale-[1.01]
                                           transition"
                                >

                                    <i class="fas fa-paper-plane mr-2"></i>

                                    Submit Nagad Payment

                                </button>


                                <p class="text-center
                                          text-xs
                                          text-slate-500">

                                    Your payment will be added after
                                    campaign owner verification.

                                </p>

                            </form>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- VISA / CARD PANEL --}}
                    {{-- ================================================= --}}

                    <div
                        id="payment-panel-visa"
                        class="payment-panel mt-6 hidden"
                    >

                        <div class="rounded-3xl
                                    bg-blue-500/10
                                    border border-blue-500/20
                                    p-5 md:p-6">


                            <div class="flex items-start gap-4">

                                <div class="h-12 w-12
                                            shrink-0
                                            rounded-2xl
                                            bg-gradient-to-br
                                            from-indigo-500
                                            to-blue-600
                                            text-white
                                            flex items-center
                                            justify-center">

                                    <i class="fas fa-credit-card"></i>

                                </div>


                                <div>

                                    <h3 class="text-xl font-black
                                               text-slate-900
                                               dark:text-white">

                                        VISA / Card Payment

                                    </h3>

                                    <p class="mt-1
                                              text-sm
                                              text-slate-500">

                                        Continue to secure Stripe Checkout
                                        to complete your card payment.

                                    </p>

                                </div>

                            </div>



                            <form
                                action="{{ route('donations.stripe.checkout', $donation) }}"
                                method="POST"
                                class="mt-6"
                            >

                                @csrf


                                <label class="block
                                              mb-2
                                              text-sm font-black
                                              text-slate-700
                                              dark:text-slate-300">

                                    Donation Amount (৳)

                                </label>


                                <div class="relative">

                                    <span class="absolute
                                                 left-4 top-1/2
                                                 -translate-y-1/2
                                                 font-black
                                                 text-slate-500">

                                        ৳

                                    </span>

                                    <input
                                        type="number"
                                        name="amount"
                                        required
                                        min="1"
                                        step="0.01"
                                        placeholder="500"
                                        class="w-full
                                               rounded-2xl
                                               border-slate-300
                                               dark:border-white/10
                                               dark:bg-slate-950
                                               dark:text-white
                                               pl-10
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                </div>



                                <button
                                    type="submit"
                                    class="mt-5
                                           w-full
                                           rounded-2xl
                                           bg-gradient-to-r
                                           from-indigo-500
                                           via-blue-500
                                           to-cyan-500
                                           px-5 py-4
                                           text-white
                                           font-black
                                           shadow-xl
                                           hover:scale-[1.01]
                                           transition"
                                >

                                    <i class="fas fa-lock mr-2"></i>

                                    Continue to Secure Payment

                                </button>


                                <div class="mt-4
                                            flex flex-wrap
                                            items-center
                                            justify-center
                                            gap-3
                                            text-xs
                                            text-slate-500">

                                    <span>
                                        <i class="fas fa-shield-halved mr-1"></i>
                                        Secure Checkout
                                    </span>

                                    <span>•</span>

                                    <span>
                                        VISA / Card
                                    </span>

                                    <span>•</span>

                                    <span>
                                        Powered by Stripe
                                    </span>

                                </div>

                            </form>

                        </div>

                    </div>


                </div>

            </div>


        @else

            {{-- Campaign is not approved --}}

            <div class="rounded-[30px]
                        border
                        {{ $status === 'pending'
                            ? 'border-amber-500/30 bg-amber-500/10'
                            : 'border-red-500/30 bg-red-500/10'
                        }}
                        p-7
                        text-center">

                <div class="mx-auto
                            h-14 w-14
                            rounded-2xl
                            flex items-center
                            justify-center
                            {{ $status === 'pending'
                                ? 'bg-amber-500/15 text-amber-500'
                                : 'bg-red-500/15 text-red-500'
                            }}">

                    <i class="fas fa-hourglass-half text-xl"></i>

                </div>


                <h3 class="mt-4
                           text-xl font-black
                           text-slate-900
                           dark:text-white">

                    @if($status === 'pending')

                        Campaign Waiting for Approval

                    @else

                        Donations Are Not Available

                    @endif

                </h3>


                <p class="mt-2
                          text-sm
                          text-slate-500">

                    @if($status === 'pending')

                        Payment options will become available
                        after Admin approval.

                    @else

                        This campaign is currently not accepting payments.

                    @endif

                </p>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- MANAGEMENT CONTROLS --}}
        {{-- ========================================================= --}}

        @if($isManagement && $status === 'pending')

            <div class="rounded-[30px]
                        bg-white
                        dark:bg-slate-900
                        border
                        border-slate-200
                        dark:border-white/10
                        shadow-xl
                        p-6">

                <div class="flex flex-col
                            lg:flex-row
                            lg:items-center
                            lg:justify-between
                            gap-5">

                    <div>

                        <p class="text-xs
                                  uppercase tracking-[0.2em]
                                  font-black
                                  text-amber-500">

                            Administration

                        </p>

                        <h3 class="mt-2
                                   text-xl font-black
                                   text-slate-900
                                   dark:text-white">

                            Campaign Approval Required

                        </h3>

                        <p class="mt-1
                                  text-sm
                                  text-slate-500">

                            Review this donation campaign before
                            allowing users to make payments.

                        </p>

                    </div>


                    <div class="flex flex-wrap gap-3">

                        <form
                            action="{{ route('donations.approve', $donation) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                onclick="return confirm('Approve this donation campaign?')"
                                class="rounded-2xl
                                       bg-emerald-500
                                       hover:bg-emerald-600
                                       px-5 py-3
                                       text-white
                                       font-black
                                       transition">

                                <i class="fas fa-check mr-2"></i>

                                Approve

                            </button>

                        </form>


                        <form
                            action="{{ route('donations.reject', $donation) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                onclick="return confirm('Reject this donation campaign?')"
                                class="rounded-2xl
                                       bg-red-500
                                       hover:bg-red-600
                                       px-5 py-3
                                       text-white
                                       font-black
                                       transition">

                                <i class="fas fa-xmark mr-2"></i>

                                Reject

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- DELETE CAMPAIGN --}}
        {{-- ========================================================= --}}

        @if($isManagement || $isOwner)

            <div class="rounded-[30px]
                        border border-red-500/20
                        bg-red-500/5
                        p-6">

                <div class="flex flex-col
                            md:flex-row
                            md:items-center
                            md:justify-between
                            gap-5">

                    <div>

                        <h3 class="font-black
                                   text-slate-900
                                   dark:text-white">

                            Campaign Management

                        </h3>

                        <p class="mt-1
                                  text-sm
                                  text-slate-500">

                            Deleting a campaign cannot be undone.

                        </p>

                    </div>


                    <form
                        action="{{ route('donations.destroy', $donation) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this donation campaign?');"
                    >

                        @csrf
                        @method('DELETE')


                        <button
                            type="submit"
                            class="rounded-2xl
                                   bg-red-500
                                   hover:bg-red-600
                                   px-5 py-3
                                   text-white
                                   font-black
                                   transition">

                            <i class="fas fa-trash mr-2"></i>

                            Delete Campaign

                        </button>

                    </form>

                </div>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- CONTRIBUTION HISTORY --}}
        {{-- ========================================================= --}}

        @if(
            isset($donation->contributions) &&
            $donation->contributions->count() > 0
        )

            <div class="rounded-[30px]
                        bg-white
                        dark:bg-slate-900
                        border
                        border-slate-200
                        dark:border-white/10
                        shadow-xl
                        p-6 md:p-7">

                <div>

                    <p class="text-xs
                              uppercase tracking-[0.2em]
                              font-black
                              text-cyan-500">

                        Community Support

                    </p>

                    <h3 class="mt-2
                               text-2xl font-black
                               text-slate-900
                               dark:text-white">

                        Recent Contributions

                    </h3>

                </div>


                <div class="mt-6 space-y-3">

                    @foreach(
                        $donation->contributions
                            ->sortByDesc('created_at')
                            ->take(10)
                        as $contribution
                    )

                        <div class="flex flex-col
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                    gap-4
                                    rounded-2xl
                                    bg-slate-100
                                    dark:bg-slate-950
                                    border
                                    border-slate-200
                                    dark:border-white/10
                                    p-4">

                            <div class="flex items-center gap-3">

                                <div class="h-11 w-11
                                            rounded-2xl
                                            bg-gradient-to-br
                                            from-cyan-500
                                            to-blue-600
                                            flex items-center
                                            justify-center
                                            text-white
                                            font-black">

                                    {{ strtoupper(
                                        substr(
                                            $contribution->user->name ?? 'U',
                                            0,
                                            1
                                        )
                                    ) }}

                                </div>


                                <div>

                                    <p class="font-black
                                              text-slate-900
                                              dark:text-white">

                                        {{ $contribution->user->name ?? 'Anonymous Donor' }}

                                    </p>


                                    @if($contribution->created_at)

                                        <p class="text-xs text-slate-500">

                                            {{ $contribution->created_at->diffForHumans() }}

                                        </p>

                                    @endif

                                </div>

                            </div>


                            <p class="text-lg
                                      font-black
                                      text-emerald-500">

                                ৳{{ number_format(
                                    (float) $contribution->amount,
                                    2
                                ) }}

                            </p>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif


    </div>



    {{-- ============================================================= --}}
    {{-- PAYMENT METHOD JAVASCRIPT --}}
    {{-- ============================================================= --}}

    <script>

        function selectDonationMethod(method) {

            /*
            |--------------------------------------------------------------------------
            | Hide all payment panels
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.payment-panel')
                .forEach(function (panel) {

                    panel.classList.add('hidden');

                });


            /*
            |--------------------------------------------------------------------------
            | Reset all payment buttons
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.payment-method-btn')
                .forEach(function (button) {

                    button.classList.remove(
                        'border-cyan-500',
                        'ring-2',
                        'ring-cyan-500/20',
                        'scale-[1.02]'
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | Show selected panel
            |--------------------------------------------------------------------------
            */

            const selectedButton = document.getElementById('payment-btn-' + method);
            if (selectedButton && selectedButton.disabled) return;
            const panel =
                document.getElementById(
                    'payment-panel-' + method
                );


            const button =
                document.getElementById(
                    'payment-btn-' + method
                );


            if (panel) {

                panel.classList.remove('hidden');

            }


            if (button) {

                button.classList.add(
                    'border-cyan-500',
                    'ring-2',
                    'ring-cyan-500/20',
                    'scale-[1.02]'
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Open bKash by default
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const bkashButton =
                    document.getElementById(
                        'payment-btn-bkash'
                    );


                if (bkashButton && !bkashButton.disabled) {

                    selectDonationMethod('bkash');

                }

            }
        );

    </script>


</x-app-layout>