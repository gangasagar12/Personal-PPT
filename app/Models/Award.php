<?php
declare(strict_types=1);

namespace App\Models;

class Award extends Model {
    protected static string $table = 'awards';
    private static bool $schemaChecked = false;

    /**
     * Ensure table exists in MySQL and seed initial records only on first creation
     */
    public static function ensureTable(): void {
        if (self::$schemaChecked) return;
        self::$schemaChecked = true;

        try {
            $db = self::db();
            $driver = $db->getAttribute(\PDO::ATTR_DRIVER_NAME);
            $autoIncrement = ($driver === 'sqlite') ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
            $textType = ($driver === 'sqlite') ? 'TEXT' : 'VARCHAR(255)';
            $longTextType = ($driver === 'sqlite') ? 'TEXT' : 'LONGTEXT';

            $tableExists = false;
            try {
                $check = $db->query("SELECT 1 FROM awards LIMIT 1");
                if ($check !== false) $tableExists = true;
            } catch (\Throwable $t) {
                $tableExists = false;
            }

            if (!$tableExists) {
                $db->exec("CREATE TABLE IF NOT EXISTS awards (
                    id {$autoIncrement},
                    title {$textType} NOT NULL,
                    issuer {$textType} NOT NULL,
                    date {$textType} NOT NULL,
                    image {$textType} NOT NULL,
                    description {$longTextType} NOT NULL,
                    display_order INTEGER DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )");

                // Insert initial default records once so they are real deletable database rows
                $stmt = $db->prepare("INSERT INTO awards (id, title, issuer, date, image, description, display_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
                foreach (self::defaults() as $d) {
                    $stmt->execute([
                        $d['id'],
                        $d['title'],
                        $d['issuer'],
                        $d['date'],
                        $d['image'],
                        $d['description'],
                        $d['display_order']
                    ]);
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Retrieve all awards from database.
     * When records are deleted, it returns the remaining records (even if empty []).
     */
    public static function allWithDetails(): array {
        try {
            self::ensureTable();
            return self::all('display_order ASC, id ASC');
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Save or update an award.
     */
    public static function saveItem(array $data): bool {
        $db = self::db();
        $image = !empty($data['image']) ? trim($data['image']) : 'images/projectcard1.jpg';
        
        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET title = ?, issuer = ?, date = ?, image = ?, description = ?, display_order = ? WHERE id = ?");
            return $stmt->execute([
                $data['title'],
                $data['issuer'] ?? '',
                $data['date'] ?? '',
                $image,
                $data['description'] ?? '',
                $data['display_order'] ?? 0,
                $data['id']
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (title, issuer, date, image, description, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $data['issuer'] ?? '',
                $data['date'] ?? '',
                $image,
                $data['description'] ?? '',
                $data['display_order'] ?? 0
            ]);
        }
    }

    /**
     * Default awards / gallery milestones.
     */
    public static function defaults(): array {
        return [
            [
                'id' => 1,
                'title' => 'Excellence in Software Engineering & Database Systems',
                'issuer' => 'Kantipur City College & Purbanchal University',
                'date' => '2025',
                'image' => 'images/projectcard1.jpg',
                'description' => 'Honored for outstanding academic performance, complex relational database architecture design, and high-performance server-side system implementations.',
                'display_order' => 1
            ],
            [
                'id' => 2,
                'title' => 'Best Clinical Architecture & HealthTech Innovation',
                'issuer' => 'Inovix & Health System Stakeholders',
                'date' => '2024',
                'image' => 'images/projectcard2.jpg',
                'description' => 'Recognized for designing Oralix Dental Practice OS, unifying multi-chair scheduling, electronic health records, and automated clinic billing engines.',
                'display_order' => 2
            ],
            [
                'id' => 3,
                'title' => 'Open Source Backend Engineering Recognition',
                'issuer' => 'Developer Community & Tech Hackathon',
                'date' => '2023',
                'image' => 'images/projectcard3.webp',
                'description' => 'Awarded for developing high-throughput Python RESTful APIs, scalable JWT auth services, and contributing maintainable database blueprints.',
                'display_order' => 3
            ]
        ];
    }
}

