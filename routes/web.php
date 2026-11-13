<?php
/**
 * Web Application Routes
 * Defines all public and internal URL routes mapped to Controllers.
 */

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\ContactController;
use App\Controllers\BlogController;

// 1. Home Portfolio
Router::get('/', [HomeController::class, 'index']);

// 2. Entrepreneurship Profile (Strataflow Technologies)
Router::get('/profile', [ProfileController::class, 'index']);
Router::get('/profile.html', [ProfileController::class, 'index']);

// 3. Technical Insights & Blog (List & Detail)
Router::get('/insights', [BlogController::class, 'index']);
Router::get('/insights.html', [BlogController::class, 'index']);
Router::get('/insights/{slug}', [BlogController::class, 'show']);
Router::get('/insight', [BlogController::class, 'show']);
Router::get('/insight.html', [BlogController::class, 'show']);
Router::get('/insight-detail', [BlogController::class, 'show']);
Router::get('/insight-detail.html', [BlogController::class, 'show']);

// 4. Contact Form Submissions (API & Legacy Fallback)
Router::post('/contact', [ContactController::class, 'submit']);
Router::post('/backend/contact.php', [ContactController::class, 'submit']);

// 4. Admin Dashboard
Router::get('/admin', function() {
    require dirname(__DIR__) . '/admin/index.php';
    exit;
});

