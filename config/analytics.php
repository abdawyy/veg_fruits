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

    /*
    |--------------------------------------------------------------------------
    | Reporting timezone
    |--------------------------------------------------------------------------
    |
    | Timestamps are stored in the app timezone (UTC). Hour-of-day and weekday
    | charts are converted to this timezone so "8 PM" means 8 PM in Egypt.
    |
    */

    'timezone' => env('ANALYTICS_TIMEZONE', 'Africa/Cairo'),

    /*
    |--------------------------------------------------------------------------
    | Visitor location (GeoIP)
    |--------------------------------------------------------------------------
    |
    | Cloudflare location headers (CF-IPCountry, cf-ipcity, cf-region) are used
    | first when present. Otherwise one HTTP lookup per new visitor session is
    | made after the response is sent, cached per IP. Raw IPs are never stored.
    | {ip} in the URL is replaced with the visitor IP; ipwho.is, ipapi.co and
    | ip-api.com response formats are understood.
    |
    */

    'geoip' => [
        'enabled' => (bool) env('ANALYTICS_GEOIP_ENABLED', true),
        'url' => env('ANALYTICS_GEOIP_URL', 'https://ipwho.is/{ip}'),
        'timeout' => (float) env('ANALYTICS_GEOIP_TIMEOUT', 2),
        'cache_days' => (int) env('ANALYTICS_GEOIP_CACHE_DAYS', 7),
    ],

];
