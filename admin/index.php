<?php
$pageTitle = 'Dashboard';
$pageDescription = 'Overview of your district administration';

include 'includes/header.php';

// Get statistics
$db = getDB();

// Count pastors
$pastorsCount = fetchOne("SELECT COUNT(*) as count FROM pastors WHERE is_active = 1")['count'] ?? 0;

// Count events (upcoming)
$upcomingEventsCount = fetchOne("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE() AND is_active = 1")['count'] ?? 0;

// Count sermons
$sermonsCount = fetchOne("SELECT COUNT(*) as count FROM sermons WHERE is_active = 1")['count'] ?? 0;

// Count ministries
$ministriesCount = fetchOne("SELECT COUNT(*) as count FROM ministries WHERE is_active = 1")['count'] ?? 0;

// Count prayer requests (pending)
$pendingPrayersCount = fetchOne("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'pending'")['count'] ?? 0;

// Count contact messages (new)
$newMessagesCount = fetchOne("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'new'")['count'] ?? 0;

// Get recent events
$recentEvents = fetchAll("SELECT * FROM events WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5");

// Get recent sermons
$recentSermons = fetchAll("SELECT * FROM sermons WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5");

// Get recent prayer requests
$recentPrayers = fetchAll("SELECT * FROM prayer_requests ORDER BY created_at DESC LIMIT 5");
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Presbyters Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Pastors</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $pastorsCount ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users-cog text-blue-600 text-xl"></i>
            </div>
        </div>
        <a href="presbyters.php" class="text-sm text-blue-600 hover:text-blue-700 mt-3 inline-block">
            Manage <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Upcoming Events Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Upcoming Events</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $upcomingEventsCount ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
            </div>
        </div>
        <a href="events.php" class="text-sm text-green-600 hover:text-green-700 mt-3 inline-block">
            Manage <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Sermons Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Sermons</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $sermonsCount ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-microphone text-purple-600 text-xl"></i>
            </div>
        </div>
        <a href="sermons.php" class="text-sm text-purple-600 hover:text-purple-700 mt-3 inline-block">
            Manage <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- Ministries Card -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Ministries</p>
                <h3 class="text-3xl font-bold text-gray-900"><?= $ministriesCount ?></h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-hands-helping text-orange-600 text-xl"></i>
            </div>
        </div>
        <a href="ministries.php" class="text-sm text-orange-600 hover:text-orange-700 mt-3 inline-block">
            Manage <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Pending Prayer Requests -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-200 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-praying-hands text-yellow-700"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Pending Prayer Requests</h3>
                    <p class="text-sm text-gray-600"><?= $pendingPrayersCount ?> waiting for response</p>
                </div>
            </div>
            <a href="prayer-requests.php" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Review
            </a>
        </div>
    </div>

    <!-- New Contact Messages -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-envelope text-blue-700"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">New Contact Messages</h3>
                    <p class="text-sm text-gray-600"><?= $newMessagesCount ?> unread messages</p>
                </div>
            </div>
            <a href="contact-messages.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Review
            </a>
        </div>
    </div>
</div>

<!-- Recent Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Events -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i> Recent Events
            </h3>
        </div>
        <div class="p-6">
            <?php if (empty($recentEvents)): ?>
            <p class="text-gray-500 text-center py-8">No events yet. <a href="events.php" class="text-green-600 hover:text-green-700">Create one</a></p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentEvents as $event): ?>
                <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 truncate"><?= htmlspecialchars($event['title']) ?></h4>
                        <p class="text-sm text-gray-600">
                            <i class="far fa-clock mr-1"></i> <?= formatDate($event['event_date'], 'd M Y') ?>
                        </p>
                    </div>
                    <span class="px-2 py-1 bg-<?= $event['is_featured'] ? 'yellow' : 'gray' ?>-100 text-<?= $event['is_featured'] ? 'yellow' : 'gray' ?>-700 text-xs rounded-full">
                        <?= ucfirst($event['event_type']) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="events.php" class="block text-center text-green-600 hover:text-green-700 mt-4 text-sm font-medium">
                View All Events <i class="fas fa-arrow-right ml-1"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Sermons -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-microphone mr-2"></i> Recent Sermons
            </h3>
        </div>
        <div class="p-6">
            <?php if (empty($recentSermons)): ?>
            <p class="text-gray-500 text-center py-8">No sermons yet. <a href="sermons.php" class="text-purple-600 hover:text-purple-700">Upload one</a></p>
            <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($recentSermons as $sermon): ?>
                <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-bible text-purple-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 truncate"><?= htmlspecialchars($sermon['title']) ?></h4>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-user mr-1"></i> <?= htmlspecialchars($sermon['speaker']) ?>
                        </p>
                    </div>
                    <?php if ($sermon['is_featured']): ?>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                        <i class="fas fa-star"></i> Featured
                    </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="sermons.php" class="block text-center text-purple-600 hover:text-purple-700 mt-4 text-sm font-medium">
                View All Sermons <i class="fas fa-arrow-right ml-1"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
