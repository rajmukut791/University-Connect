<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeDonationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VISA / Card Checkout
    |--------------------------------------------------------------------------
    */

    public function checkout(
        Request $request,
        Donation $donation
    ) {
        abort_unless(
            $donation->status === 'approved' && \App\Services\PostLifecycleService::accepting($donation),
            403
        );


        if (!config('services.stripe.secret') || !config('services.stripe.webhook_secret')) {
            throw \Illuminate\Validation\ValidationException::withMessages(['amount'=>'Card payments are not available until payment verification is configured.']);
        }
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],
        ]);


        Stripe::setApiKey(
            config('services.stripe.secret')
        );


        $amount = round(
            (float) $validated['amount'],
            2
        );


        $currency = strtolower(
            config(
                'services.stripe.currency',
                'bdt'
            )
        );


        $session = Session::create([
            'payment_method_types' => [
                'card',
            ],

            'mode' => 'payment',

            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currency,

                        'product_data' => [
                            'name' =>
                                $donation->title,

                            'description' =>
                                'Donation for University Connect campaign',
                        ],

                        'unit_amount' =>
                            (int) round(
                                $amount * 100
                            ),
                    ],

                    'quantity' => 1,
                ],
            ],

            'metadata' => [
                'donation_id' =>
                    (string) $donation->id,

                'user_id' =>
                    (string) auth()->id(),

                'amount' =>
                    (string) $amount,
            ],

            'success_url' =>
                route(
                    'donations.stripe.success',
                    $donation
                ) .
                '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' =>
                route(
                    'donations.stripe.cancel',
                    $donation
                ),
        ]);


        return redirect()->away(
            $session->url
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Successful Card Payment
    |--------------------------------------------------------------------------
    */

    public function success(
        Request $request,
        Donation $donation
    ) {
        $sessionId =
            $request->query('session_id');


        if (!$sessionId) {
            return redirect()
                ->route(
                    'donations.show',
                    $donation
                )
                ->with(
                    'error',
                    'Payment session not found.'
                );
        }


        Stripe::setApiKey(
            config('services.stripe.secret')
        );


        $session =
            Session::retrieve(
                $sessionId
            );


        if (
            $session->payment_status
            !== 'paid'
        ) {
            return redirect()
                ->route(
                    'donations.show',
                    $donation
                )
                ->with(
                    'error',
                    'Card payment was not completed.'
                );
        }


        app(\App\Services\DonationCardPaymentService::class)->record($session, $donation->id, (int) $request->user()->id);

        return redirect()
            ->route(
                'donations.show',
                $donation
            )
            ->with(
                'success',
                'VISA/Card payment successful. Thank you for your donation!'
            );
    }


    public function cancel(
        Donation $donation
    ) {
        return redirect()
            ->route(
                'donations.show',
                $donation
            )
            ->with(
                'error',
                'Card payment was cancelled.'
            );
    }
}