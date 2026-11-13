<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/init_db.php';

/**
 * Fetch all featured projects ordered by display_order
 */
function get_featured_projects() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM featured_projects ORDER BY display_order ASC, id ASC");
    $projects = $stmt->fetchAll();
    
    foreach ($projects as &$p) {
        $p['skills_array'] = array_map('trim', explode(',', $p['skills']));
    }
    return $projects;
}

/**
 * Fetch all academic projects ordered by display_order
 */
function get_academic_projects() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM academic_projects ORDER BY display_order ASC, id ASC");
    $projects = $stmt->fetchAll();
    
    foreach ($projects as &$p) {
        $p['skills_array'] = array_map('trim', explode(',', $p['skills']));
    }
    return $projects;
}

/**
 * Fetch published blog & insights posts
 */
function get_blog_insights($limit = null) {
    global $pdo;
    $sql = "SELECT * FROM blog_insights WHERE is_published = 1 ORDER BY display_order ASC, id DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Save Contact Message
 */
function save_contact_message($name, $email, $subject, $phone, $message) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO contact_inquiries (name, email, subject, phone, message) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        trim($name),
        trim($email),
        trim($subject),
        trim($phone),
        trim($message)
    ]);
}

