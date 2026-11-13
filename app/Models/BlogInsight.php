<?php
namespace App\Models;

class BlogInsight extends Model {
    protected static string $table = 'blog_insights';

    public static function published(): array {
        $stmt = self::db()->query("SELECT * FROM " . self::$table . " WHERE is_published = 1 ORDER BY display_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array {
        $stmt = self::db()->prepare("SELECT * FROM " . self::$table . " WHERE slug = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findById(int $id): ?array {
        $stmt = self::db()->prepare("SELECT * FROM " . self::$table . " WHERE id = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function recent(int $limit = 4, ?int $excludeId = null): array {
        if ($excludeId !== null) {
            $stmt = self::db()->prepare("SELECT * FROM " . self::$table . " WHERE is_published = 1 AND id != ? ORDER BY display_order ASC, id DESC LIMIT ?");
            $stmt->bindValue(1, $excludeId, \PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = self::db()->prepare("SELECT * FROM " . self::$table . " WHERE is_published = 1 ORDER BY display_order ASC, id DESC LIMIT ?");
            $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public static function saveItem(array $data): bool {
        $db = self::db();
        $slug = !empty($data['slug']) 
            ? strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['slug'])))
            : strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['title'])));
        $image = !empty($data['image']) ? trim($data['image']) : 'images/projectcard1.jpg';

        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET title=?, slug=?, category=?, image=?, read_time=?, excerpt=?, content=?, date=?, display_order=? WHERE id=?");
            return $stmt->execute([
                $data['title'],
                $slug,
                $data['category'],
                $image,
                $data['read_time'],
                $data['excerpt'],
                $data['content'] ?? '',
                $data['date'],
                $data['display_order'] ?? 0,
                $data['id']
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (title, slug, category, image, read_time, excerpt, content, date, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $slug,
                $data['category'],
                $image,
                $data['read_time'],
                $data['excerpt'],
                $data['content'] ?? '',
                $data['date'],
                $data['display_order'] ?? 0
            ]);
        }
    }
}
