<?php
/**
 * Static Site Exporter
 * Renders dynamic database views into static HTML files for GitHub Pages and web server DocumentRoot.
 */

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../app/Core/Autoloader.php';
\App\Core\Autoloader::register();

// 1. Export Home Page
ob_start();
require __DIR__ . '/../index.php';
$html = ob_get_clean();

file_put_contents(__DIR__ . '/../index.html', $html);
if (is_dir(__DIR__ . '/../public')) {
    file_put_contents(__DIR__ . '/../public/index.html', $html);
}
echo "Successfully exported dynamic database to static index.html (" . strlen($html) . " bytes)\n";

// 2. Export Entrepreneurship Profile
$profileView = __DIR__ . '/../resources/views/profile.php';
if (file_exists($profileView)) {
    $company_products = \App\Models\CompanyProduct::allWithFeaturesAndPills();

    ob_start();
    require $profileView;
    $profileHtml = ob_get_clean();

    file_put_contents(__DIR__ . '/../profile.html', $profileHtml);
    if (is_dir(__DIR__ . '/../public')) {
        file_put_contents(__DIR__ . '/../public/profile.html', $profileHtml);
    }
    echo "Successfully exported Entrepreneurship Profile to static profile.html (" . strlen($profileHtml) . " bytes)\n";
}

// 3. Export Insights Listing Page (insights.html)
$insightsView = __DIR__ . '/../resources/views/insights.php';
if (file_exists($insightsView)) {
    $posts = \App\Models\BlogInsight::published();
    $page_title = 'Insights — Ganga Sagar Joshi';

    ob_start();
    require $insightsView;
    $insightsHtml = ob_get_clean();

    file_put_contents(__DIR__ . '/../insights.html', $insightsHtml);
    if (is_dir(__DIR__ . '/../public')) {
        file_put_contents(__DIR__ . '/../public/insights.html', $insightsHtml);
    }
    echo "Successfully exported Insights listing to static insights.html (" . strlen($insightsHtml) . " bytes)\n";
}

// 4. Export Insight Detail Page (insight-detail.html & insight.html)
$detailView = __DIR__ . '/../resources/views/insight_detail.php';
if (file_exists($detailView)) {
    $all_posts = \App\Models\BlogInsight::published();
    $post = !empty($all_posts) ? $all_posts[0] : null;
    $recent = !empty($post) ? \App\Models\BlogInsight::recent(5, $post['id']) : [];

    ob_start();
    require $detailView;
    $detailHtml = ob_get_clean();

    file_put_contents(__DIR__ . '/../insight-detail.html', $detailHtml);
    file_put_contents(__DIR__ . '/../insight.html', $detailHtml);
    if (is_dir(__DIR__ . '/../public')) {
        file_put_contents(__DIR__ . '/../public/insight-detail.html', $detailHtml);
        file_put_contents(__DIR__ . '/../public/insight.html', $detailHtml);
    }
    echo "Successfully exported Insight Detail to static insight-detail.html (" . strlen($detailHtml) . " bytes)\n";

    // 5. Export individual static HTML files for each slug
    $insightsDir = __DIR__ . '/../insights';
    if (!is_dir($insightsDir)) {
        mkdir($insightsDir, 0777, true);
    }
    $publicInsightsDir = __DIR__ . '/../public/insights';
    if (!is_dir($publicInsightsDir)) {
        mkdir($publicInsightsDir, 0777, true);
    }

    foreach ($all_posts as $singlePost) {
        $post = $singlePost;
        $recent = \App\Models\BlogInsight::recent(5, $post['id']);

        ob_start();
        require $detailView;
        $singleHtml = ob_get_clean();

        // Adjust relative asset paths for subfolder if needed
        $singleHtmlAdjusted = str_replace(
            ['href="css/', 'src="images/', 'href="index.html', 'href="profile.html', 'href="insights.html'],
            ['href="../css/', 'src="../images/', 'href="../index.html', 'href="../profile.html', 'href="../insights.html'],
            $singleHtml
        );

        file_put_contents($insightsDir . '/' . $singlePost['slug'] . '.html', $singleHtmlAdjusted);
        file_put_contents($publicInsightsDir . '/' . $singlePost['slug'] . '.html', $singleHtmlAdjusted);
    }
    echo "Successfully generated standalone static HTML files for " . count($all_posts) . " insight articles.\n";
}

// 6. Export Project Detail Template
$projectDetailView = __DIR__ . '/../resources/views/project_detail.php';
if (file_exists($projectDetailView)) {
    $all_projects = \App\Models\FeaturedProject::allWithSkills();
    $project = !empty($all_projects) ? $all_projects[0] : null;
    $is_subdir = false;

    ob_start();
    require $projectDetailView;
    $projectDetailHtml = ob_get_clean();

    file_put_contents(__DIR__ . '/../project-detail.html', $projectDetailHtml);
    if (is_dir(__DIR__ . '/../public')) {
        file_put_contents(__DIR__ . '/../public/project-detail.html', $projectDetailHtml);
    }
    echo "Successfully exported Project Detail view to project-detail.html (" . strlen($projectDetailHtml) . " bytes)\n";
}

// 7. Sync projects.html
$indexContent = file_get_contents(__DIR__ . '/../index.html');
if (file_exists(__DIR__ . '/../projects.html')) {
    preg_match('/<div class="home-projects-grid">.*?<\/div>\s*<!-- ── Key Academic/s', $indexContent, $gridMatch);
    if (!empty($gridMatch[0])) {
        $cleanGrid = str_replace('<!-- ── Key Academic', '', $gridMatch[0]);
        $phtml = file_get_contents(__DIR__ . '/../projects.html');
        $phtml = preg_replace('/<div class="home-projects-grid">.*?<\/div>\s*<!-- ── Key Academic/s', $cleanGrid . '<!-- ── Key Academic', $phtml);

        file_put_contents(__DIR__ . '/../projects.html', $phtml);
        if (is_dir(__DIR__ . '/../public')) {
            file_put_contents(__DIR__ . '/../public/projects.html', $phtml);
        }
        echo "Successfully synced projects.html (" . strlen($phtml) . " bytes)\n";
    }
}
