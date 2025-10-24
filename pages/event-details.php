<?php
$pageTitle = 'Event Details';
require_once __DIR__ . '/../includes/header.php';

// Get event ID from URL
$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($eventId === 0) {
    // Redirect to events page if no ID provided
    header('Location: ' . SITE_URL . '/pages/events.php');
    exit;
}

// Get event details
$event = fetchOne("SELECT * FROM events WHERE id = ? AND is_active = 1", [$eventId], 'i');

if (!$event) {
    // Event not found, redirect
    header('Location: ' . SITE_URL . '/pages/events.php');
    exit;
}


// Get related events (same event type, different from current)
$relatedEvents = fetchAll("SELECT * FROM events WHERE event_type = ? AND id != ? AND event_date >= CURDATE() AND is_active = 1 ORDER BY event_date ASC LIMIT 3", [$event['event_type'], $eventId], 'si');
?>

<!-- Hero Section with Event Image -->
<section class="relative bg-gradient-to-br from-amber-700 via-yellow-700 to-amber-800 text-white overflow-hidden" style="min-height: 60vh;">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <?php if ($event['featured_image']): ?>
        <img src="<?= SITE_URL . '/' . $event['featured_image'] ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
        <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=1920&h=1080&fit=crop&q=80" alt="Event" class="w-full h-full object-cover">
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-br from-amber-900/90 via-yellow-900/85 to-amber-800/90"></div>
    </div>

    <!-- Animated Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-96 h-96 bg-amber-500 rounded-full mix-blend-multiply filter blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-yellow-500 rounded-full mix-blend-multiply filter blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 flex items-center min-h-[60vh]">
        <div class="text-center w-full">
            <!-- Event Type Badge -->
            <div class="inline-block mb-6 px-6 py-2 bg-white/20 backdrop-blur-xl rounded-full border border-white/30 shadow-2xl">
                <span class="font-semibold tracking-wider uppercase"><?= htmlspecialchars($event['event_type']) ?></span>
            </div>

            <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                <?= htmlspecialchars($event['title']) ?>
            </h1>

            <!-- Date and Time -->
            <div class="flex flex-wrap gap-4 justify-center mb-6">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-2xl mr-3"></i>
                    <span class="text-xl"><?= formatDate($event['event_date'], 'F j, Y') ?></span>
                </div>
                <?php if ($event['start_time']): ?>
                <div class="flex items-center">
                    <i class="fas fa-clock text-2xl mr-3"></i>
                    <span class="text-xl"><?= date('g:i A', strtotime($event['start_time'])) ?><?php if ($event['end_time']) echo ' - ' . date('g:i A', strtotime($event['end_time'])); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($event['location']): ?>
            <div class="flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-2xl mr-3"></i>
                <span class="text-xl"><?= htmlspecialchars($event['location']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
        </svg>
    </div>
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
            <a href="<?= SITE_URL ?>" class="hover:text-amber-600 transition">Home</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="<?= SITE_URL ?>/pages/events.php" class="hover:text-amber-600 transition">Events</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-amber-600 font-semibold"><?= htmlspecialchars($event['title']) ?></span>
        </div>
    </div>
</section>

<!-- Event Details -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Description -->
                <?php if ($event['description']): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">About This Event</h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($event['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Event Information -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Event Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div class="bg-amber-50 rounded-xl p-6 border-l-4 border-amber-600">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Date</p>
                                    <p class="font-bold text-gray-900"><?= formatDate($event['event_date'], 'l, F j, Y') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Time -->
                        <?php if ($event['event_time']): ?>
                        <div class="bg-yellow-50 rounded-xl p-6 border-l-4 border-yellow-600">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Time</p>
                                    <p class="font-bold text-gray-900"><?= date('g:i A', strtotime($event['event_time'])) ?><?php if ($event['end_time']) echo ' - ' . date('g:i A', strtotime($event['end_time'])); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Location -->
                        <?php if ($event['location']): ?>
                        <div class="bg-blue-50 rounded-xl p-6 border-l-4 border-blue-600">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Location</p>
                                    <p class="font-bold text-gray-900"><?= htmlspecialchars($event['location']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Cost -->
                        <div class="bg-green-50 rounded-xl p-6 border-l-4 border-green-600">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-tag text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Cost</p>
                                    <p class="font-bold text-gray-900">
                                        <?= !empty($event['cost']) ? htmlspecialchars($event['cost']) : 'Free' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Person -->
                <?php if ($event['contact_person'] || $event['contact_email'] || $event['contact_phone']): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <?php if ($event['contact_person']): ?>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-user-tie text-amber-600 text-xl mr-4"></i>
                            <div>
                                <p class="text-sm text-gray-600">Contact Person</p>
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($event['contact_person']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($event['contact_email']): ?>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-envelope text-amber-600 text-xl mr-4"></i>
                            <a href="mailto:<?= htmlspecialchars($event['contact_email']) ?>" class="text-amber-700 hover:text-amber-900 font-semibold">
                                <?= htmlspecialchars($event['contact_email']) ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php if ($event['contact_phone']): ?>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-amber-600 text-xl mr-4"></i>
                            <a href="tel:<?= htmlspecialchars($event['contact_phone']) ?>" class="text-amber-700 hover:text-amber-900 font-semibold">
                                <?= htmlspecialchars($event['contact_phone']) ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-amber-600 to-yellow-700 rounded-2xl p-8 text-white mb-8 sticky top-24">
                    <?php if ($event['registration_link']): ?>
                    <a href="<?= htmlspecialchars($event['registration_link']) ?>" target="_blank" class="block w-full text-center bg-white text-amber-700 px-6 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all mb-4">
                        <i class="fas fa-user-plus mr-2"></i>
                        Register Now
                    </a>
                    <?php endif; ?>

                    <button onclick="shareEvent()" class="block w-full text-center bg-amber-800 text-white px-6 py-4 rounded-xl font-bold hover:bg-amber-900 transition-all mb-4">
                        <i class="fas fa-share-alt mr-2"></i>
                        Share Event
                    </button>

                    <button onclick="addToCalendar()" class="block w-full text-center bg-transparent border-2 border-white text-white px-6 py-4 rounded-xl font-bold hover:bg-white hover:text-amber-700 transition-all">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Add to Calendar
                    </button>
                </div>

                <!-- Event Stats -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Event Stats</h3>
                    <div class="space-y-3">
                          <div class="flex justify-between">
                            <span class="text-gray-600">Type</span>
                            <span class="font-semibold text-amber-700"><?= htmlspecialchars($event['event_type']) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Featured</span>
                            <span class="font-semibold text-amber-700">
                                <?= $event['is_featured'] ? 'Yes' : 'No' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Events -->
<?php if (!empty($relatedEvents)): ?>
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Related <?= htmlspecialchars($event['event_type']) ?> Events</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($relatedEvents as $related): ?>
            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative overflow-hidden">
                    <?php if ($related['featured_image']): ?>
                    <img src="<?= SITE_URL . '/' . $related['featured_image'] ?>" alt="<?= htmlspecialchars($related['title']) ?>" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center">
                        <i class="fas fa-calendar text-white text-6xl opacity-50"></i>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-white text-amber-700 rounded-xl shadow-lg p-3 text-center">
                        <div class="text-xl font-bold"><?= date('d', strtotime($related['event_date'])) ?></div>
                        <div class="text-xs uppercase"><?= date('M', strtotime($related['event_date'])) ?></div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($related['title']) ?></h3>
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <i class="far fa-calendar mr-2"></i>
                        <?= formatDate($related['event_date'], 'M j, Y') ?>
                    </div>
                    <?php if ($related['location']): ?>
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-amber-600"></i>
                        <?= htmlspecialchars($related['location']) ?>
                    </div>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/pages/event-details.php?id=<?= $related['id'] ?>" class="inline-block text-amber-700 font-semibold hover:text-amber-900 transition">
                        View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($event['title']) ?>',
            text: '<?= htmlspecialchars(substr($event['description'] ?? '', 0, 200)) ?>',
            url: window.location.href
        });
    } else {
        // Fallback: Copy link to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Event link copied to clipboard!');
    }
}

function addToCalendar() {
    // Simple calendar link (you can enhance this)
    const startDate = new Date('<?= $event['event_date'] ?>T<?= $event['start_time'] ?? '09:00' ?>:00');
    const endDate = new Date('<?= $event['event_date'] ?>T<?= $event['end_time'] ?? '10:00' ?>:00');

    const googleCalendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent('<?= htmlspecialchars($event['title']) ?>')}&dates=${startDate.toISOString().replace(/-|:|\.\d\d\d/g, '')}/${endDate.toISOString().replace(/-|:|\.\d\d\d/g, '')}&details=${encodeURIComponent('<?= htmlspecialchars($event['description'] ?? '') ?>')}&location=${encodeURIComponent('<?= htmlspecialchars($event['location'] ?? '') ?>')}`;

    window.open(googleCalendarUrl, '_blank');
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>