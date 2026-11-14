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
        $inTable = preg_match('/<section id="tab-blog".*?<div class="table-wrap">.*?to prevent accidental mass assignment.*?<\/table>/s', $output);
        return strpos($output, 'Admin Workspace') !== false 
            && strpos($output, 'Academic &amp; Other Projects') !== false
            && strpos($output, 'tab-academic-other') !== false
            && strpos($output, 'onclick="editBlog(') !== false
            && !$inTable;
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

    public function testUnifiedNavbarRendering(): bool {
        // Test Home Controller
        $home = new \App\Controllers\HomeController();
        ob_start();
        $home->index();
        $homeOut = ob_get_clean();

        // Test Projects Controller
        $proj = new \App\Controllers\ProjectController();
        ob_start();
        $proj->index();
        $projOut = ob_get_clean();

        // Test Experience Controller
        $exp = new \App\Controllers\ExperienceController();
        ob_start();
        $exp->index();
        $expOut = ob_get_clean();

        // Check for presence of unified header and mobile drawer across pages
        return strpos($homeOut, 'id="desktopNav"') !== false
            && strpos($homeOut, 'id="mobileDrawer"') !== false
            && strpos($homeOut, 'id="drawerBackdrop"') !== false
            && strpos($projOut, 'id="desktopNav"') !== false
            && strpos($projOut, 'id="mobileDrawer"') !== false
            && strpos($projOut, 'class="nav-link active">Projects<') !== false
            && strpos($expOut, 'id="desktopNav"') !== false
            && strpos($expOut, 'id="mobileDrawer"') !== false
            && strpos($expOut, 'class="nav-link active">Experience<') !== false;
    }

    public function testUnifiedFooterRendering(): bool {
        // Test Home
        $home = new \App\Controllers\HomeController();
        ob_start();
        $home->index();
        $homeOut = ob_get_clean();

        // Test Projects
        $proj = new \App\Controllers\ProjectController();
        ob_start();
        $proj->index();
        $projOut = ob_get_clean();

        // Test Profile
        $prof = new \App\Controllers\ProfileController();
        ob_start();
        $prof->index();
        $profOut = ob_get_clean();

        // Check unified footer across all pages
        $check = function(string $html): bool {
            return strpos($html, 'class="footer"') !== false
                && strpos($html, 'class="footer-top"') !== false
                && strpos($html, 'class="footer-nav"') !== false
                && strpos($html, 'class="footer-bottom"') !== false
                && strpos($html, 'class="footer-copy"') !== false
                && strpos($html, 'class="footer-social"') !== false
                && strpos($html, 'class="back-to-top"') !== false;
        };

        return $check($homeOut) && $check($projOut) && $check($profOut);
    }

    public function testContactSubmissionAndAdminDisplay(): bool {
        // 1. Submit contact form
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'Aarav Sharma',
            'email' => 'aarav@example.com',
            'subject' => 'Mobile App Development',
            'phone' => '+977 9841000000',
            'message' => 'Interested in building a clinical practice application.'
        ];

        $controller = new \App\Controllers\ContactController();
        ob_start();
        $controller->submit();
        $json = ob_get_clean();

        $data = json_decode($json, true);
        if (!$data || empty($data['success'])) {
            return false;
        }

        // 2. Verify in database
        $pdo = \App\Core\Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM contact_inquiries WHERE email = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute(['aarav@example.com']);
        $inquiry = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$inquiry || $inquiry['name'] !== 'Aarav Sharma') {
            return false;
        }

        // 3. Verify in Admin Panel render
        $_SESSION['admin_logged_in'] = true;
        $_SERVER['REQUEST_URI'] = '/admin';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_POST = [];
        ob_start();
        include dirname(__DIR__) . '/admin/index.php';
        $adminHtml = ob_get_clean();
        unset($_SESSION['admin_logged_in']);

        // Cleanup test record
        $pdo->prepare("DELETE FROM contact_inquiries WHERE email = ?")->execute(['aarav@example.com']);

        return strpos($adminHtml, 'Aarav Sharma') !== false
            && strpos($adminHtml, 'Mobile App Development') !== false
            && strpos($adminHtml, 'aarav@example.com') !== false;
    }
}

