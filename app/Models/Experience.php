<?php
declare(strict_types=1);

namespace App\Models;

class Experience extends Model {
    protected static string $table = 'experiences';
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
                $check = $db->query("SELECT 1 FROM experiences LIMIT 1");
                if ($check !== false) $tableExists = true;
            } catch (\Throwable $t) {
                $tableExists = false;
            }

            if (!$tableExists) {
                $db->exec("CREATE TABLE IF NOT EXISTS experiences (
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
            }

            // Check if table contains old placeholder records (e.g. Inovix) or is empty
            $needsSync = false;
            try {
                $hasInovix = $db->query("SELECT 1 FROM experiences WHERE company LIKE '%Inovix%' LIMIT 1");
                if ($hasInovix && $hasInovix->fetch()) {
                    $needsSync = true;
                } else {
                    $count = (int)$db->query("SELECT COUNT(*) FROM experiences")->fetchColumn();
                    if ($count === 0) {
                        $needsSync = true;
                    }
                }
            } catch (\Throwable $t) {}

            if ($needsSync) {
                // Remove legacy placeholder records and insert the real experiences
                $db->exec("DELETE FROM experiences WHERE company LIKE '%Inovix%' OR company LIKE '%Strataflow%' OR company LIKE '%Independent%' OR id IN (1, 2, 3)");

                $stmt = $db->prepare("INSERT INTO experiences (id, company, role, period, location, status, description, responsibilities, skills, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach (self::defaults() as $d) {
                    $stmt->execute([
                        $d['id'],
                        $d['company'],
                        $d['role'],
                        $d['period'],
                        $d['location'],
                        $d['status'] ?? 'Active',
                        $d['description'],
                        implode("\n", $d['responsibilities_array'] ?? []),
                        implode(', ', $d['skills_array'] ?? []),
                        $d['display_order']
                    ]);
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Retrieve all experiences with formatted arrays for skills and responsibilities.
     */
    public static function allWithDetails(): array {
        try {
            self::ensureTable();
            $items = self::all('display_order ASC, id ASC');
            foreach ($items as &$item) {
                $item['skills_array'] = !empty($item['skills']) ? array_map('trim', explode(',', $item['skills'])) : [];
                $item['responsibilities_array'] = !empty($item['responsibilities']) ? array_filter(array_map('trim', explode("\n", $item['responsibilities']))) : [];
            }
            return $items;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Fallback experiences data if table is not yet seeded.
     */
    public static function defaults(): array {
        return [
            [
                'id' => 1,
                'company' => 'Strataflow Technologies Pvt. Ltd.',
                'role' => 'Co-Founder & Technical Lead',
                'period' => '2026 – Present',
                'location' => 'Kathmandu, Nepal',
                'status' => 'Active',
                'company_url' => 'https://strataflowtechnologies.com',
                'description' => 'Directing system architecture, database optimization, and scalable backend services for client institutions and in-house enterprise SaaS products.',
                'responsibilities_array' => [
                    'Lead the company technical strategy, software development direction, and overall technology operations.',
                    'Oversee system architecture, technology selection, development standards, and the delivery of scalable software solutions.',
                    'Drive product innovation, coordinate technical teams, and align technology initiatives with business goals and client requirements.'

                ],
                'skills_array' => ['Python', 'Django', 'PostgreSQL', 'REST Framework', 'AWS', 'System Architecture', 'MySQL','Linux'],
                'display_order' => 1
            ],
       
[
    'id' => 2,
    'company' => 'Self-Employed',
    'role' => 'Freelance Software Developer',
    'period' => '2025 – 2026',
    'location' => 'Kathmandu, Nepal',
    'status' => 'Completed',
    'company_url' => '',
    'description' => 'Providing independent software development services, building custom web applications and backend solutions based on client requirements.',
    'responsibilities_array' => [
        'Developed custom web applications and backend solutions for individual clients and businesses.',
        'Managed project requirements, development, testing, and delivery independently.',
        'Collaborated with clients to understand their needs and implement practical software solutions.'
    ],
    'skills_array' => ['Python', 'Django', 'MySQL', 'PostgreSQL', 'REST APIs', 'Git'],
    'display_order' => 2
]
           
        ];
    }
}

