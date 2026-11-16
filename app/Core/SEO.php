<?php
namespace App\Core;

class SEO {
    /**
     * Get preferred canonical base URL (from .env APP_URL or default production domain)
     */
    public static function baseUrl(): string {
        $appUrl = rtrim((string)env('APP_URL', ''), '/');
        if (!empty($appUrl) && $appUrl !== 'http://localhost:8000') {
            return $appUrl;
        }
        
        // Detect current environment scheme & host
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $scheme = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'gangasagar.com.np';

        // If local development, use current host, otherwise default to production domain
        if (str_contains($host, 'localhost') || str_contains($host, '127.0.0.1')) {
            return $scheme . '://' . $host;
        }

        return 'https://gangasagar.com.np';
    }

    /**
     * Get absolute URL for path
     */
    public static function url(string $path = '/'): string {
        $cleanPath = '/' . ltrim($path, '/');
        return self::baseUrl() . ($cleanPath === '/' ? '' : $cleanPath);
    }

    /**
     * Get Person Schema (JSON-LD)
     */
    public static function personSchema(): array {
        $base = self::baseUrl();
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => $base . '/#person',
            'name' => 'Ganga Sagar Joshi',
            'alternateName' => ['Ganga Sagar', 'Ganga Joshi'],
            'url' => $base,
            'image' => $base . '/images/ganga-sagar-joshi.png',
            'jobTitle' => 'Software Developer & Backend Engineer',
            'description' => 'Software Developer and Co-Founder at Strataflow Technologies, specializing in Python, Django backend engineering, scalable relational databases, and enterprise systems.',
            'worksFor' => [
                '@type' => 'Organization',
                'name' => 'Strataflow Technologies Pvt. Ltd.',
                'url' => $base . '/profile'
            ],
            'alumniOf' => [
                '@type' => 'CollegeOrUniversity',
                'name' => 'Kantipur City College',
                'sameAs' => 'https://kcc.edu.np/'
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Kathmandu',
                'addressRegion' => 'Bagmati',
                'addressCountry' => 'NP'
            ],
            'sameAs' => [
                'https://github.com/gangasagar12',
                'https://www.linkedin.com/in/ganga-sagar15'
            ],
            'knowsAbout' => [
                'Python',
                'Django',
                'RESTful APIs',
                'PostgreSQL',
                'MySQL',
                'Docker',
                'Linux Systems',
                'Machine Learning',
                'Relational Database Architecture',
                'SaaS Product Engineering'
            ]
        ];
    }

    /**
     * Get WebSite Schema (JSON-LD)
     */
    public static function websiteSchema(): array {
        $base = self::baseUrl();
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $base . '/#website',
            'url' => $base,
            'name' => 'Ganga Sagar Joshi Portfolio',
            'description' => 'Official portfolio and software engineering showcase of Ganga Sagar Joshi.',
            'publisher' => [
                '@id' => $base . '/#person'
            ]
        ];
    }
}
