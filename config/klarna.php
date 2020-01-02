<?php 
return [ 
    'merchantId' => env('KLARNA_MERCHANT_ID',''),
    'sharedSecret' => env('KLARNA_SHARED_SECRET',''),
    'currency' => env('KLARNA_CURRENCY','eur'),
    'purchase_country' => env('KLARNA_COUNTRY','de'),
    'locale' => env('KLARNA_LOCALE','en-de'),
    'settings' => array(
        'log.FileName' => storage_path() . '/logs/klarna.log',
        'log.LogLevel' => 'ERROR'
    ),
];