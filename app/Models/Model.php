<?php
namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model {
    protected static string $table = '';

    protected static function db(): PDO {
        return Database::getConnection();
    }

    public static function all(string $orderBy = 'display_order ASC, id ASC'): array {
        $stmt = self::db()->query("SELECT * FROM " . static::$table . " ORDER BY " . $orderBy);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function delete(int $id): bool {
        $stmt = self::db()->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

