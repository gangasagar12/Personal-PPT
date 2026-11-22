<?php
namespace App\Core;

class SEO {
    /**
     * Get preferred canonical base URL (from .env APP_URL or default production domain)
     */
    public static function baseUrl(): string {
        $appUrl = '';
        if (function_exists('env')) {
            $appUrl = rtrim((string)env('APP_URL', ''), '/');
        } elseif (function_exists('\\env')) {
            $appUrl = rtrim((string)\env('APP_URL', ''), '/');
        } elseif (isset($_ENV['APP_URL'])) {
            $appUrl = rtrim((string)$_ENV['APP_URL'], '/');
        } elseif (getenv('APP_URL')) {
            $appUrl = rtrim((string)getenv('APP_URL'), '/');
        }

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
            'email' => 'mailto:info@gangasagar.com.np',
            'image' => [
                '@type' => 'ImageObject',
                '@id' => $base . '/#profile-image',
                'url' => $base . '/images/ganga-sagar-joshi-profile.jpeg',
                'contentUrl' => $base . '/images/ganga-sagar-joshi-profile.jpeg',
                'caption' => 'Ganga Sagar Joshi'
            ],
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
     * Get ProfilePage Schema (JSON-LD) - Helps Google index author as person entity with image
     */
    public static function profilePageSchema(): array {
        $base = self::baseUrl();
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            '@id' => $base . '/#profilepage',
            'url' => $base,
            'name' => 'Ganga Sagar Joshi — Software Developer & Django Backend Engineer',
            'description' => 'Official portfolio and profile of Ganga Sagar Joshi, Software Developer & Technology Entrepreneur.',
            'primaryImageOfPage' => [
                '@type' => 'ImageObject',
                '@id' => $base . '/#profile-image',
                'url' => $base . '/images/ganga-sagar-joshi-profile.jpeg'
            ],
            'mainEntity' => [
                '@id' => $base . '/#person'
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
