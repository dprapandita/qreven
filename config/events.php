<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Event subdomain base
    |--------------------------------------------------------------------------
    |
    | Event pages are served from "{subdomain}.{base_domain}". The base domain
    | must match the host used for the main organizer application.
    |
    */

    'base_domain' => env('APP_BASE_DOMAIN') ?: (parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Reserved subdomains
    |--------------------------------------------------------------------------
    |
    | These labels are reserved for the platform and cannot be used by events.
    |
    */

    'reserved_subdomains' => [
        'www',
        'app',
        'admin',
        'api',
        'auth',
        'mail',
        'smtp',
        'ftp',
        'cdn',
        'static',
        'assets',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pending order lifetime
    |--------------------------------------------------------------------------
    |
    | Reserved ticket inventory is released after this many minutes when the
    | order has not been paid.
    |
    */

    'order_expiry_minutes' => (int) env('ORDER_EXPIRY_MINUTES', 30),

];
