<?php
/**
 * Ganga Sagar Joshi Portfolio — Public Front Controller
 * 
 * Handles incoming web requests, loads the environment and autoloader,
 * defines application routes, and dispatches to controllers.
 */

declare(strict_types=1);

// Built-in CLI server static file handler (supports php -S localhost:8000 -t public)
if (php_sapi_name() === 'cli-server') {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $rootPath = dirname(__DIR__);
    
    // Check if file exists inside public/ or in project root (e.g. css, js, images)
    if ($uriPath !== '/' && $uriPath !== '/index.php') {
        $candidatePaths = [
            __DIR__ . $uriPath,
            $rootPath . $uriPath,
        ];
        foreach ($candidatePaths as $candidate) {
            if (is_file($candidate)) {
                $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
                $mimes = [
                    'css'  => 'text/css',
                    'js'   => 'application/javascript',
                    'jpg'  => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png'  => 'image/png',
                    'webp' => 'image/webp',
                    'svg'  => 'image/svg+xml',
                    'ico'  => 'image/x-icon',
                    'txt'  => 'text/plain',
                    'xml'  => 'application/xml',
                ];
                if (isset($mimes[$ext])) {
                    header('Content-Type: ' . $mimes[$ext]);
                    readfile($candidate);
                    exit;
                }
                return false;
            }
        }
    }
}

// 1. Load Environment Configuration
require_once dirname(__DIR__) . '/config/env.php';

// 2. Register Native PSR-4 Autoloader
require_once dirname(__DIR__) . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

// 3. Load Web Routes & Dispatch Request
require_once dirname(__DIR__) . '/routes/web.php';
\App\Core\Router::dispatch();

