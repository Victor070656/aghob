<?php
$pageTitle = 'District Presbyters - AGC Northern Rivers';
require_once __DIR__ . '/../includes/header.php';

// Get all presbyters ordered by position and display_order
$presbytersQuery = "SELECT * FROM presbyters WHERE is_active = 1 ORDER BY FIELD(position, 'district_superintendent', 'assistant_ds', 'secretary', 'treasurer', 'member'), display_order ASC";
$presbyters = fetchAll($presbytersQuery);
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
<section class="relative bg-gradient-to-br from-amber-900 via-yellow-900 to-amber-800 text-white py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-40">
        <img src="../images/04.jpg" alt="Leadership" class="w-full h-full object-cover ">
    </div>
    <!-- <div class="absolute inset-0 bg-gradient-to-r from-amber-900/90 to-yellow-900/90"></div> -->

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-block mb-6 px-6 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                <i class="fas fa-users-cog mr-2"></i>
                <span class="font-medium">District Leadership</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Our Presbyters</h1>
            <p class="text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto leading-relaxed">
                Dedicated spiritual leaders serving with wisdom, integrity, and compassion to guide our district
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
            <a href="<?= SITE_URL ?>/index.php" class="text-amber-700 hover:text-amber-700 transition">
                <i class="fas fa-home"></i> Home
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-700 font-medium">Presbyters</span>
        </nav>
    </div>
</div>

<!-- Main Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if (!empty($presbyters)): ?>

            <!-- Leadership Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $positionColors = [
                    'district_superintendent' => ['bg' => 'from-amber-600 to-amber-700', 'text' => 'District Superintendent', 'badge' => 'bg-amber-100 text-amber-800'],
                    'assistant_ds' => ['bg' => 'from-amber-600 to-yellow-700', 'text' => 'Assistant DS', 'badge' => 'bg-amber-100 text-amber-800'],
                    'secretary' => ['bg' => 'from-green-500 to-green-700', 'text' => 'Secretary', 'badge' => 'bg-green-100 text-green-800'],
                    'treasurer' => ['bg' => 'from-yellow-500 to-yellow-700', 'text' => 'Treasurer', 'badge' => 'bg-yellow-100 text-yellow-800'],
                    'member' => ['bg' => 'from-gray-500 to-gray-700', 'text' => 'Presbyter', 'badge' => 'bg-gray-100 text-gray-800']
                ];

                foreach ($presbyters as $presbyter):
                    $posConfig = $positionColors[$presbyter['position']] ?? $positionColors['member'];
                    ?>

                    <div class="group bg-white rounded-2xl shadow-lg overflow-hidden card-hover border border-gray-100">
                        <!-- Profile Image -->
                        <div class="relative h-64 bg-gradient-to-br <?= $posConfig['bg'] ?> overflow-hidden">
                            <?php if ($presbyter['photo']): ?>
                                <img src="<?= SITE_URL . '/' . $presbyter['photo'] ?>"
                                    alt="<?= htmlspecialchars($presbyter['full_name']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-6xl opacity-50"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Position Badge -->
                            <div
                                class="absolute top-4 left-4 px-4 py-2 <?= $posConfig['badge'] ?> rounded-full font-semibold text-sm shadow-lg">
                                <i class="fas fa-shield-alt mr-1"></i>
                                <?= $posConfig['text'] ?>
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                <?= htmlspecialchars($presbyter['full_name']) ?>
                            </h3>

                            <?php if ($presbyter['credentials']): ?>
                                <p class="text-sm text-amber-700 font-medium mb-3">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    <?= htmlspecialchars($presbyter['credentials']) ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($presbyter['ordination_date']): ?>
                                <p class="text-sm text-gray-600 mb-4">
                                    <i class="fas fa-calendar-check mr-2 text-green-600"></i>
                                    Ordained: <?= formatDate($presbyter['ordination_date'], 'M Y') ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($presbyter['bio']): ?>
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-700 leading-relaxed text-sm line-clamp-3">
                                        <?= htmlspecialchars($presbyter['bio']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Contact Buttons -->
                            <div class="flex gap-3 pt-4 border-t">
                                <?php if ($presbyter['email']): ?>
                                    <a href="mailto:<?= htmlspecialchars($presbyter['email']) ?>"
                                        class="flex-1 flex items-center justify-center px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium transition shadow-md hover:shadow-lg">
                                        <i class="fas fa-envelope mr-2"></i>
                                        <span class="hidden sm:inline">Email</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($presbyter['phone']): ?>
                                    <a href="tel:<?= htmlspecialchars($presbyter['phone']) ?>"
                                        class="flex-1 flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition shadow-md hover:shadow-lg">
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
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Presbyters Listed</h3>
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
                Have questions or need spiritual guidance? Our presbyters are here to serve and support you in your
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