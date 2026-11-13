<?php
namespace App\Models;

class ContactInquiry extends Model {
    protected static string $table = 'contact_inquiries';

    public static function create(string $name, string $email, string $subject, ?string $phone, string $message): bool {
        $stmt = self::db()->prepare("INSERT INTO " . self::$table . " (name, email, subject, phone, message) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            trim($name),
            trim($email),
            trim($subject),
            trim($phone ?? ''),
            trim($message)
        ]);
    }
}

