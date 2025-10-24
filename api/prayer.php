<?php
/**
 * Prayer Request API Endpoint
 * Handles prayer request submissions
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
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $prayerRequest = isset($_POST['prayer_request']) ? trim($_POST['prayer_request']) : '';
    $isAnonymous = isset($_POST['is_anonymous']) ? (int)$_POST['is_anonymous'] : 0;
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

    // Validation
    $errors = [];

    if (empty($fullName) && !$isAnonymous) {
        $errors[] = 'Name is required';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($prayerRequest)) {
        $errors[] = 'Prayer request is required';
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
    $sql = "INSERT INTO prayer_requests (full_name, email, phone, prayer_request, is_anonymous, ip_address, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database error: ' . $db->error);
    }

    $stmt->bind_param('ssssis', $fullName, $email, $phone, $prayerRequest, $isAnonymous, $ipAddress);

    if ($stmt->execute()) {
        // Optional: Send email notification to admin
        $settings = getSiteSettings();
        if ($settings && !empty($settings['site_email'])) {
            $to = $settings['site_email'];
            $emailSubject = "New Prayer Request";
            $emailMessage = "Name: " . ($isAnonymous ? 'Anonymous' : $fullName) . "\n";
            $emailMessage .= "Email: " . ($email ?: 'Not provided') . "\n";
            $emailMessage .= "Phone: " . ($phone ?: 'Not provided') . "\n\n";
            $emailMessage .= "Prayer Request:\n{$prayerRequest}";
            $headers = !empty($email) ? "From: {$email}\r\nReply-To: {$email}" : "From: noreply@agcnrd.org";

            @mail($to, $emailSubject, $emailMessage, $headers);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your prayer request. Our team will be praying for you.'
        ]);
    } else {
        throw new Exception('Failed to save prayer request');
    }

    $stmt->close();

} catch (Exception $e) {
    error_log('Prayer request error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request. Please try again later.'
    ]);
}
