<?php
declare(strict_types=1);

namespace App\Models;

class Award extends Model {
    protected static string $table = 'awards';

    /**
     * Retrieve all awards/recognitions with fallback defaults.
     */
    public static function allWithDetails(): array {
        try {
            $items = self::all('display_order ASC, id ASC');
            if (!empty($items)) {
                return $items;
            }
        } catch (\Throwable $e) {}

        return self::defaults();
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

