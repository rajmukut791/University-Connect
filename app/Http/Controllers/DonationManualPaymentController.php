<?php

namespace App\Http\Controllers;

use App\Services\EncryptedFileService;
use App\Models\Donation;
use App\Models\DonationManualPayment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DonationManualPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Submit bKash / Nagad Payment
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Donation $donation
    ) {
        abort_unless(
            $donation->status === 'approved' && \App\Services\PostLifecycleService::accepting($donation),
            403
        );

        $validated = $request->validate([
            'payment_method' => [
                'required',
                Rule::in([
                    'bKash',
                    'Nagad',
                ]),
            ],

            'account_number' => [
                'required',
                'string',
                'max:30',
            ],

            'transaction_id' => [
                'required',
                'string',
                'max:150',
                'unique:donation_manual_payments,transaction_id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'screenshot' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);


        $receivingNumber = $validated['payment_method'] === 'bKash' ? $donation->bkash_number : $donation->nagad_number;
        if (!$receivingNumber) throw \Illuminate\Validation\ValidationException::withMessages(['payment_method'=>'This payment method has not been configured by the campaign owner.']);
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Screenshot
        |--------------------------------------------------------------------------
        */

        $screenshotPath = null;

        $screenshotPath = null;

        if ($request->hasFile('screenshot')) {
          $screenshotPath =
         app(EncryptedFileService::class)
            ->store(
                $request->file('screenshot'),
                'donation-payments'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Pending Payment
        |--------------------------------------------------------------------------
        */

        $payment = DonationManualPayment::create([
            'donation_id' => $donation->id,

            'user_id' => $user->id,

            'payment_method' =>
                $validated['payment_method'],

            'account_number' =>
                $validated['account_number'],

            'transaction_id' =>
                $validated['transaction_id'],

            'amount' =>
                $validated['amount'],

            'note' =>
                $validated['note'] ?? null,

            'screenshot' =>
                $screenshotPath,

            'status' =>
                'pending',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Donor Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::personal(
            $user,
            'donation_payment_submitted',
            'Donation Payment Submitted',
            'Your ' .
                $payment->payment_method .
                ' payment of ৳' .
                number_format(
                    (float) $payment->amount,
                    2
                ) .
                ' has been submitted and is waiting for verification.',
            route(
                'donations.show',
                $donation
            ),
            'medium',
            $payment,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::personal(
            $donation->user_id,
            'donation_payment_pending',
            'Donation Payment Verification',
            $user->name .
                ' submitted a ' .
                $payment->payment_method .
                ' donation payment of ৳' .
                number_format(
                    (float) $payment->amount,
                    2
                ) .
                '. Transaction ID: ' .
                $payment->transaction_id,
            route(
                'donations.management', $donation
            ),
            'high',
            $payment,
            $user
        );


        return back()->with(
            'success',
            'Payment submitted successfully. It will be added to the campaign after campaign owner verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Pending Payments
    |--------------------------------------------------------------------------
    */

    public function pending()
    {
        abort_unless(Auth::user()->is_active && !Auth::user()->is_blocked, 403);

        $payments = DonationManualPayment::with([
                'user',
                'donation',
            ])
            ->where('status', 'pending')
            ->whereHas('donation', fn ($q) => $q->where('user_id', Auth::id()))
            ->oldest()
            ->paginate(20);

        return view(
            'donations.pending-payments',
            compact('payments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve(
        DonationManualPayment $payment
    ) {
        $this->ensureOwner($payment);

        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                'This payment has already been processed.'
            );
        }


        DB::transaction(function () use ($payment) {

            /*
            | Lock payment so double-click / simultaneous requests
            | cannot add the amount twice.
            */

            $lockedPayment =
                DonationManualPayment::query()
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();


            if ($lockedPayment->status !== 'pending') {
                return;
            }


            $donation =
                Donation::query()
                    ->whereKey($lockedPayment->donation_id)
                    ->lockForUpdate()
                    ->firstOrFail();


            $lockedPayment->update([
                'status' => 'approved',

                'reviewed_by' =>
                    Auth::id(),

                'reviewed_at' =>
                    now(),

                'rejection_reason' =>
                    null,
            ]);


            \App\Models\DonationContribution::create([
                'donation_id' => $donation->id, 'user_id' => $lockedPayment->user_id,
                'amount' => $lockedPayment->amount, 'donor_name' => $lockedPayment->user?->name,
                'donor_email' => $lockedPayment->user?->email, 'payment_method' => strtolower($lockedPayment->payment_method),
                'message' => $lockedPayment->note, 'status' => 'confirmed', 'is_anonymous' => false,
            ]);
            $donation->increment(
                'collected_amount',
                (float) $lockedPayment->amount
            );
        });


        $payment->refresh();


        if ($payment->user) {
            NotificationService::personal(
                $payment->user,
                'donation_payment_confirmed',
                'Donation Payment Confirmed',
                'Your ' .
                    $payment->payment_method .
                    ' donation payment of ৳' .
                    number_format(
                        (float) $payment->amount,
                        2
                    ) .
                    ' has been verified successfully.',
                route(
                    'donations.show',
                    $payment->donation_id
                ),
                'medium',
                $payment,
                Auth::user()
            );
        }


        return back()->with(
            'success',
            'Payment approved and donation amount added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        DonationManualPayment $payment
    ) {
        $this->ensureOwner($payment);


        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                'This payment has already been processed.'
            );
        }


        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);


        DB::transaction(function () use ($payment, $validated) {
            $locked = DonationManualPayment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            abort_unless($locked->status === 'pending', 409, 'This payment has already been processed.');
            $locked->update(['status' => 'rejected', 'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'rejection_reason' => $validated['rejection_reason']]);
        });


        if ($payment->user) {
            NotificationService::personal(
                $payment->user,
                'donation_payment_rejected',
                'Donation Payment Rejected',
                'Your donation payment could not be verified. Reason: ' .
                    $validated['rejection_reason'],
                route(
                    'donations.show',
                    $payment->donation_id
                ),
                'high',
                $payment,
                Auth::user()
            );
        }


        return back()->with(
            'success',
            'Payment rejected successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Admin / Super Admin Authorization
    |--------------------------------------------------------------------------
    */

    private function ensureOwner(DonationManualPayment $payment): void
    {
        abort_unless(Auth::check() && Auth::user()->is_active && !Auth::user()->is_blocked
            && (int) $payment->donation?->user_id === (int) Auth::id(), 403);
    }
}
