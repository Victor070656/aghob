<?php
$pageTitle = 'About Us';
require_once __DIR__ . '/../includes/header.php';

// Get stats
$pastorsCount = fetchOne("SELECT COUNT(*) as count FROM pastors")['count'] ?? 0;
$eventsCount = fetchOne("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE()")['count'] ?? 0;
$ministriesCount = fetchOne("SELECT COUNT(*) as count FROM ministries")['count'] ?? 0;
$churchesCount = 15; // Placeholder - you can make this dynamic from a churches table if you have one
?>

<!-- Hero Section with Image -->
<section class="relative text-white py-24 overflow-hidden">
    <!-- Background with AG Gradient -->
    <div class="absolute inset-0 hero-ag-gradient"></div>
    <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('../images/01.jpg');">
    </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-fadeInUp">
            <div class="ag-ministry-icon w-24 h-24 mx-auto mb-6">
                <i class="fas fa-dove text-4xl text-white"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg">About AG House of Bread</h1>
            <p class="text-xl md:text-2xl text-white max-w-3xl mx-auto drop-shadow-md">
                Discover our Pentecostal heritage, Spirit-filled mission, and vision for our community
            </p>
        </div>
    </div>
</section>

<!-- Breadcrumb Navigation -->
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="<?= SITE_URL ?>" class="hover:text-blue-700 transition">Home</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-blue-700 font-semibold">About</span>
        </div>
    </div>
</section>

