<?php
namespace App\Models;

class OtherProject extends Model {
    protected static string $table = 'other_projects';

    public static function allWithSkills(): array {
        $items = self::all('display_order ASC, id ASC');
        foreach ($items as &$item) {
            $item['skills_array'] = array_map('trim', explode(',', $item['skills'] ?? ''));
        }
        return $items;
    }

    public static function saveItem(array $data): bool {
        $db = self::db();
        $image = !empty($data['image']) ? $data['image'] : 'images/projectcard3.webp';
        $githubUrl = !empty($data['github_url']) ? $data['github_url'] : 'https://github.com/gangasagar12';
        $liveUrl = !empty($data['live_url']) ? $data['live_url'] : $githubUrl;

        $detailedDesc = $data['detailed_description'] ?? '';

        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET title=?, category=?, description=?, detailed_description=?, image=?, skills=?, github_url=?, live_url=?, display_order=? WHERE id=?");
            return $stmt->execute([
                $data['title'],
                $data['category'] ?? 'Engineering Utility',
                $data['description'],
                $detailedDesc,
                $image,
                $data['skills'],
                $githubUrl,
                $liveUrl,
                $data['display_order'] ?? 0,
                $data['id']
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (title, category, description, detailed_description, image, skills, github_url, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $data['category'] ?? 'Engineering Utility',
                $data['description'],
                $detailedDesc,
                $image,
                $data['skills'],
                $githubUrl,
                $liveUrl,
                $data['display_order'] ?? 0
            ]);
        }
    }
}
