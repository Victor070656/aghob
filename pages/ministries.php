<?php
$pageTitle = 'Ministries';
require_once __DIR__ . '/../includes/header.php';

// Get all ministries
$ministriesQuery = "SELECT * FROM ministries ORDER BY name ASC";
$ministries = fetchAll($ministriesQuery);
?>

<!-- Hero Section with Image -->
<section class="relative bg-gradient-to-r from-amber-700 via-yellow-700 to-amber-800 text-white py-24 overflow-hidden" style="min-height: 60vh;">
    <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('../images/04.jpg');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-amber-900/65 to-yellow-900/50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fadeInUp">
            <i class="fas fa-hands-helping text-6xl mb-6 opacity-90 drop-shadow-lg"></i>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg">Our Ministries</h1>
            <p class="text-xl md:text-2xl text-white max-w-3xl mx-auto drop-shadow-md">
                Discover opportunities to serve, grow, and connect with our community
            </p>
        </div>
    </div>
</section>

<!-- Breadcrumb Navigation -->
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="<?= SITE_URL ?>" class="hover:text-amber-700 transition">Home</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-amber-700 font-semibold">Ministries</span>
        </div>
    </div>
</section>

<!-- Ministries Grid -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($ministries)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($ministries as $ministry): ?>
            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <!-- Image/Icon -->
                <div class="relative overflow-hidden">
                    <?php if ($ministry['image']): ?>
                    <img src="<?= SITE_URL . '/' . htmlspecialchars($ministry['image']) ?>"
                         alt="<?= htmlspecialchars($ministry['name']) ?>"
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                    <?php else: ?>
                    <div class="w-full h-64 bg-gradient-to-br from-amber-600 to-yellow-700 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                        <i class="fas fa-hands-helping text-8xl text-white opacity-50 relative z-10"></i>
                    </div>
                    <?php endif; ?>

                    <!-- Decorative overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <div class="p-6">
                    <!-- Ministry Name -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        <?= htmlspecialchars($ministry['name']) ?>
                    </h3>

                    <!-- Leader Info -->
                    <?php if ($ministry['leader_name']): ?>
                    <div class="flex items-center mb-4 bg-amber-50 rounded-xl p-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-600 to-yellow-700 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="fas fa-user-tie text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-amber-800 mb-1">Ministry Leader</span>
                            <span class="font-bold text-gray-900"><?= htmlspecialchars($ministry['leader_name']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Meeting Schedule -->
                    <?php if ($ministry['meeting_day'] || $ministry['meeting_time']): ?>
                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-calendar-check text-amber-700 text-xl mt-0.5 mr-3"></i>
                            <div>
                                <span class="block text-xs font-bold text-amber-800 mb-2">Meeting Schedule</span>
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    <?php if ($ministry['meeting_day']): ?>
                                        <div><strong>Day:</strong> <?= htmlspecialchars($ministry['meeting_day']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($ministry['meeting_time']): ?>
                                        <div><strong>Time:</strong> <?= htmlspecialchars($ministry['meeting_time']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($ministry['meeting_location']): ?>
                                        <div><strong>Location:</strong> <?= htmlspecialchars($ministry['meeting_location']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Description -->
                    <?php if ($ministry['description']): ?>
                    <div class="mb-4">
                        <h4 class="font-bold text-gray-900 mb-2 text-sm flex items-center">
                            <i class="fas fa-info-circle text-amber-700 mr-2"></i>
                            About This Ministry
                        </h4>
                        <p class="text-gray-700 text-sm leading-relaxed">
                            <?= nl2br(htmlspecialchars($ministry['description'])) ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Activities -->
                    <?php if ($ministry['activities']): ?>
                    <div class="mb-4">
                        <h4 class="font-bold text-gray-900 mb-2 text-sm flex items-center">
                            <i class="fas fa-list-check text-amber-700 mr-2"></i>
                            Our Activities
                        </h4>
                        <div class="text-gray-700 text-sm leading-relaxed bg-gray-50 rounded-lg p-3">
                            <?= nl2br(htmlspecialchars($ministry['activities'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Contact Leader Button -->
                    <?php if ($ministry['leader_email'] || $ministry['leader_phone']): ?>
                    <div class="pt-4 border-t border-gray-100 space-y-2">
                        <?php if ($ministry['leader_email']): ?>
                        <a href="mailto:<?= htmlspecialchars($ministry['leader_email']) ?>"
                           class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-green-600 to-teal-600 text-white px-4 py-3 rounded-xl font-bold hover:from-green-700 hover:to-teal-700 transition shadow-md hover:shadow-lg">
                            <i class="fas fa-envelope"></i>
                            <span>Contact Leader</span>
                        </a>
                        <?php endif; ?>

                        <?php if ($ministry['leader_phone']): ?>
                        <a href="tel:<?= htmlspecialchars($ministry['leader_phone']) ?>"
                           class="flex items-center justify-center gap-2 w-full bg-white border-2 border-green-600 text-amber-700 px-4 py-3 rounded-xl font-bold hover:bg-amber-50 transition">
                            <i class="fas fa-phone"></i>
                            <span><?= htmlspecialchars($ministry['leader_phone']) ?></span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-24">
            <div class="bg-white rounded-3xl shadow-xl p-16 max-w-2xl mx-auto">
                <i class="fas fa-hands-helping text-8xl text-gray-300 mb-6"></i>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">No Ministries Listed</h3>
                <p class="text-lg text-gray-600">Check back soon for ministry opportunities</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-24 bg-gradient-to-r from-green-600 to-teal-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-12 shadow-2xl">
            <i class="fas fa-heart text-6xl mb-6 opacity-90"></i>
            <h2 class="text-4xl font-bold mb-6">Ready to Get Involved?</h2>
            <p class="text-xl text-white mb-8 leading-relaxed">
                We believe everyone has gifts to share and a place to serve. Join one of our ministries and make a difference in our community.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= SITE_URL ?>/pages/contact.php"
                   class="inline-block bg-white text-amber-700 px-10 py-4 rounded-xl font-bold hover:bg-amber-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-paper-plane mr-2"></i> Contact Us
                </a>
                <a href="<?= SITE_URL ?>/pages/events.php"
                   class="inline-block bg-green-700 text-white border-2 border-white px-10 py-4 rounded-xl font-bold hover:bg-green-600 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-calendar mr-2"></i> View Events
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
