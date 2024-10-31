<?php

// SSLCommerz configuration

return [
    'projectPath' => env('PROJECT_PATH'),
    // For Sandbox, use "https://sandbox.sslcommerz.com"
    // For Live, use "https://securepay.sslcommerz.com"
    'apiDomain' => env("API_DOMAIN_URL", "https://sandbox.sslcommerz.com"),
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
    'connect_from_localhost' => env("IS_LOCALHOST", true), // For Sandbox, use "true", For Live, use "false"
    'success_url' => env("ONLINE_SUCCESS_PAYMENT"),//"/online/payment/success",//env("ONLINE_SUCCESS_PAYMENT"),
    'failed_url' => env("ONLINE_FAIL_PAYMENT"),//"/online/payment/fail",//env("ONLINE_FAIL_PAYMENT"),
    'cancel_url' => env("ONLINE_CANCEL_PAYMENT"),//"/online/payment/cancel",//env("ONLINE_CANCEL_PAYMENT"),
    'ipn_url' => env("ONLINE_PAYMENT_IPN"),//"/online/payment/ipn",//env("ONLINE_PAYMENT_IPN"),
];