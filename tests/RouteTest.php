<?php
namespace Tests;

class RouteTest {
    public function testRoutesFileExists(): bool {
        return file_exists(dirname(__DIR__) . '/routes/web.php');
    }

    public function testViewTemplatesExist(): bool {
        $home = file_exists(dirname(__DIR__) . '/resources/views/home.php');
        $profile = file_exists(dirname(__DIR__) . '/resources/views/profile.php');
        $experience = file_exists(dirname(__DIR__) . '/resources/views/experience.php');
        $insights = file_exists(dirname(__DIR__) . '/resources/views/insights.php');
        $insightDetail = file_exists(dirname(__DIR__) . '/resources/views/insight_detail.php');
        $projectDetail = file_exists(dirname(__DIR__) . '/resources/views/project_detail.php');
        $projects = file_exists(dirname(__DIR__) . '/resources/views/projects.php');
        return $home && $profile && $experience && $insights && $insightDetail && $projectDetail && $projects;
    }

    public function testExperienceController(): bool {
        $controller = new \App\Controllers\ExperienceController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        return strpos($output, 'Career Journey') !== false && strpos($output, 'Honors Gallery') !== false;
    }

    public function testDynamicProjectController(): bool {
        $controller = new \App\Controllers\ProjectController();
        ob_start();
        $controller->show('oralix-dental-practice-os');
        $output = ob_get_clean();
        return strpos($output, 'Oralix') !== false && strpos($output, 'Project Introduction') !== false;
    }

    public function testAdminAuthCredentials(): bool {
        $app_config = file_exists(dirname(__DIR__) . '/config/app.php') ? require dirname(__DIR__) . '/config/app.php' : [];
        $user = $app_config['admin']['user'] ?? env('ADMIN_USERNAME', 'admin');
        $pass = $app_config['admin']['pass'] ?? env('ADMIN_PASSWORD', 'admin123');
        return $user === 'admin' && $pass === 'admin123';
    }

    public function testAdminDashboardRendering(): bool {
        $_SESSION['admin_logged_in'] = true;
        $_SERVER['REQUEST_URI'] = '/admin';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        ob_start();
        include dirname(__DIR__) . '/admin/index.php';
        $output = ob_get_clean();
        
        unset($_SESSION['admin_logged_in']);
        return strpos($output, 'Admin Workspace') !== false && strpos($output, 'Academic Projects') !== false;
    }

    public function testHomeAcademicProjectsRendering(): bool {
        $controller = new \App\Controllers\HomeController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();
        
        return strpos($output, 'academic-projects-grid') !== false
            && strpos($output, 'academicDetailModal') !== false
            && strpos($output, 'data-academic-modal-trigger') !== false
            && strpos($output, 'Barcode -based Student Attendance System') !== false;
    }

    public function testHomeWhyWorkSectionRendering(): bool {
        $controller = new \App\Controllers\HomeController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        return strpos($output, 'why-work-section') !== false
            && strpos($output, 'Why Work') !== false
            && strpos($output, 'why-cards-grid') !== false
            && strpos($output, 'why-simple-card') !== false
            && strpos($output, 'Practical Development') !== false
            && strpos($output, 'Problem-Solving Mindset') !== false
            && strpos($output, 'Product-Oriented Thinking') !== false
            && strpos($output, 'Continuous Learning') !== false;
    }

    public function testProjectsIndexController(): bool {
        $controller = new \App\Controllers\ProjectController();
        ob_start();
        $controller->index();
        $output = ob_get_clean();

        return strpos($output, 'All Projects &') !== false
            && strpos($output, 'Featured Projects') !== false
            && strpos($output, 'Key Academic Projects') !== false
            && strpos($output, 'Some Other Projects') !== false
            && strpos($output, 'projects-archive-grid') !== false
            && strpos($output, 'academicDetailModal') !== false;
    }
}

