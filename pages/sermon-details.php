<?php
$pageTitle = 'Sermon Details';
require_once __DIR__ . '/../includes/header.php';

// Get sermon ID from URL
$sermonId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($sermonId === 0) {
    // Redirect to sermons page if no ID provided
    header('Location: ' . SITE_URL . '/pages/sermons.php');
    exit;
}

// Get sermon details
$sermon = fetchOne("SELECT * FROM sermons WHERE id = ? AND is_active = 1", [$sermonId], 'i');

if (!$sermon) {
    // Sermon not found, redirect
    header('Location: ' . SITE_URL . '/pages/sermons.php');
    exit;
}


// Get related sermons (same category or speaker, different from current)
$relatedSermons = fetchAll("SELECT * FROM sermons WHERE (category = ? OR speaker = ?) AND id != ? AND is_active = 1 ORDER BY sermon_date DESC LIMIT 4", [$sermon['category'], $sermon['speaker'], $sermonId], 'ssi');
?>

<!-- Hero Section with Sermon Image -->
<section class="relative bg-gradient-to-br from-amber-700 via-yellow-700 to-amber-800 text-white overflow-hidden" style="min-height: 60vh;">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <?php if ($sermon['thumbnail']): ?>
        <img src="<?= SITE_URL . '/' . $sermon['thumbnail'] ?>" alt="<?= htmlspecialchars($sermon['title']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
        <img src="https://images.unsplash.com/photo-1507692049790-de58290a4334?w=1920&h=1080&fit=crop&q=80" alt="Sermon" class="w-full h-full object-cover">
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
            <!-- Sermon Badge -->
            <div class="inline-block mb-6 px-6 py-2 bg-white/20 backdrop-blur-xl rounded-full border border-white/30 shadow-2xl">
                <div class="flex items-center justify-center space-x-3">
                    <i class="fas fa-bible text-2xl"></i>
                    <span class="font-semibold tracking-wider uppercase">Sermon</span>
                </div>
            </div>

            <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                <?= htmlspecialchars($sermon['title']) ?>
            </h1>

            <!-- Sermon Info -->
            <div class="flex flex-wrap gap-6 justify-center mb-6">
                <div class="flex items-center">
                    <i class="fas fa-user-tie text-2xl mr-3"></i>
                    <span class="text-xl"><?= htmlspecialchars($sermon['speaker']) ?></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-2xl mr-3"></i>
                    <span class="text-xl"><?= formatDate($sermon['sermon_date'], 'F j, Y') ?></span>
                </div>
            </div>

            <?php if ($sermon['scripture_reference']): ?>
            <div class="flex items-center justify-center">
                <i class="fas fa-book text-2xl mr-3"></i>
                <span class="text-xl"><?= htmlspecialchars($sermon['scripture_reference']) ?></span>
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
            <a href="<?= SITE_URL ?>/pages/sermons.php" class="hover:text-amber-600 transition">Sermons</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-amber-600 font-semibold"><?= htmlspecialchars($sermon['title']) ?></span>
        </div>
    </div>
</section>

