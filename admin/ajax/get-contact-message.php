<?php
require_once '../../config/config.php';
requireLogin();

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID required']);
    exit;
}

$message = fetchOne("SELECT * FROM contact_submissions WHERE id = ?", [$id], 'i');

if (!$message) {
    echo json_encode(['success' => false, 'message' => 'Contact message not found']);
    exit;
}

// Auto-mark as read if it's new
if ($message['status'] === 'new') {
    $db = getDB();
    $sql = "UPDATE contact_submissions SET status = 'read' WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $message['status'] = 'read';
    logActivity('update', 'contact_submissions', $id, "Marked contact message as read");
}

// Format dates
$message['created_at'] = formatDate($message['created_at'], 'd M Y g:i A');

echo json_encode([
    'success' => true,
    'message' => $message
]);
