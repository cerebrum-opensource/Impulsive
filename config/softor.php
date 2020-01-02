<?php 
return [ 
    'project_id' => env('SOFTOR_PROJECT_ID',''),
    'customer_id' => env('SOFTOR_CUSTOMER_ID',''),
    'key' => env('SOFTOR_KEY',''),
    'currency' => env('SOFTOR_CURRENCY','EUR'),
    'settings' => array(
        'log.FileName' => storage_path() . '/logs/softor.log',
        'log.LogLevel' => 'ERROR'
    ),
];