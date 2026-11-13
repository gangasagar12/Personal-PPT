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
                    $dsn = "mysql:host={$conn['host']};port={$conn['port']};dbname={$conn['database']};charset={$conn['charset']}";
                    self::$instance = new PDO($dsn, $conn['username'], $conn['password']);
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
            title {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            image {$textType} NOT NULL,
            skills {$textType} NOT NULL,
            live_url {$textType} NOT NULL,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS academic_projects (
            id {$autoIncrement},
            title {$textType} NOT NULL,
            description {$longTextType} NOT NULL,
            skills {$textType} NOT NULL,
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
    }
}

