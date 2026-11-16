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

        View::render('home', [
            'featured_projects' => $featured_projects,
            'academic_projects' => $academic_projects,
            'blog_insights' => $blog_insights,
            'experiences' => $experiences,
            'active_promotion' => $active_promotion
        ]);
    }
}

