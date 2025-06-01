<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayU Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the PayU gateway configuration options.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Set to false for sandbox/test environment
    | Set to true for production environment
    |
    */
    'env_prod' => env('PAYU_ENV_PROD', false),

    /*
    |--------------------------------------------------------------------------
    | PayU Credentials
    |--------------------------------------------------------------------------
    |
    | Your PayU merchant key and salt provided by PayU
    |
    */
    'key' => env('PAYU_KEY', ''),
    'salt' => env('PAYU_SALT', ''),

    /*
    |--------------------------------------------------------------------------
    | PayU URLs
    |--------------------------------------------------------------------------
    |
    | PayU gateway URLs for sandbox and production environments
    |
    */
    'urls' => [
        'sandbox' => [
            'payment' => 'https://sandboxsecure.payu.in/_payment',
            'api' => 'https://sandboxsecure.payu.in/merchant/postservice?form=2',
        ],
        'production' => [
            'payment' => 'https://secure.payu.in/_payment',
            'api' => 'https://info.payu.in/merchant/postservice?form=2',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default URLs
    |--------------------------------------------------------------------------
    |
    | Default success and failure URLs for PayU transactions
    | Note: These should be set in your .env file for proper functionality
    |
    */
    'success_url' => env('PAYU_SUCCESS_URL', ''),
    'failure_url' => env('PAYU_FAILURE_URL', ''),
];
