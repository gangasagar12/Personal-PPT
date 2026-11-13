<?php
namespace App\Models;

class FeaturedProject extends Model {
    protected static string $table = 'featured_projects';

    public static function allWithSkills(): array {
        $projects = self::all('display_order ASC, id ASC');
        foreach ($projects as &$p) {
            $p['skills_array'] = array_map('trim', explode(',', $p['skills']));
        }
        return $projects;
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