<!-- About Text Section -->
<section class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Who We Are</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-orange-600 mx-auto mb-8"></div>
            <div
                class="text-lg text-gray-700 leading-relaxed space-y-6 text-left bg-gradient-to-b from-blue-50 to-white p-8 rounded-2xl shadow-lg">
                <?php if ($settings['about_text']): ?>
                    <?= nl2br(htmlspecialchars($settings['about_text'])) ?>
                <?php else: ?>
                    <p>The Assemblies of God House of Bread is a vibrant Pentecostal community of Spirit-filled believers dedicated
                        to spreading the Living Bread of Jesus Christ. We are committed to building strong churches,
                        empowering Spirit-led leaders, and transforming lives through the power of the Holy Ghost.</p>
                    <p>As part of the Assemblies of God fellowship, we are united in our mission to advance God's kingdom through
                        biblical evangelism, Spirit-led discipleship, anointed worship, and compassionate service to our communities.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Purpose</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-orange-600 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Vision -->
            <div
                class="group bg-white rounded-2xl shadow-xl p-10 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center mb-6">
                    <div class="ag-ministry-icon w-20 h-20 mr-5">
                        <i class="fas fa-eye text-3xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Vision</h2>
                </div>
                <p class="text-gray-700 leading-relaxed text-lg">
                    <?php if ($settings['vision_statement']): ?>
                        <?= nl2br(htmlspecialchars($settings['vision_statement'])) ?>
                    <?php else: ?>
                        To be a dynamic and Spirit-filled district, reaching every community in the Northern Rivers with the
                        transforming power of the Gospel, raising up strong churches and godly leaders who will impact their
                        world for Christ.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Mission -->
            <div
                class="group bg-white rounded-2xl shadow-xl p-10 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center mb-6">
                    <div class="ag-ministry-icon w-20 h-20 mr-5">
                        <i class="fas fa-bullseye text-3xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Our Mission</h2>
                </div>
                <p class="text-gray-700 leading-relaxed text-lg">
                    <?php if ($settings['mission_statement']): ?>
                        <?= nl2br(htmlspecialchars($settings['mission_statement'])) ?>
                    <?php else: ?>
                        To support, equip, and mobilize our churches and leaders to effectively evangelize, disciple
                        believers, and serve our communities with excellence, while maintaining biblical integrity and the
                        empowerment of the Holy Spirit.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-24 bg-gradient-to-r from-blue-600 via-orange-600 to-yellow-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Our Impact</h2>
            <p class="text-xl text-white">Serving our community with dedication and excellence</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Churches -->
            <div class="text-center group">
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 shadow-xl">
                    <i class="fas fa-church text-6xl mb-4 opacity-90 group-hover:scale-110 transition-transform"></i>
                    <div class="text-6xl font-bold mb-2"><?= $churchesCount ?>+</div>
                    <div class="text-xl text-white">Churches</div>
                    <p class="mt-2 text-sm text-blue-200">Across the region</p>
                </div>
            </div>

            <!-- Presbyters -->
            <div class="text-center group">
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 shadow-xl">
                    <i class="fas fa-users text-6xl mb-4 opacity-90 group-hover:scale-110 transition-transform"></i>
                    <div class="text-6xl font-bold mb-2"><?= $pastorsCount ?></div>
                    <div class="text-xl text-white">Presbyters</div>
                    <p class="mt-2 text-sm text-blue-200">Dedicated spiritual leaders</p>
                </div>
            </div>

            <!-- Ministries -->
            <div class="text-center group">
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 shadow-xl">
                    <i
                        class="fas fa-hands-helping text-6xl mb-4 opacity-90 group-hover:scale-110 transition-transform"></i>
                    <div class="text-6xl font-bold mb-2"><?= $ministriesCount ?></div>
                    <div class="text-xl text-white">Active Ministries</div>
                    <p class="mt-2 text-sm text-blue-200">Ways to serve and grow</p>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="text-center group">
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 shadow-xl">
                    <i
                        class="fas fa-calendar-alt text-6xl mb-4 opacity-90 group-hover:scale-110 transition-transform"></i>
                    <div class="text-6xl font-bold mb-2"><?= $eventsCount ?></div>
                    <div class="text-xl text-white">Upcoming Events</div>
                    <p class="mt-2 text-sm text-blue-200">Opportunities to connect</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto mb-6"></div>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">The principles that guide everything we do</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Value 1 -->
            <div
                class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-bible text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Biblical Truth</h3>
                <p class="text-gray-700 leading-relaxed">Grounded in the Word of God as our foundation for faith and
                    practice</p>
            </div>

            <!-- Value 2 -->
            <div
                class="group bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-fire text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Spirit Empowerment</h3>
                <p class="text-gray-700 leading-relaxed">Led and empowered by the Holy Spirit in all we do</p>
            </div>

            <!-- Value 3 -->
            <div
                class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-heart text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Compassion</h3>
                <p class="text-gray-700 leading-relaxed">Serving others with love, grace, and genuine compassion</p>
            </div>

            <!-- Value 4 -->
            <div
                class="group bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl p-8 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-pink-600 to-pink-800 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-star text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Excellence</h3>
                <p class="text-gray-700 leading-relaxed">Pursuing excellence in all we do for God's glory</p>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Journey</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto mb-6"></div>
            <p class="text-xl text-gray-600">A brief history of the Northern Rivers District</p>
        </div>

        <div class="relative">
            <!-- Timeline line -->
            <div
                class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-gradient-to-b from-blue-600 to-indigo-600">
            </div>

            <!-- Timeline items -->
            <div class="space-y-12">
                <div class="relative flex items-center">
                    <div class="flex-1 text-right pr-8">
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Foundation</h3>
                            <p class="text-gray-600">Establishment of the Northern Rivers District to serve and support
                                churches in the region</p>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center shadow-lg z-10">
                        <i class="fas fa-flag text-white"></i>
                    </div>
                    <div class="flex-1 pl-8"></div>
                </div>

                <div class="relative flex items-center">
                    <div class="flex-1 pr-8"></div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center shadow-lg z-10">
                        <i class="fas fa-church text-white"></i>
                    </div>
                    <div class="flex-1 text-left pl-8">
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Growth & Expansion</h3>
                            <p class="text-gray-600">Expansion of ministry reach across the Northern Rivers region with
                                new churches and ministries</p>
                        </div>
                    </div>
                </div>

                <div class="relative flex items-center">
                    <div class="flex-1 text-right pr-8">
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Today</h3>
                            <p class="text-gray-600">Continuing to serve, equip, and empower churches and leaders for
                                kingdom impact</p>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-amber-600 to-yellow-700 rounded-full flex items-center justify-center shadow-lg z-10">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div class="flex-1 pl-8"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Preview -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Leadership</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto mb-6"></div>
            <p class="text-xl text-gray-600 mb-8">Dedicated leaders serving the Northern Rivers District</p>
            <a href="<?= SITE_URL ?>/pages/presbyters.php"
                class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-xl font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-users mr-2"></i> View All Presbyters
            </a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-24 bg-gradient-to-r from-blue-600 to-indigo-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-12 shadow-2xl">
            <i class="fas fa-hands-praying text-6xl mb-6 opacity-90"></i>
            <h2 class="text-4xl font-bold mb-6">Join Our Community</h2>
            <p class="text-xl text-white mb-8 leading-relaxed">
                We'd love to connect with you. Whether you're looking for a church home, seeking spiritual guidance, or
                want to get involved, we're here for you.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= SITE_URL ?>/pages/contact.php"
                    class="inline-block bg-white text-amber-700 px-10 py-4 rounded-xl font-bold hover:bg-amber-50 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-envelope mr-2"></i> Contact Us
                </a>
                <a href="<?= SITE_URL ?>/pages/events.php"
                    class="inline-block bg-blue-700 text-white border-2 border-white px-10 py-4 rounded-xl font-bold hover:bg-amber-600 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-calendar mr-2"></i> View Events
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>