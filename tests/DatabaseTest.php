<?php
namespace Tests;

use App\Core\Database;
use App\Models\FeaturedProject;
use App\Models\AcademicProject;
use App\Models\BlogInsight;
use App\Models\CompanyProduct;

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

    public function testCompanyProductsCount(): bool {
        $products = CompanyProduct::allWithFeaturesAndPills();
        return count($products) >= 3;
    }

    public function testBlogInsightFindBySlug(): bool {
        $post = BlogInsight::findBySlug('architecting-scalable-relational-database-schemas');
        return is_array($post) && !empty($post['title']) && !empty($post['image']);
    }

    public function testBlogInsightRecent(): bool {
        $recent = BlogInsight::recent(2);
        return count($recent) === 2;
    }
}

