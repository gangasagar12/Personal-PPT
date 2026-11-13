<?php
namespace App\Models;

class BlogInsight extends Model {
    protected static string $table = 'blog_insights';

    public static function published(): array {
        $stmt = self::db()->query("SELECT * FROM " . self::$table . " WHERE is_published = 1 ORDER BY display_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public static function saveItem(array $data): bool {
        $db = self::db();
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title'])));

        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET title=?, slug=?, category=?, read_time=?, excerpt=?, content=?, date=?, display_order=? WHERE id=?");
            return $stmt->execute([
                $data['title'],
                $slug,
                $data['category'],
                $data['read_time'],
                $data['excerpt'],
                $data['content'] ?? '',
                $data['date'],
                $data['display_order'] ?? 0,
                $data['id']
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (title, slug, category, read_time, excerpt, content, date, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $slug,
                $data['category'],
                $data['read_time'],
                $data['excerpt'],
                $data['content'] ?? '',
                $data['date'],
                $data['display_order'] ?? 0
            ]);
        }
    }
}

