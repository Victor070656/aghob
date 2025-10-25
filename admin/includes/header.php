<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';
requireLogin();

$settings = getSiteSettings();
$currentAdmin = getCurrentAdmin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - AGC Northern Rivers District</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap"
        rel="stylesheet">
        <link rel="shortcut icon" href="<?= SITE_URL . "/" . $settings['logo'] ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= SITE_URL ?>/css/aghob-custom.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden relative">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Mobile Menu Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 lg:py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Toggle -->
                        <button id="open-sidebar" class="lg:hidden text-gray-600 hover:text-gray-900 transition">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-900"><?= $pageTitle ?? 'Dashboard' ?>
                            </h1>
                            <?php if (isset($pageDescription)): ?>
                                <p class="text-xs lg:text-sm text-gray-600 mt-1"><?= $pageDescription ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <!-- View Website -->
                        <a href="<?= SITE_URL ?>" target="_blank"
                            class="hidden sm:flex text-gray-600 hover:text-blue-600 transition text-sm lg:text-base">
                            <i class="fas fa-external-link-alt mr-1"></i> <span class="hidden lg:inline">View
                                Website</span>
                        </a>

                        <!-- User Dropdown -->
                        <div class="flex items-center space-x-2 lg:space-x-3 border-l pl-2 lg:pl-4">

                            <div
                                class="w-8 lg:w-10 h-8 lg:h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm lg:text-base">
                                <?= strtoupper(substr($currentAdmin['full_name'], 0, 1)) ?>
                            </div>

                            <div class="hidden sm:block">
                                <p class="text-xs lg:text-sm font-semibold text-gray-900">
                                    <?= htmlspecialchars($currentAdmin['full_name']) ?></p>
                            </div>

                            <a href="<?= SITE_URL ?>/logout.php" class="text-red-600 hover:text-red-700 transition"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-sm lg:text-base"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-3 lg:p-6">