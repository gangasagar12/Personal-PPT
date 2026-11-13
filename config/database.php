<?php
require_once __DIR__ . '/env.php';

return [
    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => dirname(__DIR__) . '/' . env('DB_DATABASE', 'database/portfolio.sqlite'),
        ],
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_NAME', 'portfolio_db'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]
    ]
];

