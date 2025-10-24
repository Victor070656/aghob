<?php
require_once 'config/config.php';

// Log activity before destroying session
if (isLoggedIn()) {
    logActivity('logout', 'admins', $_SESSION['admin_id'], 'Admin logged out');
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
