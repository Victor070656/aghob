<?php
/**
 * Contact Form API Endpoint
 * Handles contact form submissions
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Get and validate form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validation
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }

    if (empty($message)) {
        $errors[] = 'Message is required';
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please fix the following errors: ' . implode(', ', $errors)
        ]);
        exit;
    }

    // Insert into database
    $db = getDB();
    $sql = "INSERT INTO contact_submissions (name, email, phone, subject, message, ip_address, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database error: ' . $db->error);
    }

    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt->bind_param('ssssss', $name, $email, $phone, $subject, $message, $ipAddress);

    if ($stmt->execute()) {
        // Optional: Send email notification to admin
        $settings = getSiteSettings();
        if ($settings && !empty($settings['site_email'])) {
            $to = $settings['site_email'];
            $emailSubject = "New Contact Message: " . $subject;
            $emailMessage = "Name: {$name}\n";
            $emailMessage .= "Email: {$email}\n";
            $emailMessage .= "Phone: {$phone}\n\n";
            $emailMessage .= "Message:\n{$message}";
            $headers = "From: {$email}\r\nReply-To: {$email}";

            @mail($to, $emailSubject, $emailMessage, $headers);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Thank you for contacting us! We will get back to you soon.'
        ]);
    } else {
        throw new Exception('Failed to save contact message');
    }

    $stmt->close();

} catch (Exception $e) {
    error_log('Contact form error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request. Please try again later.'
    ]);
}
