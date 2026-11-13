<?php
header('Content-Type: application/json');
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$phone = $_POST['phone'] ?? '';
$message = $_POST['message'] ?? '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$saved = save_contact_message($name, $email, $subject, $phone, $message);

if ($saved) {
    echo json_encode(['success' => true, 'message' => 'Thank you, Ganga Sagar will get back to you shortly!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to record your message. Please try again.']);
}

