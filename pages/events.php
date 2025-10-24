<?php
$pageTitle = 'Events';
require_once __DIR__ . '/../includes/header.php';

// Get filter parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'upcoming';
$eventType = isset($_GET['type']) ? $_GET['type'] : '';

// Build query based on filter
if ($filter === 'all') {
    $query = "SELECT * FROM events";
    $params = [];
    $types = '';
} elseif ($filter === 'past') {
    $query = "SELECT * FROM events WHERE event_date < CURDATE()";
    $params = [];
    $types = '';
} else {
    $query = "SELECT * FROM events WHERE event_date >= CURDATE()";
    $params = [];
    $types = '';
}

// Add event type filter if provided
if (!empty($eventType)) {
    $query .= (strpos($query, 'WHERE') !== false ? ' AND' : ' WHERE') . " event_type = ?";
    $params[] = $eventType;
    $types .= 's';
}

$query .= " ORDER BY event_date " . ($filter === 'past' ? 'DESC' : 'ASC') . ", event_time ASC";

$events = fetchAll($query, $params, $types);

// Get unique event types for filter
$eventTypes = fetchAll("SELECT DISTINCT event_type FROM events WHERE event_type IS NOT NULL AND event_type != '' ORDER BY event_type");

// Separate featured events (upcoming events in the next 7 days)
$featuredEvents = [];
$regularEvents = [];
$today = date('Y-m-d');
$nextWeek = date('Y-m-d', strtotime('+7 days'));

if ($filter === 'upcoming' || $filter === 'all') {
    foreach ($events as $event) {
        if ($event['event_date'] >= $today && $event['event_date'] <= $nextWeek) {
            $featuredEvents[] = $event;
        } else {
            $regularEvents[] = $event;
        }
    }
} else {
    $regularEvents = $events;
}
?>

<!-- Hero Section with Image -->
<section class="relative bg-gradient-to-br from-amber-700 via-yellow-700 to-amber-800 text-white overflow-hidden" style="min-height: 60vh;">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="../images/07.jpg"
             alt="Church Events"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-900/70 via-yellow-900/55 to-yellow-800/80"></div>
    </div>

    <!-- Animated Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 flex items-center min-h-[60vh]">
        <div class="text-center w-full">
            <!-- Icon Badge -->
            <div class="inline-block mb-6 px-8 py-3 bg-white/10 backdrop-blur-xl rounded-full border border-white/30 shadow-2xl">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                    <span class="font-semibold tracking-wider uppercase">Events & Activities</span>
                </div>
            </div>

            <h1 class="text-5xl md:text-7xl font-black mb-8 leading-tight">
                <span class="block">Church</span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-500">Events</span>
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed mb-8">
                Join us for worship, fellowship, and growth opportunities throughout the year
            </p>

            <!-- Quick Stats -->
            <div class="flex flex-wrap gap-6 justify-center">
                <div class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
                    <div class="text-3xl font-bold"><?= count($events) ?></div>
                    <div class="text-sm opacity-75">Total Events</div>
                </div>
                <div class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
                    <div class="text-3xl font-bold"><?= count($featuredEvents) ?></div>
                    <div class="text-sm opacity-75">This Week</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Wave -->
    <!-- <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
        </svg>
    </div> -->
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
</style>

<!-- Breadcrumb Navigation -->
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="<?= SITE_URL ?>" class="hover:text-amber-700 transition">Home</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-amber-700 font-semibold">Events</span>
        </div>
    </div>
</section>

