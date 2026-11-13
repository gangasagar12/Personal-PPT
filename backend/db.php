<?php
/**
 * Database Connection using App\Core\Database Singleton
 * Supports SQLite and MySQL configured via .env / config/database.php
 */

require_once dirname(__DIR__) . '/config/env.php';
require_once dirname(__DIR__) . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

$pdo = \App\Core\Database::getConnection();
