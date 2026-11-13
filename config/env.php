<?php
/**
 * Lightweight Environment File (.env) Loader
 * Compatible with PHP 8.0+ without third-party dependencies.
 */

if (!function_exists('env')) {
    function env($key, $default = null) {
        static $env = null;
        if ($env === null) {
            $env = [];
            $envFile = dirname(__DIR__) . '/.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#')) {
                        continue;
                    }
                    if (str_contains($line, '=')) {
                        list($name, $value) = explode('=', $line, 2);
                        $name = trim($name);
                        $value = trim($value, " \t\n\r\0\x0B\"'");
                        $env[$name] = $value;
                    }
                }
            }
        }
        return $env[$key] ?? $default;
    }
}

