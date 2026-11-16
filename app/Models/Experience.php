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
                'period' => '2024 – Present',
                'location' => 'Kathmandu, Nepal',
                'status' => 'Active',
                'company_url' => 'https://strataflowtechnologies.com',
                'description' => 'Directing system architecture, database optimization, and scalable backend services for client institutions and in-house enterprise SaaS products.',
                'responsibilities_array' => [
                    'Architected and deployed Oralix and CareFirst Dental clinical practice OS platforms with multi-chair scheduling and EHR management.',
                    'Engineered normalized PostgreSQL and MySQL database schemas handling clinical appointments, treatments, and automated billing calculation.',
                    'Implemented stateless REST APIs, JWT authentication, and secure role-based access control (RBAC).',
                    'Guided software product strategy, selecting high-performance technology stacks and leading code reviews.'
                ],
                'skills_array' => ['Python', 'Django', 'PostgreSQL', 'REST Framework', 'AWS', 'System Architecture', 'MySQL'],
                'display_order' => 1
            ],
            [
                'id' => 2,
                'company' => 'Inovix Technologies',
                'role' => 'Backend Developer & Co-Founder',
                'period' => '2023 – 2024',
                'location' => 'Kathmandu, Nepal',
                'status' => 'Completed',
                'company_url' => '',
                'description' => 'Engineered backend platforms and APIs for healthcare, education, and institutional client applications.',
                'responsibilities_array' => [
                    'Built high-performance Django web platforms and integrated appointment queuing algorithms.',
                    'Optimized ORM queries and indexing, cutting database query latency by over 35%.',
                    'Developed automated invoice generators, PDF clinical reporting engines, and transactional email notification systems.',
                    'Collaborated cross-functionally with UI designers and medical teams to translate clinic workflows into software.'
                ],
                'skills_array' => ['Python', 'Django', 'MySQL', 'Docker', 'REST APIs', 'Git', 'HTML5/CSS3'],
                'display_order' => 2
            ],
            [
                'id' => 3,
                'company' => 'Independent Software Engineering',
                'role' => 'Full-Stack Developer & Technical Consultant',
                'period' => '2022 – 2023',
                'location' => 'Remote / Nepal',
                'status' => 'Completed',
                'company_url' => '',
                'description' => 'Delivered custom software systems, interactive e-commerce backends, and tailored database solutions for diverse businesses.',
                'responsibilities_array' => [
                    'Designed and deployed custom e-commerce and inventory tracking platforms with secure payment gateways.',
                    'Built modern responsive storefronts and administrative control panels with intuitive UX.',
                    'Delivered clean, maintainable codebases with structured documentation and robust error handling.'
                ],
                'skills_array' => ['Python', 'Django', 'JavaScript', 'SQLite', 'PostgreSQL', 'Web Security'],
                'display_order' => 3
            ]
        ];
    }
}

