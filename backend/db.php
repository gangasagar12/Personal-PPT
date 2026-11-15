<?php


require_once dirname(__DIR__) . '/config/env.php';
require_once dirname(__DIR__) . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

$pdo = \App\Core\Database::getConnection();
$GLOBALS['pdo'] = $pdo;
