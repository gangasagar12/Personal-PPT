<?php
/**
 * Ganga Sagar Joshi Portfolio — Front Controller Gateway
 * 
 * Forwards requests directly to the public/ front controller
 * or dispatches directly if running flat without subfolder.
 */

declare(strict_types=1);

if (file_exists(__DIR__ . '/public/index.php')) {
    require_once __DIR__ . '/public/index.php';
} elseif (file_exists(__DIR__ . '/routes/web.php')) {
    if (file_exists(__DIR__ . '/config/env.php')) {
        require_once __DIR__ . '/config/env.php';
    }
    if (file_exists(__DIR__ . '/app/Core/Autoloader.php')) {
        require_once __DIR__ . '/app/Core/Autoloader.php';
        \App\Core\Autoloader::register();
    }
    require_once __DIR__ . '/routes/web.php';
    \App\Core\Router::dispatch();
} else {
    die("System Error: Unable to locate public/index.php or routes/web.php. Please ensure all project files are uploaded.");
}
