<?php
$pageTitle = 'Home - Assemblies of God Church House of Bread';
require_once __DIR__ . '/includes/header.php';

// Get statistics
$pastorsCount = fetchOne("SELECT COUNT(*) as count FROM pastors WHERE is_active = 1")['count'] ?? 0;
$upcomingEventsCount = fetchOne("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE() AND is_active = 1")['count'] ?? 0;
$ministriesCount = fetchOne("SELECT COUNT(*) as count FROM ministries WHERE is_active = 1")['count'] ?? 0;
$sermonsCount = fetchOne("SELECT COUNT(*) as count FROM sermons WHERE is_active = 1")['count'] ?? 0;

// Get upcoming events
$upcomingEvents = fetchAll("SELECT * FROM events WHERE event_date >= CURDATE() AND is_active = 1 ORDER BY is_featured DESC, event_date ASC LIMIT 4");

// Get featured sermons
$featuredSermons = fetchAll("SELECT * FROM sermons WHERE is_active = 1 ORDER BY is_featured DESC, sermon_date DESC LIMIT 6");

// Get ministries
$ministries = fetchAll("SELECT * FROM ministries WHERE is_active = 1 ORDER BY display_order ASC LIMIT 6");

// Get pastors
$pastors = fetchAll("SELECT * FROM pastors WHERE is_active = 1 ORDER BY FIELD(position, 'senior_pastor', 'associate_pastor', 'assistant_pastor', 'youth_pastor', 'children_pastor', 'worship_leader', 'member'), display_order ASC LIMIT 4");
?>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }

        100% {
            background-position: 1000px 0;
        }
    }

    .gradient-bg {
        background: linear-gradient(-45deg, #0f00429d 0%, #1d046e9d 35%, #20018360 70%, #bb66059d 100%);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .shimmer {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }
</style>

<!-- Hero Section -->
<section class="relative text-white overflow-hidden" style="min-height: 100vh;">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <!-- <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Bread and Wheat Field" class="w-full h-full object-cover"> -->
        <img src="<?= SITE_URL . '/images/002.jpg' ?>" alt="Bread and Wheat Field" class="w-full h-full object-cover">
        <div class="absolute inset-0 gradient-bg"></div>
    </div>

    <!-- Animated Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl animate-[float_6s_ease-in-out_infinite]"
            style="background: var(--ag-primary);">
        </div>
        <div class="absolute top-40 right-20 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl animate-[float_8s_ease-in-out_infinite]"
            style="background: var(--ag-secondary);">
        </div>
        <div class="absolute bottom-32 left-1/3 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl animate-[float_7s_ease-in-out_infinite]"
            style="background: var(--ag-gold);">
        </div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-screen flex items-center">
        <div class="text-center w-full">
            <!-- Welcome Badge -->
            <div
                class="inline-block mb-6 px-8 py-3 bg-white/10 backdrop-blur-xl rounded-full border border-white/30 shadow-2xl animate-[fadeInUp_0.8s_ease-out]">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-dove text-green-400"></i>
                    <p class="text-sm font-semibold tracking-wider uppercase">Welcome to AG House of Bread</p>
                </div>
            </div>

            <!-- Main Heading -->
            <h1
                class="text-5xl md:text-7xl lg:text-8xl font-black mb-8 leading-tight animate-[fadeInUp_0.8s_ease-out_0.2s_both]">
                <span class="block">Assemblies of God </span>
                <span
                    class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-orange-400 to-yellow-500">House
                    of Bread</span>
            </h1>

            <!-- Subtitle -->
            <p
                class="text-xl md:text-3xl mb-12 text-gray-100 max-w-4xl mx-auto font-light leading-relaxed animate-[fadeInUp_0.8s_ease-out_0.4s_both]">
                <?= htmlspecialchars($settings['hero_subtitle'] ?? 'Building Faith, Transforming Lives, Spreading the Living Bread of Jesus Christ') ?>
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 justify-center animate-[fadeInUp_0.8s_ease-out_0.6s_both] mb-12">
                <a href="<?= SITE_URL ?>/pages/events.php"
                    class="group px-10 py-5 btn-ag-gold text-white rounded-full font-bold text-lg shadow-2xl transition-all duration-300 inline-flex items-center space-x-3 hover:scale-105">
                    <i class="fas fa-calendar-alt text-xl"></i>
                    <span>Upcoming Events</span>
                    <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </a>
                <a href="<?= SITE_URL ?>/pages/sermons.php"
                    class="group px-10 py-5 bg-white/10 backdrop-blur-xl border-2 border-white rounded-full font-bold text-lg hover:bg-white hover:text-blue-700 transition-all duration-300 inline-flex items-center space-x-3 hover:scale-105">
                    <i class="fas fa-microphone text-xl"></i>
                    <span>Watch Sermons</span>
                </a>
                <?php if (!empty($settings['live_stream_url'])): ?>
                    <a href="<?= htmlspecialchars($settings['live_stream_url']) ?>" target="_blank"
                        class="group px-10 py-5 btn-ag-primary text-white rounded-full font-bold text-lg shadow-2xl transition-all duration-300 inline-flex items-center space-x-3 hover:scale-105">
                        <div class="relative flex items-center">
                            <span class="ag-live-indicator mr-2">LIVE</span>
                        </div>
                        <i class="fas fa-video text-xl"></i>
                        <span>Watch Live Now</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Scroll Indicator -->
            <div class="mt-20 animate-bounce">
                <div class="inline-flex flex-col items-center">
                    <span class="text-sm font-medium mb-2 opacity-75">Scroll to explore</span>
                    <i class="fas fa-chevron-down text-2xl opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Wave -->
    <!-- <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path
                d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z"
                fill="white" />
        </svg>
    </div> -->
</section>

<!-- Stats Section -->
<section class="py-20 bg-gradient-to-br from-orange-50 to-amber-50 relative">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-600 via-amber-600 to-orange-600"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <div class="text-center p-6 md:p-8 bg-white rounded-2xl shadow-xl card-hover">
                <div class="ag-ministry-icon mx-auto mb-4">
                    <i class="fas fa-church text-white text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">10+</div>
                <div class="text-sm md:text-base text-gray-600 font-medium">Years Serving</div>
            </div>
            <div class="text-center p-6 md:p-8 bg-white rounded-2xl shadow-xl card-hover">
                <div class="ag-ministry-icon mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2"><?= $pastorsCount ?></div>
                <div class="text-sm md:text-base text-gray-600 font-medium">Pastors</div>
            </div>
            <div class="text-center p-6 md:p-8 bg-white rounded-2xl shadow-xl card-hover">
                <div class="ag-ministry-icon mx-auto mb-4">
                    <i class="fas fa-hands-helping text-white text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2"><?= $ministriesCount ?></div>
                <div class="text-sm md:text-base text-gray-600 font-medium">Ministries</div>
            </div>
            <div class="text-center p-6 md:p-8 bg-white rounded-2xl shadow-xl card-hover">
                <div class="ag-ministry-icon mx-auto mb-4">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold text-gray-900 mb-2"><?= $upcomingEventsCount ?></div>
                <div class="text-sm md:text-base text-gray-600 font-medium">Events</div>
            </div>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="py-24 bg-white relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-20 right-0 w-96 h-96 bg-amber-100 rounded-full filter blur-3xl opacity-30"></div>
    <div class="absolute bottom-20 left-0 w-96 h-96 bg-yellow-100 rounded-full filter blur-3xl opacity-30"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div class="relative order-2 md:order-1">
                <!-- Floating Decorative Shapes -->
                <div
                    class="absolute -top-8 -left-8 w-24 h-24 bg-gradient-to-br from-amber-400 to-amber-600 rounded-3xl opacity-20 transform rotate-12 animate-[float_6s_ease-in-out_infinite]">
                </div>
                <div
                    class="absolute -bottom-8 -right-8 w-32 h-32 bg-gradient-to-br from-amber-400 to-yellow-600 rounded-3xl opacity-20 transform -rotate-12 animate-[float_8s_ease-in-out_infinite]">
                </div>

                <!-- Main Image with Better Quality -->
                <div class="relative z-10">
                    <img src="<?= SITE_URL ?>/images/005.jpg" alt="Church Community Worship"
                        class="rounded-3xl shadow-2xl w-full h-[500px] object-cover transform hover:scale-105 transition-transform duration-500">

                    <!-- Overlay Badge -->
                    <div
                        class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-xl rounded-2xl p-6 shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Join Us Every Sunday</p>
                                <p class="text-2xl font-bold text-gray-900">8:00 AM</p>
                            </div>
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-amber-600 to-yellow-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-church text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-1 md:order-2">
                <div
                    class="inline-block mb-4 px-5 py-2.5 bg-gradient-to-r from-amber-100 to-yellow-100 text-blue-700 rounded-full text-sm font-bold shadow-sm">
                    <i class="fas fa-cross mr-2"></i>About Us
                </div>
                <h2 class="ag-heading text-4xl md:text-6xl font-black mb-6 leading-tight">
                    Welcome to AG <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-orange-700 to-yellow-700">House
                        of Bread</span>
                </h2>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    <?= htmlspecialchars($settings['about_text'] ?? 'The Assemblies of God House of Bread is a vibrant Pentecostal community dedicated to spreading the Gospel, empowered by the Holy Spirit, and transforming lives through the living bread of Jesus Christ.') ?>
                </p>

                <?php if (!empty($settings['vision_statement'])): ?>
                    <div
                        class="bg-gradient-to-r from-amber-50 to-amber-100 p-6 rounded-2xl mb-4 border-l-4 border-amber-600 shadow-sm hover:shadow-md transition-shadow">
                        <h3 class="font-bold text-gray-900 mb-2 flex items-center text-lg">
                            <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-eye text-white text-sm"></i>
                            </div>
                            Our Vision
                        </h3>
                        <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($settings['vision_statement']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($settings['mission_statement'])): ?>
                    <div
                        class="bg-gradient-to-r from-amber-50 to-yellow-100 p-6 rounded-2xl mb-8 border-l-4 border-amber-700 shadow-sm hover:shadow-md transition-shadow">
                        <h3 class="font-bold text-gray-900 mb-2 flex items-center text-lg">
                            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-bullseye text-white text-sm"></i>
                            </div>
                            Our Mission
                        </h3>
                        <p class="text-gray-700 leading-relaxed"><?= htmlspecialchars($settings['mission_statement']) ?></p>
                    </div>
                <?php endif; ?>

                <a href="<?= SITE_URL ?>/pages/about.php"
                    class="group inline-flex items-center px-10 py-5 bg-gradient-to-r from-amber-700 via-yellow-700 to-amber-800 text-white rounded-full font-bold text-lg shadow-2xl hover:shadow-amber-500/50 transition-all hover:scale-105">
                    Learn More About Us
                    <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="py-24 bg-gradient-to-br from-amber-50 via-yellow-50 to-yellow-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block mb-4 px-4 py-2 bg-green-100 text-green-600 rounded-full text-sm font-semibold">
                <i class="fas fa-calendar-star mr-2"></i>What's Happening
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Upcoming <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-amber-600">Events</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Join us in worship, fellowship, and community</p>
        </div>

        <?php if (!empty($upcomingEvents)): ?>
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <?php $featured = $upcomingEvents[0]; ?>
                <div class="md:col-span-2 bg-white rounded-3xl shadow-xl overflow-hidden card-hover border border-gray-100">
                    <div class="md:flex">
                        <div class="md:w-1/2 relative h-80 md:h-auto">
                            <?php if ($featured['featured_image']): ?>
                                <img src="<?= SITE_URL . '/' . $featured['featured_image'] ?>"
                                    alt="<?= htmlspecialchars($featured['title']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="relative w-full h-full">
                                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&q=80"
                                        alt="Church Event" class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-green-600/80 to-amber-600/80 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-7xl opacity-40"></i>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div
                                class="absolute top-6 left-6 px-5 py-2.5 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full font-bold shadow-2xl flex items-center space-x-2">
                                <i class="fas fa-star"></i>
                                <span>Featured Event</span>
                            </div>
                        </div>
                        <div class="md:w-1/2 p-8 md:p-12">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span
                                    class="px-4 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold capitalize"><?= htmlspecialchars($featured['event_type']) ?></span>
                                <span class="text-gray-500"><i
                                        class="far fa-calendar mr-2"></i><?= formatDate($featured['event_date'], 'M d, Y') ?></span>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($featured['title']) ?>
                            </h3>
                            <p class="text-gray-600 mb-6">
                                <?= htmlspecialchars(substr($featured['description'] ?? '', 0, 200)) ?>...
                            </p>
                            <div class="space-y-3 mb-6">
                                <?php if ($featured['event_time']): ?>
                                    <div class="flex items-center text-gray-700">
                                        <i class="far fa-clock w-6 text-amber-700"></i>
                                        <span><?= date('g:i A', strtotime($featured['event_time'])) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($featured['location']): ?>
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-map-marker-alt w-6 text-red-600"></i>
                                        <span><?= htmlspecialchars($featured['location']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <a href="<?= SITE_URL ?>/pages/event-details.php?id=<?= $featured['id'] ?>"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-amber-600 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all">
                                View Details <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <?php
                $eventImages = [
                    'https://images.unsplash.com/photo-1502444330042-d1a1ddf9bb5b?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1478147427282-58a87a120781?w=600&h=400&fit=crop&q=80'
                ];
                for ($i = 1; $i < min(3, count($upcomingEvents)); $i++):
                    $event = $upcomingEvents[$i]; ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover border border-gray-100">
                        <div class="relative h-56 overflow-hidden group">
                            <?php if ($event['featured_image']): ?>
                                <img src="<?= SITE_URL . '/' . $event['featured_image'] ?>"
                                    alt="<?= htmlspecialchars($event['title']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="relative w-full h-full">
                                    <img src="<?= $eventImages[$i - 1] ?>" alt="Church Event"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-br from-amber-600/70 to-yellow-600/70"></div>
                                </div>
                            <?php endif; ?>
                            <div
                                class="absolute top-4 right-4 bg-white text-gray-900 rounded-2xl shadow-2xl p-4 text-center backdrop-blur-sm">
                                <div
                                    class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-br from-amber-600 to-yellow-600">
                                    <?= date('d', strtotime($event['event_date'])) ?>
                                </div>
                                <div class="text-xs uppercase font-bold text-gray-600">
                                    <?= date('M', strtotime($event['event_date'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <span
                                class="px-3 py-1 bg-yellow-100 text-purple-700 rounded-full text-xs font-semibold capitalize"><?= htmlspecialchars($event['event_type']) ?></span>
                            <h3 class="text-xl font-bold text-gray-900 mt-3 mb-2"><?= htmlspecialchars($event['title']) ?></h3>
                            <p class="text-gray-600 text-sm mb-4">
                                <?= htmlspecialchars(substr($event['description'] ?? '', 0, 100)) ?>...
                            </p>
                            <?php if ($event['location']): ?>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    <?= htmlspecialchars($event['location']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="text-center">
                <a href="<?= SITE_URL ?>/pages/events.php"
                    class="inline-flex items-center px-8 py-4 bg-white text-gray-900 rounded-full font-semibold shadow-xl hover:shadow-2xl transition-all">
                    View All Events <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-12 bg-white rounded-3xl shadow-lg">
                <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">No upcoming events. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Sermons Section -->
<section class="py-24 bg-gradient-to-br from-gray-900 via-blue-900 to-yellow-900 text-white relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 opacity-5">
        <img src="https://images.unsplash.com/photo-1507692049790-de58290a4334?w=1920&h=1080&fit=crop&q=80"
            alt="Bible Study" class="w-full h-full object-cover">
    </div>

    <!-- Pattern Overlay -->
    <div class="absolute inset-0 opacity-10"
        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E');">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <div class="inline-block mb-4 px-4 py-2 glass-effect rounded-full text-sm font-semibold">
                <i class="fas fa-microphone mr-2"></i>Grow In Faith
            </div>
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                Latest <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-500">Sermons</span>
            </h2>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">Powerful messages to inspire your faith journey</p>
        </div>

        <?php if (!empty($featuredSermons)): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <?php
                $sermonImages = [
                    'https://images.unsplash.com/photo-1490730141103-6cac27aaab94?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1507692049790-de58290a4334?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1501612780327-45045538702b?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1496449903678-68ddcb189a24?w=600&h=400&fit=crop&q=80'
                ];
                $imgIndex = 0;
                foreach (array_slice($featuredSermons, 0, 6) as $sermon): ?>
                    <a href="<?= SITE_URL ?>/pages/sermon-details.php?id=<?= $sermon['id'] ?>"
                        class="group bg-white/10 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl card-hover border border-white/20 hover:border-white/40 transition-all block">
                        <div class="relative h-56 overflow-hidden">
                            <?php if ($sermon['thumbnail']): ?>
                                <img src="<?= SITE_URL . '/' . $sermon['thumbnail'] ?>"
                                    alt="<?= htmlspecialchars($sermon['title']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="relative w-full h-full">
                                    <img src="<?= $sermonImages[$imgIndex % 6] ?>" alt="Sermon"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-amber-600/60 to-amber-600/60 flex items-center justify-center">
                                        <i
                                            class="fas fa-bible text-white text-6xl opacity-40 group-hover:scale-110 transition-transform duration-500"></i>
                                    </div>
                                </div>
                            <?php endif;
                            $imgIndex++; ?>
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div
                                    class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-2xl transform scale-90 group-hover:scale-100 transition-transform">
                                    <i class="fas fa-play text-amber-700 text-xl ml-1"></i>
                                </div>
                            </div>
                            <?php if ($sermon['is_featured']): ?>
                                <div
                                    class="absolute top-4 left-4 px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <?php if ($sermon['category']): ?>
                                    <span
                                        class="px-3 py-1 bg-blue-500/20 text-blue-200 rounded-full text-xs font-semibold"><?= htmlspecialchars($sermon['category']) ?></span>
                                <?php endif; ?>
                                <span class="text-sm text-gray-300"><i
                                        class="far fa-calendar mr-1"></i><?= formatDate($sermon['sermon_date'], 'M d') ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2 line-clamp-2"><?= htmlspecialchars($sermon['title']) ?>
                            </h3>
                            <p class="text-gray-300 text-sm mb-3"><i
                                    class="fas fa-user-tie mr-2 text-yellow-400"></i><?= htmlspecialchars($sermon['speaker']) ?>
                            </p>
                            <?php if ($sermon['scripture_reference']): ?>
                                <p class="text-blue-300 text-sm mb-4"><i
                                        class="fas fa-book mr-2"></i><?= htmlspecialchars($sermon['scripture_reference']) ?></p>
                            <?php endif; ?>
                            <div class="flex gap-2">
                                <?php if ($sermon['audio_file']): ?>
                                    <span class="flex-1 text-center px-3 py-2 bg-green-500/20 text-green-300 rounded-lg text-xs"><i
                                            class="fas fa-headphones mr-1"></i>Audio</span>
                                <?php endif; ?>
                                <?php if ($sermon['video_url']): ?>
                                    <span class="flex-1 text-center px-3 py-2 bg-red-500/20 text-red-300 rounded-lg text-xs"><i
                                            class="fas fa-video mr-1"></i>Video</span>
                                <?php endif; ?>
                                <?php if ($sermon['pdf_file']): ?>
                                    <span
                                        class="flex-1 text-center px-3 py-2 bg-purple-500/20 text-purple-300 rounded-lg text-xs"><i
                                            class="fas fa-file-pdf mr-1"></i>PDF</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="text-center">
                <a href="<?= SITE_URL ?>/pages/sermons.php"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-yellow-500 text-white rounded-full font-semibold shadow-2xl hover:shadow-pink-500/50 transition-all">
                    Browse Sermon Library <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-12 glass-effect rounded-3xl">
                <i class="fas fa-microphone text-6xl text-gray-400 mb-4"></i>
                <p class="text-xl text-gray-300">No sermons yet. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Ministries Section -->
<?php if (!empty($ministries)): ?>
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block mb-4 px-4 py-2 bg-orange-100 text-orange-600 rounded-full text-sm font-semibold">
                    <i class="fas fa-hands-helping mr-2"></i>Get Involved
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Our <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">Ministries</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Find your place to serve and grow</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12 ">
                <?php
                $ministryIcons = ['users'];
                $ministryGradients = [
                    'from-orange-500 to-red-500',
                    'from-yellow-500 to-rose-500',
                    'from-amber-500 to-indigo-500',
                    'from-amber-500 to-cyan-500',
                    'from-green-500 to-emerald-500',
                    'from-yellow-500 to-orange-500'
                ];
                $minIndex = 0;
                foreach ($ministries as $ministry): ?>
                    <div
                        class="group bg-gradient-to-br from-white to-gray-50 rounded-3xl shadow-xl p-8 card-hover border-2 border-gray-100 hover:border-orange-400 transition-all overflow-hidden relative">
                        <!-- Decorative Background -->
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br <?= $ministryGradients[$minIndex % 6] ?> opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500">
                        </div>

                        <div class="relative">
                            <div
                                class="w-20 h-20 mb-6 bg-gradient-to-br <?= $ministryGradients[$minIndex % 6] ?> rounded-2xl flex items-center justify-center transform group-hover:rotate-12 group-hover:scale-110 transition-all shadow-xl">
                                <?php if ($ministry['image']): ?>
                                    <img src="<?= SITE_URL . '/' . $ministry['image'] ?>"
                                        class="w-full h-full object-cover rounded-2xl" alt="">
                                <?php else: ?>
                                    <i class="fas fa-<?= $ministryIcons[0] ?> text-white text-3xl"></i>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">
                                <?= htmlspecialchars($ministry['name']) ?>
                            </h3>
                            <p class="text-gray-600 mb-4 leading-relaxed">
                                <?= htmlspecialchars(substr($ministry['description'] ?? '', 0, 120)) ?>...
                            </p>
                            <?php if ($ministry['meeting_day'] || $ministry['meeting_time']): ?>
                                <div
                                    class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 mb-4 border-l-4 border-orange-500">
                                    <p class="text-sm text-gray-800 font-medium">
                                        <i class="far fa-clock text-orange-600 mr-2"></i>
                                        <strong><?= htmlspecialchars($ministry['meeting_day'] ?? 'TBD') ?></strong>
                                        <?php if ($ministry['meeting_time']): ?>
                                            at <strong
                                                class="text-orange-600"><?= htmlspecialchars($ministry['meeting_time']) ?></strong>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if ($ministry['leader_name']): ?>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-user-tie text-amber-700 mr-2"></i>
                                    Led by <strong class="ml-1"><?= htmlspecialchars($ministry['leader_name']) ?></strong>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php $minIndex++; endforeach; ?>
            </div>

            <div class="text-center">
                <a href="<?= SITE_URL ?>/pages/ministries.php"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-full font-semibold shadow-xl hover:shadow-2xl transition-all">
                    Explore All Ministries <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Presbyters Section -->
<?php if (!empty($presbyters)): ?>
    <section
        class="py-24 bg-gradient-to-br from-indigo-900 via-yellow-900 to-yellow-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'80\' height=\'80\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' opacity=\'0.4\'%3E%3Ccircle cx=\'10\' cy=\'10\' r=\'10\'/%3E%3Ccircle cx=\'50\' cy=\'50\' r=\'10\'/%3E%3Ccircle cx=\'90\' cy=\'10\' r=\'10\'/%3E%3C/g%3E%3C/svg%3E');">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <div class="inline-block mb-4 px-4 py-2 glass-effect rounded-full text-sm font-semibold">
                    <i class="fas fa-users-cog mr-2"></i>District Leadership
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Meet Our <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-500">Presbyters</span>
                </h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">Dedicated leaders serving with wisdom and compassion</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <?php foreach ($presbyters as $presbyter):
                    $positionColors = [
                        'district_superintendent' => 'from-amber-500 to-yellow-700',
                        'assistant_ds' => 'from-amber-500 to-amber-700',
                        'secretary' => 'from-green-500 to-green-700',
                        'treasurer' => 'from-yellow-500 to-yellow-700',
                        'member' => 'from-gray-500 to-gray-700'
                    ];
                    $bgColor = $positionColors[$presbyter['position']] ?? 'from-gray-500 to-gray-700';
                    ?>
                    <div class="group text-center">
                        <div class="relative mb-6 inline-block">
                            <div
                                class="absolute inset-0 bg-gradient-to-r <?= $bgColor ?> rounded-full blur-lg opacity-75 group-hover:opacity-100 transition-opacity">
                            </div>
                            <?php if ($presbyter['photo']): ?>
                                <img src="<?= SITE_URL . '/' . $presbyter['photo'] ?>"
                                    alt="<?= htmlspecialchars($presbyter['full_name']) ?>"
                                    class="relative w-40 h-40 rounded-full object-cover border-4 border-white shadow-2xl mx-auto group-hover:scale-105 transition-transform">
                            <?php else: ?>
                                <div
                                    class="relative w-40 h-40 bg-gradient-to-r <?= $bgColor ?> rounded-full flex items-center justify-center border-4 border-white shadow-2xl mx-auto">
                                    <i class="fas fa-user text-white text-5xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2"><?= htmlspecialchars($presbyter['full_name']) ?></h3>
                        <p class="text-sm text-gray-300 mb-3 capitalize"><?= str_replace('_', ' ', $presbyter['position']) ?>
                        </p>
                        <?php if ($presbyter['credentials']): ?>
                            <p class="text-xs text-gray-400 mb-3"><?= htmlspecialchars($presbyter['credentials']) ?></p>
                        <?php endif; ?>
                        <div class="flex justify-center gap-3">
                            <?php if ($presbyter['email']): ?>
                                <a href="mailto:<?= htmlspecialchars($presbyter['email']) ?>"
                                    class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($presbyter['phone']): ?>
                                <a href="tel:<?= htmlspecialchars($presbyter['phone']) ?>"
                                    class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-all">
                                    <i class="fas fa-phone"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center">
                <a href="<?= SITE_URL ?>/pages/presbyters.php"
                    class="inline-flex items-center px-8 py-4 glass-effect border-2 border-white rounded-full font-semibold hover:bg-white hover:text-purple-900 transition-all">
                    View All Leadership <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Prayer CTA -->
<section class="py-24 bg-gradient-to-r from-amber-700 via-yellow-700 to-amber-800 text-white relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?w=1920&h=600&fit=crop&q=80"
            alt="Prayer Hands" class="w-full h-full object-cover">
    </div>

    <!-- Animated Gradient Orbs -->
    <div class="absolute inset-0 overflow-hidden">
        <div
            class="absolute top-1/4 left-1/4 w-96 h-96 bg-white/10 rounded-full filter blur-3xl animate-[float_10s_ease-in-out_infinite]">
        </div>
        <div
            class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-yellow-300/10 rounded-full filter blur-3xl animate-[float_15s_ease-in-out_infinite]">
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="inline-block mb-8 animate-[float_3s_ease-in-out_infinite]">
            <div
                class="w-28 h-28 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-xl border-4 border-white/30 shadow-2xl">
                <i class="fas fa-praying-hands text-6xl"></i>
            </div>
        </div>
        <h2 class="text-5xl md:text-6xl font-black mb-6 leading-tight">
            We're Here to <span class="text-yellow-300 relative">
                Pray
                <svg class="absolute -bottom-2 left-0 w-full" height="12" viewBox="0 0 200 12"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 8 Q50 2, 100 8 T200 8" stroke="currentColor" stroke-width="4" fill="none"
                        class="text-yellow-300" />
                </svg>
            </span> With You
        </h2>
        <p class="text-2xl mb-10 text-white/90 max-w-2xl mx-auto leading-relaxed font-light">
            No matter what you're facing, you don't have to face it alone. Submit your prayer request today.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="<?= SITE_URL ?>/pages/contact.php#prayer"
                class="group inline-flex items-center px-10 py-5 bg-white text-amber-700 rounded-full font-bold text-lg shadow-2xl hover:shadow-white/50 transition-all hover:scale-105">
                <i class="fas fa-praying-hands mr-3 text-xl group-hover:scale-110 transition-transform"></i>
                Submit Prayer Request
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
            </a>
            <a href="<?= SITE_URL ?>/pages/contact.php"
                class="group inline-flex items-center px-10 py-5 glass-effect border-2 border-white rounded-full font-bold text-lg hover:bg-white hover:text-amber-700 transition-all hover:scale-105">
                <i class="fas fa-envelope mr-3 text-xl"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>

<!-- Newsletter -->


<script>
    document.getElementById('newsletterForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageEl = document.getElementById('newsletterMessage');

        try {
            const response = await fetch('<?= SITE_URL ?>/api/newsletter.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            messageEl.textContent = data.message;
            messageEl.className = data.success ? 'mt-4 text-sm text-green-300 font-semibold' : 'mt-4 text-sm text-red-300';

            if (data.success) this.reset();
        } catch (error) {
            messageEl.textContent = 'An error occurred. Please try again.';
            messageEl.className = 'mt-4 text-sm text-red-300';
        }
    });

    // Scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    entry.target.style.transition = 'all 0.8s ease-out';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card-hover').forEach(el => observer.observe(el));
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>