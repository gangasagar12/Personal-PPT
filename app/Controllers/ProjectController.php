<?php
namespace App\Controllers;

use App\Models\FeaturedProject;
use App\Core\View;

class ProjectController {
    /**
     * Display single project detail view
     */
    public function show(?string $slug = null): void {
        if (!$slug) {
            $slug = $_GET['slug'] ?? '';
        }

        $project = null;
        $cleanSlug = !empty($slug) ? strtolower(trim(preg_replace('/\.html$/', '', (string)$slug), '/')) : '';

        if (!empty($cleanSlug)) {
            $project = FeaturedProject::findBySlug($cleanSlug);
        }

        if (!$project && !empty($_GET['id'])) {
            $project = FeaturedProject::findWithDetails(intval($_GET['id']));
        }

        // Only default to first project if NO slug was provided at all (e.g. root /project-detail)
        if (!$project && empty($cleanSlug) && empty($_GET['id'])) {
            $all = FeaturedProject::allWithSkills();
            $project = !empty($all) ? $all[0] : null;
        }

        if (!$project) {
            http_response_code(404);
            echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>404 — Project Not Found</title>";
            echo "<link rel='stylesheet' href='/css/style.css'>";
            echo "</head><body style='display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;text-align:center;background:#0f172a;color:#fff;'>";
            echo "<div><h1 style='font-size:3rem;margin-bottom:1rem;'>404 — Project Not Found</h1>";
            echo "<p style='color:#94a3b8;margin-bottom:2rem;'>The requested project case study was not found or has been moved.</p>";
            echo "<a href='/#projects' style='display:inline-block;padding:0.75rem 1.5rem;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;'>&larr; Back to Works</a>";
            echo "</div></body></html>";
            return;
        }

        // Return JSON if requested via AJAX
        if (isset($_GET['format']) && $_GET['format'] === 'json') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($project, JSON_UNESCAPED_UNICODE);
            return;
        }

        $allProjects = FeaturedProject::allWithSkills();

        View::render('project_detail', [
            'project' => $project,
            'all_projects' => $allProjects,
            'page_title' => htmlspecialchars($project['title']) . ' — Project Case Study'
        ]);
    }
}
