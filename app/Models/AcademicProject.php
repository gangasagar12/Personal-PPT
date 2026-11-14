<?php
namespace App\Models;

class AcademicProject extends Model {
    protected static string $table = 'academic_projects';

    public static function allWithSkills(): array {
        $items = self::all('display_order ASC, id ASC');
        foreach ($items as &$item) {
            $item['skills_array'] = array_map('trim', explode(',', $item['skills']));
        }
        return $items;
    }

    public static function saveItem(array $data): bool {
        $db = self::db();
        $image = !empty($data['image']) ? $data['image'] : 'images/projectcard1.jpg';
        $detailedDesc = $data['detailed_description'] ?? '';

        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET title=?, description=?, detailed_description=?, image=?, skills=?, display_order=? WHERE id=?");
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $detailedDesc,
                $image,
                $data['skills'],
                $data['display_order'] ?? 0,
                $data['id']
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (title, description, detailed_description, image, skills, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $detailedDesc,
                $image,
                $data['skills'],
                $data['display_order'] ?? 0
            ]);
        }
    }
}

