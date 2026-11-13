<?php
namespace App\Core;

class View {
    public static function render(string $template, array $data = []): void {
        extract($data);
        $file = dirname(__DIR__, 2) . '/resources/views/' . ltrim($template, '/') . '.php';

        if (!file_exists($file)) {
            die("View template not found: " . htmlspecialchars($template));
        }

        require $file;
    }

    public static function escape(?string $str): string {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}

