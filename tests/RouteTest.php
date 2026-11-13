<?php
namespace Tests;

class RouteTest {
    public function testRoutesFileExists(): bool {
        return file_exists(dirname(__DIR__) . '/routes/web.php');
    }

    public function testViewTemplatesExist(): bool {
        $home = file_exists(dirname(__DIR__) . '/resources/views/home.php');
        $profile = file_exists(dirname(__DIR__) . '/resources/views/profile.php');
        $insights = file_exists(dirname(__DIR__) . '/resources/views/insights.php');
        $insightDetail = file_exists(dirname(__DIR__) . '/resources/views/insight_detail.php');
        $projectDetail = file_exists(dirname(__DIR__) . '/resources/views/project_detail.php');
        return $home && $profile && $insights && $insightDetail && $projectDetail;
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
}

