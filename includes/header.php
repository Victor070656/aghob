<?php
require_once __DIR__ . '/../config/config.php';
$settings = getSiteSettings();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? ($settings['site_name'] ?? 'Assemblies of God Church House of Bread') ?></title>
    <meta name="description" content="<?= $settings['site_tagline'] ?? 'Building Faith, Transforming Lives' ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Assemblies Of God House of Bread Custom CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/css/aghob-custom.css">
    <link rel="stylesheet" href="<?= SITE_URL ?>/css/navbar-fix.css">

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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Enhanced AG Branding for Navigation */
        .top-bar-ag {
            background: linear-gradient(135deg,
                var(--ag-primary-dark) 0%,
                var(--ag-primary) 50%,
                var(--ag-accent) 100%);
        }

        .nav-border-ag {
            background: linear-gradient(90deg,
                var(--ag-primary) 0%,
                var(--ag-accent) 50%,
                var(--ag-gold) 100%);
        }
    </style>
</head>

<body class="bg-white">
    <!-- Top Bar -->
    <div class="top-bar-ag text-white py-2.5 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center space-x-6">
                    <?php if (!empty($settings['site_email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($settings['site_email']) ?>"
                            class="hover:text-orange-200 transition flex items-center group">
                            <i class="fas fa-envelope mr-2 group-hover:scale-110 transition-transform"></i>
                            <span><?= htmlspecialchars($settings['site_email']) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['site_phone'])): ?>
                        <a href="tel:<?= htmlspecialchars($settings['site_phone']) ?>"
                            class="hover:text-orange-200 transition flex items-center group">
                            <i class="fas fa-phone mr-2 group-hover:scale-110 transition-transform"></i>
                            <span><?= htmlspecialchars($settings['site_phone']) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs opacity-75 mr-2">Follow Us:</span>
                    <?php if (!empty($settings['facebook_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['facebook_url']) ?>" target="_blank"
                            class="hover:text-orange-200 transition hover:scale-125 transform">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['instagram_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['instagram_url']) ?>" target="_blank"
                            class="hover:text-orange-200 transition hover:scale-125 transform">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['youtube_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['youtube_url']) ?>" target="_blank"
                            class="hover:text-orange-200 transition hover:scale-125 transform">
                            <i class="fab fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['twitter_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['twitter_url']) ?>" target="_blank"
                            class="hover:text-orange-200 transition hover:scale-125 transform">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white shadow-xl sticky top-0 z-50 border-b-4" style="border-image: linear-gradient(90deg, #1e40af 0%, #ea580c 50%, #d97706 100%) 1;">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="<?= SITE_URL ?>/index.php" class="flex items-center space-x-3 group flex-shrink-0">
                    <?php if (!empty($settings['logo'])): ?>
                        <div
                            class="w-14 h-14 ag-logo-placeholder text-white rounded-xl flex items-center justify-center group-hover:shadow-lg transition-all flex-shrink-0">
                            <i class="fas fa-dove text-2xl"></i>
                        </div>
                    <?php else: ?>
                        <img src="<?= SITE_URL . '/' . $settings['logo'] ?>"
                            alt="<?= htmlspecialchars($settings['site_name']) ?>"
                            class="h-10 group-hover:scale-110 transition-transform">
                    <?php endif; ?>
                    <div class="hidden md:block">
                        <h1 class="text-lg font-bold nav-ag-brand leading-tight">
                            Assemblies Of God Church
                        </h1>
                        <p class="text-xs text-gray-600 font-semibold">House of Bread</p>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="<?= SITE_URL ?>/index.php"
                        class="<?= $currentPage === 'index' ? 'nav-ag-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="<?= SITE_URL ?>/pages/about.php"
                        class="<?= $currentPage === 'about' ? 'nav-ag-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-info-circle mr-1"></i> About
                    </a>
                    <a href="<?= SITE_URL ?>/pages/pastors.php"
                        class="<?= $currentPage === 'pastors' ? 'nav-ag-active' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-users mr-1"></i> Leadership
                    </a>
                    <a href="<?= SITE_URL ?>/pages/events.php"
                        class="<?= $currentPage === 'events' ? 'nav-ag-active' : 'text-gray-700 hover:bg-green-50 hover:text-green-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-calendar-alt mr-1"></i> Events
                    </a>
                    <a href="<?= SITE_URL ?>/pages/sermons.php"
                        class="<?= $currentPage === 'sermons' ? 'nav-ag-active' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-microphone mr-1"></i> Sermons
                    </a>
                    <a href="<?= SITE_URL ?>/pages/ministries.php"
                        class="<?= $currentPage === 'ministries' ? 'nav-ag-active' : 'text-gray-700 hover:bg-yellow-50 hover:text-yellow-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-hands-helping mr-1"></i> Ministries
                    </a>
                    <a href="<?= SITE_URL ?>/pages/contact.php"
                        class="<?= $currentPage === 'contact' ? 'nav-ag-active' : 'text-gray-700 hover:bg-red-50 hover:text-red-700' ?> px-3 py-2 rounded-lg font-medium transition-all text-sm">
                        <i class="fas fa-envelope mr-1"></i> Contact
                    </a>
                </div>

                <!-- CTA Button -->
                <div class="hidden lg:block flex-shrink-0">
                    <?php if (!empty($settings['live_stream_url'])): ?>
                        <a href="<?= htmlspecialchars($settings['live_stream_url']) ?>" target="_blank"
                            class="inline-flex items-center px-6 py-2.5 btn-ag-primary text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all">
                            <span class="ag-live-indicator mr-2">LIVE</span>
                            <span>Watch Live</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/pages/contact.php"
                            class="px-6 py-2.5 btn-ag-gold text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all">
                            <i class="fas fa-praying-hands mr-2"></i>
                            Get In Touch
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 hover:text-orange-700 p-2">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-4 space-y-2">
                <a href="<?= SITE_URL ?>/index.php"
                    class="block py-3 px-4 <?= $currentPage === 'index' ? 'nav-ag-active' : 'text-gray-700 hover:bg-blue-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="<?= SITE_URL ?>/pages/about.php"
                    class="block py-3 px-4 <?= $currentPage === 'about' ? 'nav-ag-active' : 'text-gray-700 hover:bg-blue-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-info-circle mr-2"></i>About
                </a>
                <a href="<?= SITE_URL ?>/pages/pastors.php"
                    class="block py-3 px-4 <?= $currentPage === 'pastors' ? 'nav-ag-active' : 'text-gray-700 hover:bg-orange-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-users mr-2"></i>Leadership
                </a>
                <a href="<?= SITE_URL ?>/pages/events.php"
                    class="block py-3 px-4 <?= $currentPage === 'events' ? 'nav-ag-active' : 'text-gray-700 hover:bg-green-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-calendar-alt mr-2"></i>Events
                </a>
                <a href="<?= SITE_URL ?>/pages/sermons.php"
                    class="block py-3 px-4 <?= $currentPage === 'sermons' ? 'nav-ag-active' : 'text-gray-700 hover:bg-purple-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-microphone mr-2"></i>Sermons
                </a>
                <a href="<?= SITE_URL ?>/pages/ministries.php"
                    class="block py-3 px-4 <?= $currentPage === 'ministries' ? 'nav-ag-active' : 'text-gray-700 hover:bg-yellow-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-hands-helping mr-2"></i>Ministries
                </a>
                <a href="<?= SITE_URL ?>/pages/contact.php"
                    class="block py-3 px-4 <?= $currentPage === 'contact' ? 'nav-ag-active' : 'text-gray-700 hover:bg-red-50' ?> rounded-lg font-medium transition">
                    <i class="fas fa-envelope mr-2"></i>Contact
                </a>
            </div>
        </div>
    </nav>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>