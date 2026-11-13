<?php
/**
 * Static Site Exporter
 * Renders index.php and profile.php, saving to index.html and profile.html
 * in both Root (for GitHub Pages) and public/ (for web server DocumentRoot).
 */

// 1. Export Home Page
ob_start();
require __DIR__ . '/../index.php';
$html = ob_get_clean();

file_put_contents(__DIR__ . '/../index.html', $html);
if (is_dir(__DIR__ . '/../public')) {
    file_put_contents(__DIR__ . '/../public/index.html', $html);
}
echo "Successfully exported dynamic database to static index.html (" . strlen($html) . " bytes)\n";

// 2. Export Entrepreneurship Profile
$profileView = __DIR__ . '/../resources/views/profile.php';
if (!file_exists($profileView)) {
    $profileView = __DIR__ . '/../app/Views/profile.php';
}

if (file_exists($profileView)) {
    ob_start();
    require $profileView;
    $profileHtml = ob_get_clean();

    file_put_contents(__DIR__ . '/../profile.html', $profileHtml);
    if (is_dir(__DIR__ . '/../public')) {
        file_put_contents(__DIR__ . '/../public/profile.html', $profileHtml);
    }
    echo "Successfully exported Entrepreneurship Profile to static profile.html (" . strlen($profileHtml) . " bytes)\n";
}
