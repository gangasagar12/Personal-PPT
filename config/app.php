<?php
require_once __DIR__ . '/env.php';

return [
    'name' => env('APP_NAME', 'Ganga Sagar Joshi Portfolio'),
    'env' => env('APP_ENV', 'development'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost:8000'),

    'admin' => [
        'user' => env('ADMIN_USERNAME', 'admin'),
        'pass' => env('ADMIN_PASSWORD', 'admin123'),
    ]
];

