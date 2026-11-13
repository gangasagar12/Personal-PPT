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
        if (!empty($slug)) {
            $project = FeaturedProject::findBySlug($slug);
        }

        if (!$project && !empty($_GET['id'])) {
            $project = FeaturedProject::findWithDetails(intval($_GET['id']));
        }

        if (!$project) {
            $all = FeaturedProject::allWithSkills();
            $project = !empty($all) ? $all[0] : null;
        }

        if (!$project) {
            http_response_code(404);
            echo "<h1>Project Not Found</h1>";
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

