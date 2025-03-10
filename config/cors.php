<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
        'paths' => ['api/*', 'sanctum/csrf-cookie'], // Allow API routes
        'allowed_methods' => ['*'], // Allow all methods (GET, POST, PUT, DELETE)
        'allowed_origins' => ['http://localhost:3000'], // Your React frontend URL
        'allowed_origins_patterns' => [],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => true, 
    

];
