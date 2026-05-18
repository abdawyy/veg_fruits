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

];
