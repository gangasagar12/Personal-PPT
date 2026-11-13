<?php
namespace App\Controllers;

use App\Models\FeaturedProject;
use App\Models\AcademicProject;
use App\Models\BlogInsight;
use App\Models\Experience;
use App\Core\View;

class HomeController {
    public function index(): void {
        $featured_projects = FeaturedProject::allWithSkills();
        $academic_projects = AcademicProject::allWithSkills();
        $blog_insights = BlogInsight::published();
        $experiences = Experience::allWithDetails();

        View::render('home', [
            'featured_projects' => $featured_projects,
            'academic_projects' => $academic_projects,
            'blog_insights' => $blog_insights,
            'experiences' => $experiences
        ]);
    }
}

