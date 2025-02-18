<?php

// SSLCommerz configuration
// For Sandbox, use "https://sandbox.sslcommerz.com"
// For Live, use "https://securepay.sslcommerz.com"

// For Sandbox
// 'store_id' => 'mmltd5e3fdced02f57',
// 'store_password' => "mmltd5e3fdced02f57@ssl",

// For securepay
// 'store_id' => 'mistrimamalive',
// 'store_password' => "5D639A63C24EC49244",
return [
    'projectPath' => 'http://127.0.0.1:8000',
    'apiDomain' => env("API_DOMAIN_URL"),
    'apiCredentials' => [
        'store_id' => env("STORE_ID"),
        'store_password' => env("STORE_PASSWORD"),
    ],
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
    ],
    'connect_from_localhost' => env("IS_LOCALHOST"), // For Sandbox, use "true", For Live, use "false"
    'success_url' => '/success',
    'failed_url' => '/fail',
    'cancel_url' => '/cancel',
    'ipn_url' => '/ipn',
];
