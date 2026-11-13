<?php
namespace App\Models;

class FeaturedProject extends Model {
    protected static string $table = 'featured_projects';

    public static function slugify(string $text): string {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $text);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('~-+~', '-', $slug);
        $slug = strtolower($slug);
        return empty($slug) ? 'project-' . time() : $slug;
    }

    public static function allWithSkills(): array {
        $projects = self::all('display_order ASC, id ASC');
        foreach ($projects as &$p) {
            $p['skills_array'] = !empty($p['skills']) ? array_map('trim', explode(',', $p['skills'])) : [];
            $p['features_array'] = self::parseFeatures($p['features'] ?? null);
            $p['screenshots_array'] = self::parseScreenshots($p['screenshots'] ?? null);
            $p['display_desc'] = !empty($p['short_description']) ? $p['short_description'] : $p['description'];
            if (empty($p['slug'])) {
                $p['slug'] = self::slugify($p['title']);
            }
        }
        return $projects;
    }

    public static function findBySlug(string $slug): ?array {
        $cleanSlug = strtolower(trim(preg_replace('/\.html$/', '', $slug), '/'));
        
        $stmt = self::db()->prepare("SELECT * FROM " . self::$table . " WHERE LOWER(slug) = ? LIMIT 1");
        $stmt->execute([$cleanSlug]);
        $row = $stmt->fetch();

        // Fallback: match auto-generated slug of title
        if (!$row) {
            $all = self::all();
            foreach ($all as $item) {
                $computedSlug = !empty($item['slug']) ? $item['slug'] : self::slugify($item['title']);
                if (strtolower($computedSlug) === $cleanSlug) {
                    $row = $item;
                    break;
                }
            }
        }

        if ($row) {
            $row['skills_array'] = !empty($row['skills']) ? array_map('trim', explode(',', $row['skills'])) : [];
            $row['features_array'] = self::parseFeatures($row['features'] ?? null);
            $row['screenshots_array'] = self::parseScreenshots($row['screenshots'] ?? null);
            if (empty($row['slug'])) {
                $row['slug'] = self::slugify($row['title']);
            }
            return $row;
        }
        return null;
    }

    public static function findWithDetails(int $id): ?array {
        $row = self::find($id);
        if ($row) {
            $row['skills_array'] = !empty($row['skills']) ? array_map('trim', explode(',', $row['skills'])) : [];
            $row['features_array'] = self::parseFeatures($row['features'] ?? null);
            $row['screenshots_array'] = self::parseScreenshots($row['screenshots'] ?? null);
            if (empty($row['slug'])) {
                $row['slug'] = self::slugify($row['title']);
            }
            return $row;
        }
        return null;
    }

    public static function parseFeatures(?string $raw): array {
        if (empty($raw)) return [];
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }
        $lines = explode("\n", str_replace("\r", "", $raw));
        $res = [];
        foreach ($lines as $line) {
            $cleaned = trim(ltrim($line, "•*- \t"));
            if ($cleaned !== '') {
                $res[] = $cleaned;
            }
        }
        return $res;
    }

    public static function parseScreenshots(?string $raw): array {
        if (empty($raw)) return [];
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $list = [];
            foreach ($decoded as $item) {
                if (is_string($item)) {
                    $list[] = ['src' => trim($item), 'title' => 'System Preview'];
                } elseif (is_array($item) && !empty($item['src'])) {
                    $list[] = [
                        'src' => trim($item['src']),
                        'title' => trim($item['title'] ?? 'System Preview')
                    ];
                }
            }
            return $list;
        }

        // Line-based fallback: "path/to/image.jpg | Screenshot Title"
        $lines = explode("\n", str_replace("\r", "", $raw));
        $list = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed)) continue;
            if (strpos($trimmed, '|') !== false) {
                list($src, $title) = explode('|', $trimmed, 2);
                $list[] = ['src' => trim($src), 'title' => trim($title)];
            } else {
                $list[] = ['src' => $trimmed, 'title' => 'System Preview'];
            }
        }
        return $list;
    }

    public static function saveItem(array $data): bool {
        $db = self::db();
        
        $title = trim($data['title'] ?? '');
        $slug = trim($data['slug'] ?? '');
        if (empty($slug)) {
            $slug = self::slugify($title);
        } else {
            $slug = self::slugify($slug);
        }

        $category_badge = trim($data['category_badge'] ?? '');
        if (empty($category_badge)) {
            $category_badge = 'FEATURED PROJECT';
        }

        $category = trim($data['category'] ?? '');
        $official_site = trim($data['official_site'] ?? '');
        $client_name = trim($data['client_name'] ?? '');
        $location = trim($data['location'] ?? '');
        $short_description = trim($data['short_description'] ?? '');
        $description = trim($data['description'] ?? '');
        $impact = trim($data['impact'] ?? '');
        $image = trim($data['image'] ?? 'images/projectcard1.jpg');
        $skills = trim($data['skills'] ?? '');
        $live_url = trim($data['live_url'] ?? 'https://github.com/gangasaagar');
        $display_order = intval($data['display_order'] ?? 0);

        // Normalize features into JSON
        $features = $data['features'] ?? '';
        if (is_array($features)) {
            $featuresJson = json_encode(array_values(array_filter($features)), JSON_UNESCAPED_UNICODE);
        } else {
            $parsedF = self::parseFeatures((string)$features);
            $featuresJson = json_encode($parsedF, JSON_UNESCAPED_UNICODE);
        }

        // Normalize screenshots into JSON
        $screenshots = $data['screenshots'] ?? '';
        if (is_array($screenshots)) {
            $screenshotsJson = json_encode($screenshots, JSON_UNESCAPED_UNICODE);
        } else {
            $parsedS = self::parseScreenshots((string)$screenshots);
            $screenshotsJson = json_encode($parsedS, JSON_UNESCAPED_UNICODE);
        }

        if (!empty($data['id'])) {
            $stmt = $db->prepare("UPDATE " . self::$table . " SET 
                title=?, slug=?, category_badge=?, category=?, official_site=?,
                client_name=?, location=?, short_description=?, description=?,
                impact=?, features=?, screenshots=?, image=?, skills=?,
                live_url=?, display_order=?
                WHERE id=?");
            return $stmt->execute([
                $title, $slug, $category_badge, $category, $official_site,
                $client_name, $location, $short_description, $description,
                $impact, $featuresJson, $screenshotsJson, $image, $skills,
                $live_url, $display_order, intval($data['id'])
            ]);
        } else {
            $stmt = $db->prepare("INSERT INTO " . self::$table . " (
                title, slug, category_badge, category, official_site,
                client_name, location, short_description, description,
                impact, features, screenshots, image, skills,
                live_url, display_order
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $title, $slug, $category_badge, $category, $official_site,
                $client_name, $location, $short_description, $description,
                $impact, $featuresJson, $screenshotsJson, $image, $skills,
                $live_url, $display_order
            ]);
        }
    }
}
