<?php
/**
 * Newsletter Subscription API Endpoint
 * Handles newsletter subscriptions
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
    // Get and validate email
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Validation
    if (empty($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email address is required'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please provide a valid email address'
        ]);
        exit;
    }

    // Check if email already exists
    $db = getDB();
    $checkSql = "SELECT id FROM newsletter_subscribers WHERE email = ?";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->bind_param('s', $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'This email is already subscribed to our newsletter'
        ]);
        exit;
    }
    $checkStmt->close();

    // Insert into database
    $sql = "INSERT INTO newsletter_subscribers (email, subscribed_at, status)
            VALUES (?, NOW(), 'active')";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database error: ' . $db->error);
    }

    $stmt->bind_param('s', $email);

    if ($stmt->execute()) {
        // Optional: Send welcome email
        $settings = getSiteSettings();
        $siteName = $settings['site_name'] ?? 'AGC Northern Rivers District';

        $to = $email;
        $subject = "Welcome to {$siteName} Newsletter";
        $message = "Thank you for subscribing to our newsletter!\n\n";
        $message .= "You'll receive updates about:\n";
        $message .= "- Upcoming events and services\n";
        $message .= "- Latest sermons and teachings\n";
        $message .= "- Community news and announcements\n\n";
        $message .= "Blessings,\n{$siteName} Team";

        if (!empty($settings['site_email'])) {
            $headers = "From: {$settings['site_email']}\r\nReply-To: {$settings['site_email']}";
            @mail($to, $subject, $message, $headers);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Thank you for subscribing! Check your email for confirmation.'
        ]);
    } else {
        throw new Exception('Failed to save subscription');
    }

    $stmt->close();

} catch (Exception $e) {
    error_log('Newsletter subscription error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your subscription. Please try again later.'
    ]);
}
