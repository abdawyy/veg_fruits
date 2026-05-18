<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4 (optional)
    |--------------------------------------------------------------------------
    |
    | Set ANALYTICS_GA4_MEASUREMENT_ID in .env (e.g. G-XXXXXXXXXX) to load
    | gtag.js on the storefront and guest auth pages only.
    |
    */

    'ga4_measurement_id' => env('ANALYTICS_GA4_MEASUREMENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Meta (Facebook) Pixel (optional)
    |--------------------------------------------------------------------------
    |
    | Numeric pixel ID only. Loads the standard fbq snippet when set.
    |
    */

    'meta_pixel_id' => env('ANALYTICS_META_PIXEL_ID'),

];
