<?php
/**
 * Ganga Sagar Joshi — Dynamic Project Details Controller
 * 
 * Dynamically queries the project by slug or id from the database
 * and renders the case study view template.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

use App\Models\FeaturedProject;

// Extract slug from query string or request URI
$slug = $_GET['slug'] ?? '';

if (empty($slug) && !empty($_SERVER['REQUEST_URI'])) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if (preg_match('#^(?:projects?)/([^/]+)#', $path, $matches)) {
        $slug = $matches[1];
    }
}

$project = null;

if (!empty($slug)) {
    $cleanSlug = preg_replace('/\.html$/', '', (string)$slug);
    $project = FeaturedProject::findBySlug($cleanSlug);
}

if (!$project && !empty($_GET['id'])) {
    $project = FeaturedProject::findWithDetails((int)$_GET['id']);
}

// Fallback to first project if no parameter provided
if (!$project) {
    $all = FeaturedProject::allWithSkills();
    $project = !empty($all) ? $all[0] : null;
}

if (!$project) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>Project Not Found</title><link rel="stylesheet" href="css/style.css"></head><body style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;text-align:center;">';
    echo '<div><h1>404 — Project Not Found</h1><p>The requested project does not exist.</p><p><a href="index.php" style="color:#2563eb;text-decoration:none;font-weight:600;">← Return to Home</a></p></div>';
    echo '</body></html>';
    exit;
}

$is_subdir = false;
require __DIR__ . '/resources/views/project_detail.php';
