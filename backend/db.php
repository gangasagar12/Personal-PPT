<?php


$root = dirname(__DIR__);
if (file_exists($root . '/config/env.php')) {
    require_once $root . '/config/env.php';
} elseif (file_exists(__DIR__ . '/../config/env.php')) {
    require_once __DIR__ . '/../config/env.php';
}

if (file_exists($root . '/app/Core/Autoloader.php')) {
    require_once $root . '/app/Core/Autoloader.php';
    \App\Core\Autoloader::register();
} elseif (file_exists(__DIR__ . '/../app/Core/Autoloader.php')) {
    require_once __DIR__ . '/../app/Core/Autoloader.php';
    \App\Core\Autoloader::register();
}

$pdo = \App\Core\Database::getConnection();
$GLOBALS['pdo'] = $pdo;
