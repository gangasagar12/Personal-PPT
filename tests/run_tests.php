<?php
/**
 * Automated Test Runner
 * Executes unit and integration tests across application models and routes.
 */

require_once dirname(__DIR__) . '/config/env.php';
require_once dirname(__DIR__) . '/app/Core/Autoloader.php';
\App\Core\Autoloader::register();

require_once __DIR__ . '/DatabaseTest.php';
require_once __DIR__ . '/RouteTest.php';

$passed = 0;
$failed = 0;

function runTest(string $suite, string $method, callable $callback) {
    global $passed, $failed;
    echo "Running [{$suite}::{$method}]... ";
    try {
        $result = $callback();
        if ($result === true) {
            echo "PASSED\n";
            $passed++;
        } else {
            echo "FAILED (returned false)\n";
            $failed++;
        }
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        $failed++;
    }
}

echo "=============================================\n";
echo " Ganga Sagar Joshi Portfolio — Automated Tests\n";
echo "=============================================\n";

$dbTest = new \Tests\DatabaseTest();
runTest('DatabaseTest', 'testConnection', [$dbTest, 'testConnection']);
runTest('DatabaseTest', 'testFeaturedProjectsCount', [$dbTest, 'testFeaturedProjectsCount']);
runTest('DatabaseTest', 'testAcademicProjectsCount', [$dbTest, 'testAcademicProjectsCount']);
runTest('DatabaseTest', 'testBlogInsightsCount', [$dbTest, 'testBlogInsightsCount']);
runTest('DatabaseTest', 'testCompanyProductsCount', [$dbTest, 'testCompanyProductsCount']);
runTest('DatabaseTest', 'testBlogInsightFindBySlug', [$dbTest, 'testBlogInsightFindBySlug']);
runTest('DatabaseTest', 'testBlogInsightRecent', [$dbTest, 'testBlogInsightRecent']);
runTest('DatabaseTest', 'testFeaturedProjectFindBySlug', [$dbTest, 'testFeaturedProjectFindBySlug']);
runTest('DatabaseTest', 'testExperienceCount', [$dbTest, 'testExperienceCount']);

$routeTest = new \Tests\RouteTest();
runTest('RouteTest', 'testRoutesFileExists', [$routeTest, 'testRoutesFileExists']);
runTest('RouteTest', 'testViewTemplatesExist', [$routeTest, 'testViewTemplatesExist']);
runTest('RouteTest', 'testDynamicProjectController', [$routeTest, 'testDynamicProjectController']);
runTest('RouteTest', 'testExperienceController', [$routeTest, 'testExperienceController']);
runTest('RouteTest', 'testAdminAuthCredentials', [$routeTest, 'testAdminAuthCredentials']);
runTest('RouteTest', 'testAdminDashboardRendering', [$routeTest, 'testAdminDashboardRendering']);

echo "---------------------------------------------\n";
echo "Results: {$passed} Passed, {$failed} Failed\n";
echo "=============================================\n";

exit($failed > 0 ? 1 : 0);

