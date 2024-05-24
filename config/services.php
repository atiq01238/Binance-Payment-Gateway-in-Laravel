<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'binance' => [
        'api_key' => env('MvGrJzFDSYLnV21qtorU1TP7NgknzIw6eC1tfFJ0uQSN7z2iqRXUElE9e7WPYq2S'),
        'secret_key' => env('nB4zCcUe7OzFSPdb2Nq5z636RbHqaOgbftaQdLnxyxsLda9lAmrmbFdo8MJ7h3XT'),
        'base_uri' => env('BINANCE_BASE_URI', 'https://testnet.binance.vision/api/v3/'), // default to live Binance API
    ],
];
