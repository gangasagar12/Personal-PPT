<?php
namespace App\Models;

use PDO;

class Promotion extends Model {
    protected static string $table = 'promotions';

    /**
     * Get the latest active promotion for frontend pop-up display
     */
    public static function getActive(): ?array {
        $stmt = self::db()->query("SELECT * FROM promotions WHERE is_active = 1 ORDER BY id DESC LIMIT 1");
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Get all promotions for admin management
     */
    public static function allOrdered(): array {
        $stmt = self::db()->query("SELECT * FROM promotions ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    /**
     * Save (create or update) promotion
     */
    public static function saveItem(array $data): bool {
        $db = self::db();
        $id = isset($data['id']) ? (int)$data['id'] : 0;
        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $image = trim($data['image'] ?? 'images/projectcard1.jpg');
        $button_text = trim($data['button_text'] ?? 'Claim Offer');
        $button_url = trim($data['button_url'] ?? '#contact');
        $is_active = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        if ($id > 0) {
            $stmt = $db->prepare("UPDATE promotions SET title = ?, description = ?, image = ?, button_text = ?, button_url = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$title, $description, $image, $button_text, $button_url, $is_active, $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO promotions (title, description, image, button_text, button_url, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$title, $description, $image, $button_text, $button_url, $is_active]);
        }
    }

    /**
     * Toggle active state
     */
    public static function toggleStatus(int $id): bool {
        $stmt = self::db()->prepare("UPDATE promotions SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
