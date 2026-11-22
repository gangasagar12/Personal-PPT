<?php
/**
 * Ganga Sagar Joshi Portfolio — Public Front Controller
 * 
 * Handles incoming web requests, loads the environment and autoloader,
 * defines application routes, and dispatches to controllers.
 */

declare(strict_types=1);

// Debug mode toggle via query parameter (?debug=1)
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// Static asset fallback handler (serves css, js, images on CLI server & Apache rewrites)
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$rootPath = dirname(__DIR__);

if ($uriPath !== '/' && $uriPath !== '/index.php' && preg_match('/\.(css|js|png|jpe?g|webp|svg|ico|pdf|txt|xml)$/i', (string)$uriPath)) {
    $candidatePaths = [
        __DIR__ . $uriPath,
        $rootPath . $uriPath,
        $rootPath . '/public' . $uriPath,
    ];
    foreach ($candidatePaths as $candidate) {
        if (is_file($candidate)) {
            $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
            $mimes = [
                'css'  => 'text/css; charset=utf-8',
                'js'   => 'application/javascript; charset=utf-8',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                'webp' => 'image/webp',
                'svg'  => 'image/svg+xml',
                'ico'  => 'image/x-icon',
                'pdf'  => 'application/pdf',
                'txt'  => 'text/plain; charset=utf-8',
                'xml'  => 'application/xml; charset=utf-8',
            ];
            if (isset($mimes[$ext])) {
                header('Content-Type: ' . $mimes[$ext]);
                header('Cache-Control: public, max-age=86400');
                readfile($candidate);
                exit;
            }
        }
    }
}

// Locate project root containing config/, app/, and routes/
$possibleRoots = [
    dirname(__DIR__),
    __DIR__,
    dirname(__DIR__) . '/public_html',
    __DIR__ . '/../public_html',
    dirname($_SERVER['DOCUMENT_ROOT'] ?? ''),
    ($_SERVER['DOCUMENT_ROOT'] ?? ''),
    dirname($_SERVER['DOCUMENT_ROOT'] ?? '') . '/public_html',
];

$projectRoot = dirname(__DIR__);
foreach ($possibleRoots as $cand) {
    if ($cand && is_dir($cand . '/app') && file_exists($cand . '/routes/web.php')) {
        $projectRoot = realpath($cand) ?: $cand;
        break;
    }
}
$GLOBALS['projectRoot'] = $projectRoot;

// 1. Load Environment Configuration
if (file_exists($projectRoot . '/config/env.php')) {
    require_once $projectRoot . '/config/env.php';
} elseif (file_exists(__DIR__ . '/config/env.php')) {
    require_once __DIR__ . '/config/env.php';
} else {
    // Built-in fallback env() loader if config/ folder was not uploaded or placed elsewhere
    if (!function_exists('env')) {
        function env($key, $default = null) {
            static $env = null;
            if ($env === null) {
                $env = [];
                $root = $GLOBALS['projectRoot'] ?? dirname(__DIR__);
                $candidates = [
                    $root . '/.env',
                    dirname(__DIR__) . '/.env',
                    __DIR__ . '/.env',
                    ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/.env',
                    dirname($_SERVER['DOCUMENT_ROOT'] ?? '') . '/.env',
                ];
                foreach ($candidates as $envFile) {
                    if (file_exists($envFile)) {
                        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if ($line === '' || str_starts_with($line, '#')) continue;
                            if (str_contains($line, '=')) {
                                list($k, $v) = explode('=', $line, 2);
                                $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
                            }
                        }
                        break;
                    }
                }
            }
            return $env[$key] ?? $default;
        }
    }
}

// 2. Register Native PSR-4 Autoloader
if (file_exists($projectRoot . '/app/Core/Autoloader.php')) {
    require_once $projectRoot . '/app/Core/Autoloader.php';
    \App\Core\Autoloader::register();
}

// 3. Load Web Routes & Dispatch Request
if (file_exists($projectRoot . '/routes/web.php')) {
    require_once $projectRoot . '/routes/web.php';
    try {
        \App\Core\Router::dispatch();
    } catch (\Throwable $e) {
        http_response_code(200);
        echo "<!DOCTYPE html><html><head><title>System Diagnostic</title><style>body{font-family:sans-serif;padding:2rem;background:#0b1329;color:#fff;}pre{background:#1e293b;padding:1rem;border-radius:8px;overflow:auto;color:#38bdf8;}</style></head><body>";
        echo "<h2>System Diagnostic Notice:</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
        echo "<h3>Stack Trace:</h3><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</body></html>";
    }
} else {
    die("System Error: routes/web.php could not be found in {$projectRoot}. Please ensure all project folders (app, routes, resources, config) are uploaded to your hosting server.");
}

