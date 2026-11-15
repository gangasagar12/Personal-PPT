<?php
namespace App\Controllers;

use App\Core\SEO;
use App\Models\FeaturedProject;
use App\Models\BlogInsight;

class SitemapController {
    /**
     * Generate dynamic XML Sitemap for Google & Bing
     */
    public function index(): void {
        header('Content-Type: application/xml; charset=utf-8');
        header('X-Robots-Tag: noindex, follow');

        $base = SEO::baseUrl();
        $today = date('Y-m-d');

        // Static Pages Definition
        $urls = [
            [
                'loc' => $base . '/',
                'lastmod' => $today,
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => $base . '/profile',
                'lastmod' => $today,
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $base . '/experience',
                'lastmod' => $today,
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $base . '/projects',
                'lastmod' => $today,
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $base . '/insights',
                'lastmod' => $today,
                'changefreq' => 'weekly',
                'priority' => '0.85'
            ]
        ];

        // Dynamic Featured Project Case Studies
        try {
            $projects = FeaturedProject::all();
            foreach ($projects as $p) {
                $slug = !empty($p['slug']) ? $p['slug'] : FeaturedProject::slugify($p['title']);
                $urls[] = [
                    'loc' => $base . '/works/' . urlencode($slug),
                    'lastmod' => !empty($p['created_at']) ? date('Y-m-d', strtotime($p['created_at'])) : $today,
                    'changefreq' => 'monthly',
                    'priority' => '0.8'
                ];
            }
        } catch (\Throwable $e) {}

        // Dynamic Blog Insights / Articles
        try {
            $blogs = BlogInsight::published();
            foreach ($blogs as $b) {
                if (!empty($b['slug'])) {
                    $urls[] = [
                        'loc' => $base . '/insights/' . urlencode($b['slug']),
                        'lastmod' => !empty($b['created_at']) ? date('Y-m-d', strtotime($b['created_at'])) : $today,
                        'changefreq' => 'monthly',
                        'priority' => '0.8'
                    ];
                }
            }
        } catch (\Throwable $e) {}

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($u['loc']) . "</loc>\n";
            echo "    <lastmod>" . $u['lastmod'] . "</lastmod>\n";
            echo "    <changefreq>" . $u['changefreq'] . "</changefreq>\n";
            echo "    <priority>" . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }

    /**
     * Generate dynamic robots.txt
     */
    public function robots(): void {
        header('Content-Type: text/plain; charset=utf-8');
        $base = SEO::baseUrl();

        echo "# robots.txt — Ganga Sagar Joshi Portfolio\n";
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /backend/\n";
        echo "Disallow: /config/\n";
        echo "Disallow: /database/\n\n";
        echo "Sitemap: " . $base . "/sitemap.xml\n";
        exit;
    }
}
