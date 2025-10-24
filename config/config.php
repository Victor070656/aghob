<?php
/**
 * Main Configuration File
 * Assemblies of God Church House of Bread
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database configuration
require_once __DIR__ . '/database.php';

// Site configuration
define('SITE_URL', 'http://localhost/aghob');
define('SITE_NAME', 'Assemblies of God Church House of Bread');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('SESSION_TIMEOUT', 3600); // 1 hour

// Date and time settings
date_default_timezone_set('Africa/Lagos');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error.log');

/**
 * Sanitize input data
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Generate slug from string
 * @param string $string Input string
 * @return string URL-friendly slug
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Format file size
 * @param int $bytes File size in bytes
 * @return string Formatted file size
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Format date for display
 * @param string $date Date string
 * @param string $format Desired format
 * @return string Formatted date
 */
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Upload file helper
 * @param array $file $_FILES array element
 * @param string $directory Upload subdirectory
 * @param array $allowedTypes Allowed MIME types
 * @return array Result with 'success', 'path', and 'message'
 */
function uploadFile($file, $directory, $allowedTypes = []) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or upload error'];
    }

    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File size exceeds maximum allowed'];
    }

    // Check file type if specified
    if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }

    // Create upload directory if it doesn't exist
    $uploadPath = UPLOAD_DIR . $directory;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $targetPath = $uploadPath . '/' . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $relativePath = 'uploads/' . $directory . '/' . $filename;
        return [
            'success' => true,
            'path' => $relativePath,
            'filename' => $filename,
            'size' => $file['size']
        ];
    }

    return ['success' => false, 'message' => 'Failed to move uploaded file'];
}

/**
 * Delete file
 * @param string $filePath Relative file path
 * @return bool
 */
function deleteFile($filePath) {
    if (empty($filePath)) {
        return false;
    }

    $fullPath = __DIR__ . '/../' . $filePath;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }

    return false;
}

// =====================================================
// AUTHENTICATION FUNCTIONS
// =====================================================

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Get current logged in admin
 * @return array|null
 */
function getCurrentAdmin() {
    if (!isLoggedIn()) {
        return null;
    }

    $sql = "SELECT * FROM admins WHERE id = ?";
    return fetchOne($sql, [$_SESSION['admin_id']], 'i');
}

/**
 * Require login - redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}

/**
 * Check if admin has specific role
 * @param string $role Role to check
 * @return bool
 */
function hasRole($role) {
    $admin = getCurrentAdmin();
    return $admin && $admin['role'] === $role;
}

/**
 * Check if admin is super admin
 * @return bool
 */
function isSuperAdmin() {
    return hasRole('super_admin');
}

/**
 * Require super admin - redirect if not
 */
function requireSuperAdmin() {
    requireLogin();
    if (!isSuperAdmin()) {
        header('Location: ' . SITE_URL . '/admin/index.php');
        exit;
    }
}

/**
 * Log activity
 * @param string $action Action performed
 * @param string $table Table affected
 * @param int $recordId Record ID
 * @param string $description Description
 */
function logActivity($action, $table = null, $recordId = null, $description = null) {
    if (!isLoggedIn()) {
        return;
    }

    $db = getDB();
    $sql = "INSERT INTO activity_logs (admin_id, action, table_name, record_id, description, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $adminId = $_SESSION['admin_id'];
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

    $stmt->bind_param('issssss', $adminId, $action, $table, $recordId, $description, $ipAddress, $userAgent);
    $stmt->execute();
}

/**
 * Get site settings
 * @return array|null
 */
function getSiteSettings() {
    $sql = "SELECT * FROM site_settings LIMIT 1";
    return fetchOne($sql);
}

/**
 * Get specific setting value
 * @param string $key Setting key (column name)
 * @return mixed
 */
function getSetting($key) {
    $settings = getSiteSettings();
    return $settings[$key] ?? null;
}
