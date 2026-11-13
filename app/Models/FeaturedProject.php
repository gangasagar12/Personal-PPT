<?php
namespace App\Models;

class FeaturedProject extends Model {
    protected static string $table = 'featured_projects';

    public static function allWithSkills(): array {
        $projects = self::all('display_order ASC, id ASC');
        foreach ($projects as &$p) {
            $p['skills_array'] = !empty($p['skills']) ? array_map('trim', explode(',', $p['skills'])) : [];
            $p['features_array'] = !empty($p['features']) ? (json_decode($p['features'], true) ?: array_filter(array_map('trim', explode("\n", $p['features'])))) : [];
            $p['screenshots_array'] = !empty($p['screenshots']) ? json_decode($p['screenshots'], true) : [];
            $p['display_desc'] = !empty($p['short_description']) ? $p['short_description'] : $p['description'];
        }
        return $projects;
    }

    public static function findBySlug(string $slug): ?array {
        $stmt = self::db()->prepare("SELECT * FROM " . self::$table . " WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        if ($row) {
            $row['skills_array'] = !empty($row['skills']) ? array_map('trim', explode(',', $row['skills'])) : [];
            $row['features_array'] = !empty($row['features']) ? (json_decode($row['features'], true) ?: array_filter(array_map('trim', explode("\n", $row['features'])))) : [];
            $row['screenshots_array'] = !empty($row['screenshots']) ? json_decode($row['screenshots'], true) : [];
            return $row;
        }
        return null;
    }

    public static function findWithDetails(int $id): ?array {
        $row = self::find($id);
        if ($row) {
            $row['skills_array'] = !empty($row['skills']) ? array_map('trim', explode(',', $row['skills'])) : [];
            $row['features_array'] = !empty($row['features']) ? (json_decode($row['features'], true) ?: array_filter(array_map('trim', explode("\n", $row['features'])))) : [];
            $row['screenshots_array'] = !empty($row['screenshots']) ? json_decode($row['screenshots'], true) : [];
            return $row;
        }
        return null;
    }

    public static function saveItem(array $data): bool {
        $db = self::db();
        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET title=?, description=?, image=?, skills=?, live_url=?, display_order=? WHERE id=?");
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $data['image'],
                $data['skills'],
                $data['live_url'],
                $data['display_order'] ?? 0,
                $data['id']
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (title, description, image, skills, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $data['image'],
                $data['skills'],
                $data['live_url'],
                $data['display_order'] ?? 0
            ]);
        }
    }
}
