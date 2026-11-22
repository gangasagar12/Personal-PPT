<?php
namespace App\Controllers;

use App\Models\FeaturedProject;
use App\Models\AcademicProject;
use App\Models\BlogInsight;
use App\Models\Experience;
use App\Models\Promotion;
use App\Core\View;

class HomeController {
    public function index(): void {
        try {
            $featured_projects = FeaturedProject::allWithSkills();
        } catch (\Throwable $e) {
            $featured_projects = [];
        }

        try {
            $academic_projects = AcademicProject::allWithSkills();
        } catch (\Throwable $e) {
            $academic_projects = [];
        }

        try {
            $blog_insights = BlogInsight::published();
        } catch (\Throwable $e) {
            $blog_insights = [];
        }

        try {
            $experiences = Experience::allWithDetails();
        } catch (\Throwable $e) {
            $experiences = [];
        }

        try {
            $active_promotion = Promotion::getActive();
        } catch (\Throwable $e) {
            $active_promotion = null;
        }

        try {
            View::render('home', [
                'featured_projects' => $featured_projects ?? [],
                'academic_projects' => $academic_projects ?? [],
                'blog_insights' => $blog_insights ?? [],
                'experiences' => $experiences ?? [],
                'active_promotion' => $active_promotion ?? null
            ]);
        } catch (\Throwable $e) {
            http_response_code(200);
            echo "<!DOCTYPE html><html><head><title>System Diagnostic</title><style>body{font-family:sans-serif;padding:2rem;background:#0b1329;color:#fff;}pre{background:#1e293b;padding:1rem;border-radius:8px;overflow:auto;color:#38bdf8;}</style></head><body>";
            echo "<h2>Homepage Error Detected:</h2>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
            echo "<h3>Stack Trace:</h3><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "</body></html>";
            exit;
        }
    }
}

