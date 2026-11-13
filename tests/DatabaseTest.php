<?php
namespace Tests;

use App\Core\Database;
use App\Models\FeaturedProject;
use App\Models\AcademicProject;
use App\Models\BlogInsight;

class DatabaseTest {
    public function testConnection(): bool {
        $pdo = Database::getConnection();
        return $pdo instanceof \PDO;
    }

    public function testFeaturedProjectsCount(): bool {
        $projects = FeaturedProject::allWithSkills();
        return count($projects) === 6;
    }

    public function testAcademicProjectsCount(): bool {
        $academics = AcademicProject::allWithSkills();
        return count($academics) === 4;
    }

    public function testBlogInsightsCount(): bool {
        $insights = BlogInsight::published();
        return count($insights) === 3;
    }
}

