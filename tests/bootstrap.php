<?php

// Mock Laravel's config helper function for testing
if (!function_exists('config')) {
    function config($key, $default = null) {
        $configs = [
            'payu.env_prod' => false,
            'payu.key' => 'test_key',
            'payu.salt' => 'test_salt',
            'payu.success_url' => 'http://localhost/success',
            'payu.failure_url' => 'http://localhost/failure',
            'payu.urls' => [
                'sandbox' => [
                    'payment' => 'https://test.payu.in/_payment',
                    'api' => 'https://test.payu.in/merchant/postservice.php?form=2'
                ],
                'production' => [
                    'payment' => 'https://secure.payu.in/_payment',
                    'api' => 'https://info.payu.in/merchant/postservice.php?form=2'
                ]
            ]
        ];

        return $configs[$key] ?? $default;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
