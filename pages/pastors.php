<?php
$pageTitle = 'Our Pastors - Assemblies of God Church House of Bread';
require_once __DIR__ . '/../includes/header.php';

// Get all pastors ordered by position and display_order
$pastorsQuery = "SELECT * FROM pastors WHERE is_active = 1 ORDER BY display_order ASC";
$pastors = fetchAll($pastorsQuery);
?>

<style>
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
</style>

<!-- Hero Section -->
<section class="relative text-white py-24 overflow-hidden">
    <!-- Background with AG Gradient -->
    <div class="absolute inset-0 hero-ag-gradient"></div>
    <div class="absolute inset-0 opacity-30">
        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Church Leadership" class="w-full h-full object-cover">
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="ag-ministry-icon w-20 h-20 mx-auto mb-6">
                <i class="fas fa-users text-3xl text-white"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Our Pastoral Leadership</h1>
            <p class="text-xl md:text-2xl text-gray-100 max-w-3xl mx-auto leading-relaxed">
                Spirit-appointed leaders serving with wisdom, integrity, and compassion to guide our AG House of Bread family
            </p>
        </div>
    </div>

    <!-- Decorative Elements -->
    <!-- <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
        </svg>
    </div> -->
</section>

<!-- Breadcrumb -->
<div class="bg-gray-50 py-4 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="<?= SITE_URL ?>/index.php" class="text-blue-700 hover:text-blue-800 transition">
                <i class="fas fa-home"></i> Home
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-blue-700 font-medium">Leadership</span>
        </nav>
    </div>
</div>

<!-- Main Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if (!empty($pastors)): ?>

            <!-- Leadership Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $positionColors = [
                    'district_superintendent' => ['bg' => 'from-blue-600 to-blue-700', 'text' => 'District Superintendent', 'badge' => 'bg-blue-100 text-blue-800'],
                    'assistant_ds' => ['bg' => 'from-orange-500 to-orange-600', 'text' => 'Assistant DS', 'badge' => 'bg-orange-100 text-orange-800'],
                    'secretary' => ['bg' => 'from-green-500 to-green-700', 'text' => 'Secretary', 'badge' => 'bg-green-100 text-green-800'],
                    'treasurer' => ['bg' => 'from-yellow-500 to-yellow-700', 'text' => 'Treasurer', 'badge' => 'bg-yellow-100 text-yellow-800'],
                    'member' => ['bg' => 'from-blue-500 to-blue-600', 'text' => 'Presbyter', 'badge' => 'bg-blue-100 text-blue-800']
                ];

                foreach ($pastors as $pastor):
                    $posConfig = $positionColors[$pastor['position']] ?? $positionColors['member'];
                    ?>

                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden card-hover border border-gray-100">
                        <!-- Profile Image -->
                        <div class="relative h-64 bg-gradient-to-br <?= $posConfig['bg'] ?> overflow-hidden">
                            <?php if ($pastor['photo']): ?>
                                <img src="<?= SITE_URL . '/' . $pastor['photo'] ?>"
                                    alt="<?= htmlspecialchars($pastor['full_name']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-6xl opacity-50"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Position Badge -->
                            <!-- <div
                                class="absolute top-4 left-4 px-4 py-2 <?= $posConfig['badge'] ?> rounded-full font-semibold text-sm shadow-lg">
                                <i class="fas fa-shield-alt mr-1"></i>
                                <?= $posConfig['text'] ?>
                            </div> -->
                        </div>

                        <!-- Profile Info -->
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                <?= htmlspecialchars($pastor['full_name']) ?>
                            </h3>

                            <?php if ($pastor['credentials']): ?>
                                <p class="text-sm text-blue-700 font-medium mb-3">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    <?= htmlspecialchars($pastor['credentials']) ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($pastor['ordination_date']): ?>
                                <p class="text-sm text-gray-600 mb-4">
                                    <i class="fas fa-calendar-check mr-2 text-green-600"></i>
                                    Ordained: <?= formatDate($pastor['ordination_date'], 'M Y') ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($pastor['bio']): ?>
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-700 leading-relaxed text-sm line-clamp-3">
                                        <?= htmlspecialchars($pastor['bio']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Contact Buttons -->
                            <div class="flex gap-3 pt-4 border-t">
                                <?php if ($pastor['email']): ?>
                                    <a href="mailto:<?= htmlspecialchars($pastor['email']) ?>"
                                        class="flex-1 flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-md hover:shadow-lg">
                                        <i class="fas fa-envelope mr-2"></i>
                                        <span class="hidden sm:inline">Email</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($pastor['phone']): ?>
                                    <a href="tel:<?= htmlspecialchars($pastor['phone']) ?>"
                                        class="flex-1 flex items-center justify-center px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition shadow-md hover:shadow-lg">
                                        <i class="fas fa-phone mr-2"></i>
                                        <span class="hidden sm:inline">Call</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        <?php else: ?>

            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-block p-8 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-users-cog text-6xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Pastors Listed</h3>
                <p class="text-gray-600 max-w-md mx-auto">
                    Information about our district leadership will be available soon.
                </p>
            </div>

        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-br from-amber-50 to-yellow-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-3xl shadow-xl p-12">
            <div class="inline-block p-4 bg-amber-100 rounded-full mb-6">
                <i class="fas fa-hands-praying text-amber-700 text-4xl"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Connect With Our Leadership
            </h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Have questions or need spiritual guidance? Our pastors are here to serve and support you in your
                faith journey.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="<?= SITE_URL ?>/pages/contact.php"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-600 to-yellow-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all">
                    <i class="fas fa-envelope mr-2"></i>
                    Get In Touch
                </a>
                <a href="<?= SITE_URL ?>/pages/about.php"
                    class="inline-flex items-center px-8 py-4 bg-white border-2 border-amber-600 text-amber-700 rounded-full font-semibold hover:bg-amber-50 transition-all">
                    <i class="fas fa-info-circle mr-2"></i>
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>