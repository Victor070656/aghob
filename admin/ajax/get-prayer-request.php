<?php
require_once '../../config/config.php';
requireLogin();

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID required']);
    exit;
}

$request = fetchOne("SELECT * FROM prayer_requests WHERE id = ?", [$id], 'i');

if (!$request) {
    echo json_encode(['success' => false, 'message' => 'Prayer request not found']);
    exit;
}

// Format dates
$request['created_at'] = formatDate($request['created_at'], 'd M Y g:i A');

echo json_encode([
    'success' => true,
    'request' => $request
]);
