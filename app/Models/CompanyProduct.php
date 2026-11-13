<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class CompanyProduct extends Model {
    protected static string $table = 'company_products';

    /**
     * Fetch all products ordered by display_order with parsed features and tech pills
     */
    public static function allWithFeaturesAndPills(): array {
        self::seedInitialIfEmpty();

        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM " . static::$table . " ORDER BY display_order ASC, id ASC");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$p) {
            // Parse newline or semicolon delimited features
            $rawFeatures = preg_split('/[\r\n;]+/', $p['features'] ?? '');
            $p['features_array'] = array_values(array_filter(array_map('trim', $rawFeatures)));

            // Parse comma-delimited tech pills
            $rawPills = explode(',', $p['tech_pills'] ?? '');
            $p['pills_array'] = array_values(array_filter(array_map('trim', $rawPills)));
        }

        return $products;
    }

    /**
     * Auto-seed initial Strataflow products if table is empty
     */
    public static function seedInitialIfEmpty(): void {
        $pdo = Database::getConnection();
        $count = $pdo->query("SELECT COUNT(*) FROM " . static::$table)->fetchColumn();
        if ($count > 0) {
            return;
        }

        $items = [
            [
                'title' => 'Dental Clinic Management Software',
                'category' => 'Healthcare Operating Platform',
                'description' => 'One of our key products is dental clinic management software, designed to help dental clinics manage their daily operations through a single platform. It brings together essential features such as patient management, appointment scheduling, dental treatment records, billing, expense tracking, and financial reporting. By bringing these processes into one organized system, we aim to reduce manual work, improve accuracy, and help dental professionals spend more time focusing on patient care.',
                'image' => 'images/projectcard1.jpg',
                'status_text' => 'Live Product',
                'features' => "Patient Records & Medical History\nReal-time Appointment Scheduling\nDental Treatment & Charting Logs\nAutomated Billing & Invoicing\nClinic Expense & Stock Tracking\nFinancial Analytics & Reports",
                'tech_pills' => 'Python, Django, PostgreSQL, REST APIs, Docker',
                'link_url' => 'https://github.com/gangasaagar',
                'link_text' => 'View Details',
                'display_order' => 1
            ],
            [
                'title' => 'CareFirst Practice & Inventory Management',
                'category' => 'Front-Desk & Inventory Suite',
                'description' => 'Engineered for front-desk efficiency and inventory control. Manages patient queue tokens, waiting room flow, dental consumable supplies, and automated recall notifications with relational ACID database consistency.',
                'image' => 'images/projectcard2.jpg',
                'status_text' => 'Live Product',
                'features' => "Front-Desk Patient Intake & Queue\nConsumables & Medicine Inventory\nAutomated Recall Reminders\nRelational ACID Schema",
                'tech_pills' => 'Django, Python, MySQL, JavaScript',
                'link_url' => 'https://github.com/gangasaagar',
                'link_text' => 'View Details',
                'display_order' => 2
            ],
            [
                'title' => 'Business Billing & Accounting Solutions',
                'category' => 'Financial Infrastructure',
                'description' => 'Complete business invoicing, expense tracking, and financial reconciliation software designed to eliminate manual spreadsheet bookkeeping for growing enterprises and healthcare practices.',
                'image' => 'images/project2.jpg',
                'status_text' => 'Deployed System',
                'features' => "Itemized Billing & Tax Calculation\nMulti-Payment Ledger Tracking\nAutomated PDF Receipt Dispatch\nReal-time Revenue Summaries",
                'tech_pills' => 'Django REST, PostgreSQL, Redis, JWT Auth',
                'link_url' => 'https://github.com/gangasaagar',
                'link_text' => 'View Details',
                'display_order' => 3
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO " . static::$table . " 
            (title, category, description, image, status_text, features, tech_pills, link_url, link_text, display_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            $stmt->execute([
                $item['title'],
                $item['category'],
                $item['description'],
                $item['image'],
                $item['status_text'],
                $item['features'],
                $item['tech_pills'],
                $item['link_url'],
                $item['link_text'],
                $item['display_order']
            ]);
        }
    }

    /**
     * Create or update a product item
     */
    public static function saveItem(array $data, ?int $id = null): bool {
        $pdo = Database::getConnection();

        if ($id) {
            $stmt = $pdo->prepare("UPDATE " . static::$table . " SET 
                title = ?, category = ?, description = ?, image = ?, status_text = ?, 
                features = ?, tech_pills = ?, link_url = ?, link_text = ?, display_order = ? 
                WHERE id = ?");
            return $stmt->execute([
                $data['title'],
                $data['category'],
                $data['description'],
                $data['image'],
                $data['status_text'],
                $data['features'],
                $data['tech_pills'],
                $data['link_url'],
                $data['link_text'] ?? 'View Details',
                intval($data['display_order'] ?? 0),
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO " . static::$table . " 
                (title, category, description, image, status_text, features, tech_pills, link_url, link_text, display_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $data['category'],
                $data['description'],
                $data['image'],
                $data['status_text'],
                $data['features'],
                $data['tech_pills'],
                $data['link_url'],
                $data['link_text'] ?? 'View Details',
                intval($data['display_order'] ?? 0)
            ]);
        }
    }

    /**
     * Delete a product item
     */
    public static function deleteItem(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

