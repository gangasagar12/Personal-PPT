<?php
namespace App\Controllers;

use App\Models\BlogInsight;
use App\Core\View;

class BlogController {
    /**
     * Display all blog insights (Grid view: Hero + Cards with Image & Title only)
     */
    public function index(): void {
        $posts = BlogInsight::published();

        View::render('insights', [
            'posts' => $posts,
            'page_title' => 'Insights — Ganga Sagar Joshi'
        ]);
    }

    /**
     * Display single blog insight detail with Recent Articles sidebar
     */
    public function show(?string $slug = null): void {
        if (!$slug) {
            $slug = $_GET['slug'] ?? '';
        }

        $post = null;
        if (!empty($slug)) {
            $post = BlogInsight::findBySlug($slug);
        }

        // Fallback by ID if requested
        if (!$post && !empty($_GET['id'])) {
            $post = BlogInsight::findById(intval($_GET['id']));
        }

        // If still not found, load latest post as graceful fallback or 404
        if (!$post) {
            $all = BlogInsight::published();
            if (!empty($all)) {
                $post = $all[0];
            } else {
                http_response_code(404);
                echo "<h1>Article Not Found</h1>";
                return;
            }
        }

        $recent = BlogInsight::recent(5, $post['id'] ?? null);
        $all_posts = BlogInsight::published();

        View::render('insight_detail', [
            'post' => $post,
            'recent' => $recent,
            'all_posts' => $all_posts,
            'page_title' => $post['title'] . ' — Ganga Sagar Joshi'
        ]);
    }
}

