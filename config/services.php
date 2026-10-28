<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Calling and Payments
    |--------------------------------------------------------------------------
    */

    'calls' => [
        'ice_servers' => array_values(array_filter([
            ['urls'=>'stun:stun.l.google.com:19302'], ['urls'=>'stun:stun1.l.google.com:19302'],
            env('TURN_URL') ? ['urls'=>env('TURN_URL'), 'username'=>env('TURN_USERNAME'), 'credential'=>env('TURN_PASSWORD')] : null,
        ])),
    ],
   'stripe' => [
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'bdt'),
],

];