<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';
            $default = $config['default'];
            $conn = $config['connections'][$default];

            try {
                if ($default === 'sqlite') {
                    $dir = dirname($conn['database']);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    self::$instance = new PDO("sqlite:" . $conn['database']);
                } else {
                    $host = !empty($conn['host']) ? $conn['host'] : 'localhost';
                    $db = $conn['database'] ?? '';
                    $user = $conn['username'] ?? 'root';
                    $pass = $conn['password'] ?? '';
                    $charset = $conn['charset'] ?? 'utf8mb4';
                    $port = !empty($conn['port']) ? $conn['port'] : 3306;

                    // Build candidate DSNs to guarantee connectivity across environments (cPanel/Linux vs Local/Windows)
                    $dsnCandidates = [];

                    if (!empty($conn['socket'])) {
                        $dsnCandidates[] = "mysql:unix_socket={$conn['socket']};dbname={$db};charset={$charset}";
                    }

                    if ($host === 'localhost') {
                        $dsnCandidates[] = "mysql:host=localhost;dbname={$db};charset={$charset}";
                        $dsnCandidates[] = "mysql:host=127.0.0.1;port={$port};dbname={$db};charset={$charset}";
                    } elseif ($host === '127.0.0.1') {
                        $dsnCandidates[] = "mysql:host=127.0.0.1;port={$port};dbname={$db};charset={$charset}";
                        $dsnCandidates[] = "mysql:host=localhost;dbname={$db};charset={$charset}";
                    } else {
                        $dsnCandidates[] = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
                        $dsnCandidates[] = "mysql:host=localhost;dbname={$db};charset={$charset}";
                    }

                    // Common Linux cPanel unix socket fallbacks if TCP connection is refused
                    $commonSockets = ['/var/lib/mysql/mysql.sock', '/tmp/mysql.sock', '/var/run/mysqld/mysqld.sock'];
                    foreach ($commonSockets as $sock) {
                        if (@file_exists($sock)) {
                            $dsnCandidates[] = "mysql:unix_socket={$sock};dbname={$db};charset={$charset}";
                        }
                    }

                    $connected = false;
                    $lastException = null;

                    foreach ($dsnCandidates as $candidateDsn) {
                        try {
                            self::$instance = new PDO($candidateDsn, $user, $pass, [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_TIMEOUT => 4
                            ]);
                            $connected = true;
                            break;
                        } catch (PDOException $pe) {
                            $lastException = $pe;
                            // If error is 1049 (Unknown database), try auto-creating if privileges permit
                            if ($pe->getCode() == 1049 || strpos($pe->getMessage(), 'Unknown database') !== false) {
                                try {
                                    $rootDsn = preg_replace('/dbname=[^;]+;?/', '', $candidateDsn);
                                    $initPdo = new PDO($rootDsn, $user, $pass);
                                    $initPdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                                    self::$instance = new PDO($candidateDsn, $user, $pass, [
                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                                    ]);
                                    $connected = true;
                                    break;
                                } catch (\Throwable $t) {}
                            }
                        }
                    }

                    if (!$connected) {
                        throw $lastException ?? new PDOException("Could not connect to MySQL database.");
                    }
                }

                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                self::ensureSchema();

            } catch (PDOException $e) {
                die("Database Connection Error: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    private static function ensureSchema(): void {
        $pdo = self::$instance;
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        $autoIncrement = ($driver === 'sqlite') ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
        $textType = ($driver === 'sqlite') ? 'TEXT' : 'VARCHAR(255)';
        $longTextType = ($driver === 'sqlite') ? 'TEXT' : 'LONGTEXT';

        // Create tables if they do not exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS featured_projects (
            id {$autoIncrement},
            slug {$textType},
            title {$textType} NOT NULL,
            short_description {$longTextType},
            description {$longTextType} NOT NULL,
            category {$textType},
            category_badge {$textType},
            official_site {$textType},
            impact {$longTextType},
            screenshots {$longTextType},
            image {$textType} NOT NULL,
            skills {$textType} NOT NULL,
            client_name {$textType},
            location {$textType},
            features {$longTextType},
            live_url {$textType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Ensure missing columns exist in featured_projects
        $fpCols = [];
        if ($driver === 'sqlite') {
            $stmt = $pdo->query("PRAGMA table_info(featured_projects)");
            while ($r = $stmt->fetch()) {
                $fpCols[] = strtolower($r['name']);
            }
        }
        $neededCols = [
            'slug' => $textType,
            'short_description' => $longTextType,
            'category' => $textType,
            'category_badge' => $textType,
            'official_site' => $textType,
            'impact' => $longTextType,
            'screenshots' => $longTextType,
            'client_name' => $textType,
            'location' => $textType,
            'features' => $longTextType
        ];
        foreach ($neededCols as $c => $type) {
            if (!empty($fpCols) && !in_array($c, $fpCols)) {
                try {
                    $pdo->exec("ALTER TABLE featured_projects ADD COLUMN {$c} {$type}");
                } catch (\Exception $e) {}
            }
        }

        $pdo->exec("CREATE TABLE IF NOT EXISTS academic_projects (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            detailed_description {$longTextType} NULL,
            image {$textType} DEFAULT 'images/projectcard1.jpg',
            skills {$textType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS other_projects (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            category {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            detailed_description {$longTextType} NULL,
            image {$textType} DEFAULT 'images/projectcard3.webp',
            skills {$textType} NOT NULL,
            github_url {$textType} DEFAULT 'https://github.com/gangasagar12',
            live_url {$textType} DEFAULT 'https://github.com/gangasagar12',
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS blog_insights (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            slug {$textType} NOT NULL,
            category {$textType} NOT NULL,
            image {$textType} DEFAULT 'images/projectcard1.jpg',
            read_time {$textType} NOT NULL,
            excerpt {$longTextType} NOT NULL,
            content {$longTextType} NOT NULL,
            date {$textType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            is_published INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS contact_inquiries (
            id {$autoIncrement},
            name {$textType} NOT NULL,
            email {$textType} NOT NULL,
            subject {$textType} NOT NULL,
            phone {$textType},
            message {$longTextType} NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS company_products (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            category {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            image {$textType} NOT NULL,
            status_text {$textType} NOT NULL,
            features {$longTextType} NOT NULL,
            tech_pills {$textType} NOT NULL,
            link_url {$textType} NOT NULL,
            link_text {$textType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS experiences (
            id {$autoIncrement},
            company {$textType} NOT NULL,
            role {$textType} NOT NULL,
            period {$textType} NOT NULL,
            location {$textType} NOT NULL,
            status {$textType} DEFAULT 'Active',
            description {$longTextType} NOT NULL,
            responsibilities {$longTextType} NOT NULL,
            skills {$textType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS awards (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            issuer {$textType} NOT NULL,
            date {$textType} NOT NULL,
            image {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS promotions (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            image {$textType} NOT NULL,
            button_text {$textType} DEFAULT 'Claim Offer',
            button_url {$textType} DEFAULT '#contact',
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }
}