<!-- Filter Tabs and Event Type Filter -->
<section class="bg-gradient-to-b from-gray-50 to-white py-8 sticky top-0 z-10 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Tabs -->
            <div class="flex flex-wrap gap-2">
                <a href="?filter=upcoming<?= !empty($eventType) ? '&type=' . urlencode($eventType) : '' ?>"
                   class="px-6 py-3 rounded-xl font-semibold transition shadow-sm <?= $filter === 'upcoming' ? 'bg-gradient-to-r from-amber-600 to-cyan-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-calendar-plus mr-2"></i> Upcoming
                </a>
                <a href="?filter=all<?= !empty($eventType) ? '&type=' . urlencode($eventType) : '' ?>"
                   class="px-6 py-3 rounded-xl font-semibold transition shadow-sm <?= $filter === 'all' ? 'bg-gradient-to-r from-amber-600 to-cyan-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-calendar mr-2"></i> All Events
                </a>
                <a href="?filter=past<?= !empty($eventType) ? '&type=' . urlencode($eventType) : '' ?>"
                   class="px-6 py-3 rounded-xl font-semibold transition shadow-sm <?= $filter === 'past' ? 'bg-gradient-to-r from-amber-600 to-cyan-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                    <i class="fas fa-history mr-2"></i> Past
                </a>
            </div>

            <!-- Event Type Filter -->
            <div class="flex items-center gap-3">
                <label for="event-type-filter" class="text-sm font-medium text-gray-700">Filter by Type:</label>
                <select id="event-type-filter"
                        onchange="window.location.href='?filter=<?= $filter ?>&type=' + this.value"
                        class="px-4 py-2 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent bg-white">
                    <option value="">All Types</option>
                    <?php foreach ($eventTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type['event_type']) ?>"
                            <?= $eventType === $type['event_type'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($type['event_type']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($eventType)): ?>
                <a href="?filter=<?= $filter ?>" class="text-sm text-amber-700 hover:text-blue-800">
                    <i class="fas fa-times"></i> Clear
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Featured Events (Next 7 Days) -->
<?php if (!empty($featuredEvents) && ($filter === 'upcoming' || $filter === 'all')): ?>
<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                <i class="fas fa-star text-yellow-500 mr-2"></i> Featured Events
            </h2>
            <p class="text-lg text-gray-600">Coming up in the next 7 days</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php
            $featuredEventImages = [
                'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop&q=80',
                'https://images.unsplash.com/photo-1502444330042-d1a1ddf9bb5b?w=800&h=600&fit=crop&q=80'
            ];
            $featIdx = 0;
            foreach ($featuredEvents as $event): ?>
            <div class="group bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 relative cursor-pointer" onclick="window.location.href='<?= SITE_URL ?>/pages/event-details.php?id=<?= $event['id'] ?>'">
                <div class="relative overflow-hidden">
                    <?php if ($event['featured_image']): ?>
                    <img src="<?= SITE_URL . '/' . htmlspecialchars($event['featured_image']) ?>"
                         alt="<?= htmlspecialchars($event['title']) ?>"
                         class="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="relative w-full h-80">
                        <img src="<?= $featuredEventImages[$featIdx % 2] ?>"
                             alt="Church Event"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/70 to-yellow-600/70 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-9xl text-white opacity-30 group-hover:scale-110 transition-transform duration-500"></i>
                        </div>
                    </div>
                    <?php endif; $featIdx++; ?>

                    <!-- Date Badge -->
                    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg text-center">
                        <div class="text-3xl font-bold text-amber-700">
                            <?= date('d', strtotime($event['event_date'])) ?>
                        </div>
                        <div class="text-sm font-semibold text-gray-600">
                            <?= date('M', strtotime($event['event_date'])) ?>
                        </div>
                    </div>

                    <!-- Event Type Badge -->
                    <?php
                    $typeColors = [
                        'Conference' => 'bg-purple-600',
                        'Service' => 'bg-amber-600',
                        'Meeting' => 'bg-green-600',
                        'Workshop' => 'bg-orange-600',
                        'Other' => 'bg-gray-600'
                    ];
                    $typeColor = $typeColors[$event['event_type']] ?? 'bg-gray-600';
                    ?>
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-2 rounded-full text-sm font-bold text-white <?= $typeColor ?> shadow-lg">
                            <?= htmlspecialchars($event['event_type']) ?>
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        <?= htmlspecialchars($event['title']) ?>
                    </h3>

                    <?php if ($event['description']): ?>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        <?= nl2br(htmlspecialchars($event['description'])) ?>
                    </p>
                    <?php endif; ?>

                    <!-- Event Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar text-amber-700"></i>
                            </div>
                            <span class="font-medium"><?= formatDate($event['event_date'], 'l, F j, Y') ?></span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-amber-700"></i>
                            </div>
                            <span class="font-medium">
                                <?= date('g:i A', strtotime($event['start_time'])) ?>
                                <?php if ($event['end_time']): ?>
                                - <?= date('g:i A', strtotime($event['end_time'])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if ($event['location']): ?>
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-amber-700"></i>
                            </div>
                            <span class="font-medium"><?= htmlspecialchars($event['location']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($event['contact_person']): ?>
                        <div class="flex items-center text-gray-700">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-amber-700"></i>
                            </div>
                            <span class="font-medium"><?= htmlspecialchars($event['contact_person']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contact Information -->
                    <?php if ($event['contact_email'] || $event['contact_phone']): ?>
                    <div class="flex flex-wrap gap-3 mb-6">
                        <?php if ($event['contact_email']): ?>
                        <a href="mailto:<?= htmlspecialchars($event['contact_email']) ?>"
                           class="flex items-center gap-2 text-sm text-amber-700 hover:text-blue-800">
                            <i class="fas fa-envelope"></i>
                            <?= htmlspecialchars($event['contact_email']) ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($event['contact_phone']): ?>
                        <a href="tel:<?= htmlspecialchars($event['contact_phone']) ?>"
                           class="flex items-center gap-2 text-sm text-amber-700 hover:text-blue-800">
                            <i class="fas fa-phone"></i>
                            <?= htmlspecialchars($event['contact_phone']) ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Registration Button -->
                    <?php if ($event['registration_link']): ?>
                    <a href="<?= htmlspecialchars($event['registration_link']) ?>"
                       target="_blank"
                       class="block w-full text-center bg-gradient-to-r from-amber-600 to-cyan-600 text-white px-6 py-4 rounded-xl font-bold hover:from-amber-700 hover:to-cyan-700 transition shadow-lg hover:shadow-xl">
                        <i class="fas fa-user-plus mr-2"></i> Register Now
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Regular Events Grid -->
<?php if (!empty($regularEvents)): ?>
<section class="py-16 <?= empty($featuredEvents) ? 'bg-gradient-to-b from-white to-gray-50' : 'bg-gray-50' ?>">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($featuredEvents)): ?>
        <h2 class="text-3xl font-bold text-gray-900 mb-8">
            <?= $filter === 'past' ? 'Past Events' : 'More Events' ?>
        </h2>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $regularEventImages = [
                'https://images.unsplash.com/photo-1478147427282-58a87a120781?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=600&h=400&fit=crop&q=80'
            ];
            $regIdx = 0;
            foreach ($regularEvents as $event): ?>
            <div class="group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 <?= $filter === 'past' ? 'opacity-90' : '' ?> relative cursor-pointer" onclick="window.location.href='<?= SITE_URL ?>/pages/event-details.php?id=<?= $event['id'] ?>'">
                <div class="relative overflow-hidden">
                    <?php if ($event['featured_image']): ?>
                    <img src="<?= SITE_URL . '/' . htmlspecialchars($event['featured_image']) ?>"
                         alt="<?= htmlspecialchars($event['title']) ?>"
                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="relative w-full h-56">
                        <img src="<?= $regularEventImages[$regIdx % 3] ?>"
                             alt="Church Event"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-600/70 to-yellow-600/70"></div>
                    </div>
                    <?php endif; $regIdx++; ?>

                    <!-- Date Badge -->
                    <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm rounded-lg p-3 shadow-lg text-center">
                        <div class="text-2xl font-bold text-amber-700">
                            <?= date('d', strtotime($event['event_date'])) ?>
                        </div>
                        <div class="text-xs font-semibold text-gray-600">
                            <?= date('M', strtotime($event['event_date'])) ?>
                        </div>
                    </div>

                    <!-- Event Type Badge -->
                    <?php
                    $typeColors = [
                        'Conference' => 'bg-purple-600',
                        'Service' => 'bg-amber-600',
                        'Meeting' => 'bg-green-600',
                        'Workshop' => 'bg-orange-600',
                        'Other' => 'bg-gray-600'
                    ];
                    $typeColor = $typeColors[$event['event_type']] ?? 'bg-gray-600';
                    ?>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold text-white <?= $typeColor ?> shadow-md">
                            <?= htmlspecialchars($event['event_type']) ?>
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                        <?= htmlspecialchars($event['title']) ?>
                    </h3>

                    <?php if ($event['description']): ?>
                    <p class="text-gray-700 mb-4 text-sm leading-relaxed line-clamp-3">
                        <?= nl2br(htmlspecialchars($event['description'])) ?>
                    </p>
                    <?php endif; ?>

                    <!-- Event Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar text-amber-700 w-5 mr-2"></i>
                            <span><?= formatDate($event['event_date'], 'M j, Y') ?></span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock text-amber-700 w-5 mr-2"></i>
                            <span>
                                <?= date('g:i A', strtotime($event['event_time'])) ?>
                                <?php if ($event['end_time']): ?>
                                - <?= date('g:i A', strtotime($event['end_time'])) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if ($event['location']): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt text-amber-700 w-5 mr-2"></i>
                            <span class="line-clamp-1"><?= htmlspecialchars($event['location']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contact & Registration -->
                    <?php if ($event['registration_link'] && $filter !== 'past'): ?>
                    <a href="<?= htmlspecialchars($event['registration_link']) ?>"
                       target="_blank"
                       class="block w-full text-center bg-gradient-to-r from-amber-600 to-cyan-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-amber-700 hover:to-cyan-700 transition shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                    <?php elseif ($event['contact_email'] || $event['contact_phone']): ?>
                    <div class="flex gap-2">
                        <?php if ($event['contact_email']): ?>
                        <a href="mailto:<?= htmlspecialchars($event['contact_email']) ?>"
                           class="flex-1 text-center bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($event['contact_phone']): ?>
                        <a href="tel:<?= htmlspecialchars($event['contact_phone']) ?>"
                           class="flex-1 text-center bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cyan-700 transition">
                            <i class="fas fa-phone"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Empty State -->
<?php if (empty($events)): ?>
<section class="py-24 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-3xl shadow-xl p-16">
            <i class="fas fa-calendar-times text-8xl text-gray-300 mb-6"></i>
            <h3 class="text-3xl font-bold text-gray-900 mb-4">No Events Found</h3>
            <p class="text-lg text-gray-600 mb-8">
                <?php if ($filter === 'past'): ?>
                    No past events to display at this time.
                <?php elseif (!empty($eventType)): ?>
                    No events found for this type. Try a different filter.
                <?php else: ?>
                    Check back soon for new events and activities!
                <?php endif; ?>
            </p>
            <?php if (!empty($eventType) || $filter !== 'upcoming'): ?>
            <a href="<?= SITE_URL ?>/pages/events.php"
               class="inline-block bg-gradient-to-r from-amber-600 to-cyan-600 text-white px-8 py-4 rounded-xl font-bold hover:from-amber-700 hover:to-cyan-700 transition shadow-lg">
                <i class="fas fa-calendar-alt mr-2"></i> View All Events
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
