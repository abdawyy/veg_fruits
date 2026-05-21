<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Branding & legal sender (invoices, PDFs, notices)
    |--------------------------------------------------------------------------
    */
    'invoice_sender_name' => env('ALDAWY_INVOICE_SENDER_NAME', 'abdelrahman mohamed'),

    'currency' => env('ALDAWY_CURRENCY', 'EGP'),

    'decimal_scale' => (int) env('ALDAWY_DECIMAL_SCALE', 4),

    /*
    |--------------------------------------------------------------------------
    | SMS (driver: log | http)
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'driver' => env('ALDAWY_SMS_DRIVER', 'log'),
        'http' => [
            'url' => env('ALDAWY_SMS_HTTP_URL'),
            'method' => env('ALDAWY_SMS_HTTP_METHOD', 'POST'),
            'phone_key' => env('ALDAWY_SMS_HTTP_PHONE_KEY', 'to'),
            'message_key' => env('ALDAWY_SMS_HTTP_MESSAGE_KEY', 'message'),
            'api_key' => env('ALDAWY_SMS_HTTP_API_KEY'),
            'api_key_header' => env('ALDAWY_SMS_HTTP_API_KEY_HEADER', 'Authorization'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment gateways enabled on checkout (cod always; online optional)
    |--------------------------------------------------------------------------
    */
    'payments' => [
        'online_enabled' => (bool) env('ALDAWY_ONLINE_PAYMENT_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Require verified email for /my account panel
    |--------------------------------------------------------------------------
    */
    'require_email_verification' => (bool) env('ALDAWY_REQUIRE_EMAIL_VERIFICATION', false),

    /*
    |--------------------------------------------------------------------------
    | Phone OTP login (storefront)
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'enabled' => (bool) env('ALDAWY_PHONE_OTP_ENABLED', true),
        'driver' => env('ALDAWY_OTP_DRIVER', 'log'),
        'ttl_minutes' => (int) env('ALDAWY_OTP_TTL_MINUTES', 10),
    ],

];
