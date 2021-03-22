<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | Here you can specify the gateway that the facade should use by default.
    |
    */
    'gateway' => env('OMNIPAY_GATEWAY', 'PayPal_Rest'),

    /*
    |--------------------------------------------------------------------------
    | Default settings
    |--------------------------------------------------------------------------
    |
    | Here you can specify default settings for gateways.
    |
    */
    'defaults' => [
        'testMode' => env('OMNIPAY_TESTMODE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gateway specific settings
    |--------------------------------------------------------------------------
    |
    | Here you can specify gateway specific settings.
    |
    */
    'gateways' => [
        'PayPal_Rest' => [
            'clientId' => env('OMNIPAY_PAYPAL_CLIENTID'),
            'secret' => env('OMNIPAY_PAYPAL_SECRET')
        ],
    ],

];