<!-- Sermon Player & Content -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Media Player -->
                <div class="mb-12">
                    <div class="bg-gradient-to-br from-amber-600 to-yellow-700 rounded-2xl p-8 shadow-xl">
                        <div class="bg-white rounded-xl p-6 mb-6">
                            <!-- Audio Player -->
                            <?php if ($sermon['audio_file']): ?>
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-headphones text-amber-600 mr-2"></i>
                                    Listen to Sermon
                                </h3>
                                <audio controls class="w-full" controlsList="nodownload">
                                    <source src="<?= SITE_URL . '/' . $sermon['audio_file'] ?>" type="audio/mpeg">
                                    <source src="<?= SITE_URL . '/' . $sermon['audio_file'] ?>" type="audio/wav">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                            <?php endif; ?>

                            <!-- Video Player -->
                            <?php if ($sermon['video_url']): ?>
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-video text-amber-600 mr-2"></i>
                                    Watch Sermon
                                </h3>
                                <div class="aspect-w-16 aspect-h-9 bg-gray-900 rounded-lg overflow-hidden">
                                    <?php if (strpos($sermon['video_url'], 'youtube.com') !== false || strpos($sermon['video_url'], 'youtu.be') !== false): ?>
                                    <!-- YouTube Embed -->
                                    <?php
                                    $videoId = '';
                                    if (strpos($sermon['video_url'], 'youtube.com/watch?v=') !== false) {
                                        parse_str(parse_url($sermon['video_url'], PHP_URL_QUERY), $query);
                                        $videoId = $query['v'] ?? '';
                                    } elseif (strpos($sermon['video_url'], 'youtu.be/') !== false) {
                                        $videoId = substr($sermon['video_url'], strrpos($sermon['video_url'], '/') + 1);
                                    }
                                    ?>
                                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= $videoId ?>" frameborder="0" allowfullscreen class="w-full aspect-video rounded-lg"></iframe>
                                    <?php else: ?>
                                    <!-- Regular Video Link -->
                                    <video controls class="w-full rounded-lg" controlsList="nodownload">
                                        <source src="<?= htmlspecialchars($sermon['video_url']) ?>">
                                        Your browser does not support the video tag.
                                    </video>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- PDF Download -->
                            <?php if ($sermon['pdf_file']): ?>
                            <div>
                                <a href="<?= SITE_URL . '/' . $sermon['pdf_file'] ?>" target="_blank" class="inline-flex items-center px-6 py-3 bg-amber-600 text-white rounded-lg font-semibold hover:bg-amber-700 transition">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Download Sermon Notes
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Media Info -->
                        <div class="grid grid-cols-3 gap-4 text-center text-white">
                            <?php if ($sermon['audio_file']): ?>
                            <div>
                                <i class="fas fa-headphones text-2xl mb-2"></i>
                                <p class="text-sm opacity-90">Audio Available</p>
                            </div>
                            <?php endif; ?>
                            <?php if ($sermon['video_url']): ?>
                            <div>
                                <i class="fas fa-video text-2xl mb-2"></i>
                                <p class="text-sm opacity-90">Video Available</p>
                            </div>
                            <?php endif; ?>
                            <?php if ($sermon['pdf_file']): ?>
                            <div>
                                <i class="fas fa-file-pdf text-2xl mb-2"></i>
                                <p class="text-sm opacity-90">Notes Available</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <?php if ($sermon['description']): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Sermon Overview</h2>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        <?= nl2br(htmlspecialchars($sermon['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Scripture Reference -->
                <?php if ($sermon['scripture_reference']): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Scripture Focus</h2>
                    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl p-8 border-l-4 border-amber-600">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-amber-600 rounded-lg flex items-center justify-center mr-6">
                                <i class="fas fa-book text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($sermon['scripture_reference']) ?></p>
                                <p class="text-gray-600">This sermon explores the depths of God's Word from this passage.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Series Information -->
               
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Sermon Info Card -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4">Sermon Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Date</p>
                            <p class="font-semibold text-gray-900"><?= formatDate($sermon['sermon_date'], 'F j, Y') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Speaker</p>
                            <p class="font-semibold text-gray-900"><?= htmlspecialchars($sermon['speaker']) ?></p>
                        </div>
                        <?php if ($sermon['category']): ?>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Category</p>
                            <p class="font-semibold text-amber-700"><?= htmlspecialchars($sermon['category']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($sermon['scripture_reference']): ?>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Scripture</p>
                            <p class="font-semibold text-gray-900"><?= htmlspecialchars($sermon['scripture_reference']) ?></p>
                        </div>
                        <?php endif; ?>
                       </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <button onclick="shareSermon()" class="w-full px-4 py-3 bg-amber-600 text-white rounded-lg font-semibold hover:bg-amber-700 transition">
                            <i class="fas fa-share-alt mr-2"></i> Share Sermon
                        </button>
                        <button onclick="downloadNotes()" class="w-full px-4 py-3 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">
                            <i class="fas fa-download mr-2"></i> Download Notes
                        </button>
                    </div>
                </div>

                <!-- Featured Badge -->
                <?php if ($sermon['is_featured']): ?>
                <div class="bg-gradient-to-r from-yellow-400 to-amber-500 rounded-xl p-6 text-white text-center">
                    <i class="fas fa-star text-3xl mb-2"></i>
                    <p class="font-bold">Featured Sermon</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Related Sermons -->
<?php if (!empty($relatedSermons)): ?>
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Related Sermons</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($relatedSermons as $related): ?>
            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative overflow-hidden">
                    <?php if ($related['thumbnail']): ?>
                    <img src="<?= SITE_URL . '/' . $related['thumbnail'] ?>" alt="<?= htmlspecialchars($related['title']) ?>" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center">
                        <i class="fas fa-bible text-white text-6xl opacity-50"></i>
                    </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-play text-amber-600 text-lg ml-1"></i>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2"><?= htmlspecialchars($related['title']) ?></h3>
                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <i class="fas fa-user-tie mr-2 text-amber-600"></i>
                        <?= htmlspecialchars($related['speaker']) ?>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <i class="far fa-calendar mr-2"></i>
                        <?= formatDate($related['sermon_date'], 'M j, Y') ?>
                    </div>
                    <a href="<?= SITE_URL ?>/pages/sermon-details.php?id=<?= $related['id'] ?>" class="inline-block text-amber-700 font-semibold hover:text-amber-900 transition">
                        Listen Now <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function shareSermon() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($sermon['title']) ?>',
            text: 'Listen to this sermon by <?= htmlspecialchars($sermon['speaker']) ?>',
            url: window.location.href
        });
    } else {
        // Fallback: Copy link to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Sermon link copied to clipboard!');
    }
}

function downloadNotes() {
    <?php if ($sermon['pdf_file']): ?>
    window.open('<?= SITE_URL . '/' . $sermon['pdf_file'] ?>', '_blank');
    <?php else: ?>
    alert('Sermon notes are not available for this sermon.');
    <?php endif; ?>
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>