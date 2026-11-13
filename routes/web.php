<?php

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\ContactController;
use App\Controllers\BlogController;
use App\Controllers\ProjectController;

// 1. Home Portfolio
Router::get('/', [HomeController::class, 'index']);
Router::get('/index.php', [HomeController::class, 'index']);

// 2. Entrepreneurship Profile (Strataflow Technologies)
Router::get('/profile', [ProfileController::class, 'index']);
Router::get('/profile.html', [ProfileController::class, 'index']);

// 3. Technical Insights & Blog (List & Detail)
Router::get('/insights', [BlogController::class, 'index']);
Router::get('/insights.html', [BlogController::class, 'index']);
Router::get('/insights/{slug}', [BlogController::class, 'show']);
Router::get('/insight', [BlogController::class, 'show']);
Router::get('/insight-detail', [BlogController::class, 'show']);
Router::get('/insight-detail.html', [BlogController::class, 'show']);

// 4. Project Case Studies & Details (Clean URL /works/{slug})
Router::get('/works/{slug}', [ProjectController::class, 'show']);
Router::get('/works/{slug}.html', [ProjectController::class, 'show']);
Router::get('/works', [HomeController::class, 'index']);
Router::get('/projects/{slug}', [ProjectController::class, 'show']);
Router::get('/projects/{slug}.html', [ProjectController::class, 'show']);
Router::get('/project/{slug}', [ProjectController::class, 'show']);
Router::get('/project-detail', [ProjectController::class, 'show']);
Router::get('/project-detail.html', [ProjectController::class, 'show']);
Router::get('/project-detail.php', [ProjectController::class, 'show']);

// 4. Contact Form Submissions (API & Legacy Fallback)
Router::post('/contact', [ContactController::class, 'submit']);
Router::post('/backend/contact.php', [ContactController::class, 'submit']);

// 4. Admin Dashboard (GET and POST for login, CRUD, actions)
$adminHandler = function() {
    require dirname(__DIR__) . '/admin/index.php';
    exit;
};
Router::get('/admin', $adminHandler);
Router::post('/admin', $adminHandler);
Router::get('/admin/index.php', $adminHandler);
Router::post('/admin/index.php', $adminHandler);

