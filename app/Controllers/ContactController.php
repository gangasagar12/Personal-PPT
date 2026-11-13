<?php
namespace App\Controllers;

use App\Models\ContactInquiry;

class ContactController {
    public function submit(): void {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
            return;
        }

        $success = ContactInquiry::create($name, $email, $subject, $phone, $message);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save message. Please try again.']);
        }
    }
}

