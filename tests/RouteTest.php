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
        return $home && $profile && $insights && $insightDetail;
    }
}

