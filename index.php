<?php
/**
 * Ganga Sagar Joshi Portfolio — Front Controller Gateway
 * 
 * Forwards requests directly to the public/ front controller
 * when running from project root (e.g., php -S localhost:8000).
 */

declare(strict_types=1);

require_once __DIR__ . '/public/index.php';
