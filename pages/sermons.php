<?php
$pageTitle = 'Sermons';
require_once __DIR__ . '/../includes/header.php';

// Get filter parameters
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterSpeaker = isset($_GET['speaker']) ? trim($_GET['speaker']) : '';
$filterCategory = isset($_GET['category']) ? trim($_GET['category']) : '';
$filterSeries = isset($_GET['series']) ? trim($_GET['series']) : '';

// Build query
$query = "SELECT * FROM sermons WHERE 1=1";
$params = [];
$types = '';

if (!empty($searchTerm)) {
    $query .= " AND (title LIKE ? OR scripture_reference LIKE ? OR speaker LIKE ?)";
    $searchParam = '%' . $searchTerm . '%';
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= 'sss';
}

if (!empty($filterSpeaker)) {
    $query .= " AND speaker = ?";
    $params[] = $filterSpeaker;
    $types .= 's';
}

if (!empty($filterCategory)) {
    $query .= " AND category = ?";
    $params[] = $filterCategory;
    $types .= 's';
}

if (!empty($filterSeries)) {
    $query .= " AND series = ?";
    $params[] = $filterSeries;
    $types .= 's';
}

$query .= " ORDER BY sermon_date DESC, created_at DESC";

// Fetch sermons
$sermons = fetchAll($query, $params, $types);

// Get unique speakers, categories, and series for filters
$speakers = fetchAll("SELECT DISTINCT speaker FROM sermons WHERE speaker IS NOT NULL AND speaker != '' ORDER BY speaker");
$categories = fetchAll("SELECT DISTINCT category FROM sermons WHERE category IS NOT NULL AND category != '' ORDER BY category");
// $series = fetchAll("SELECT DISTINCT series FROM sermons WHERE series IS NOT NULL AND series != '' ORDER BY series");
?>

<!-- Hero Section with Image -->
<section class="relative bg-gradient-to-br from-amber-700 via-yellow-700 to-amber-800 text-white overflow-hidden" style="min-height: 60vh;">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="../images/06.jpg"
             alt="Bible Study"
             class="w-full h-full object-cover ">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-900/70 via-yellow-900/55 to-yellow-800/80"></div>
    </div>

    <!-- Animated Pattern Overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl animate-[float_6s_ease-in-out_infinite]"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-[float_8s_ease-in-out_infinite]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 flex items-center min-h-[60vh]">
        <div class="text-center w-full">
            <!-- Icon Badge -->
            <div class="inline-block mb-6 px-8 py-3 bg-white/10 backdrop-blur-xl rounded-full border border-white/30 shadow-2xl">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-bible text-2xl"></i>
                    <span class="font-semibold tracking-wider uppercase">Sermons & Teachings</span>
                </div>
            </div>

            <h1 class="text-5xl md:text-7xl font-black mb-8 leading-tight">
                <span class="block">Sermon</span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-500">Library</span>
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed mb-8">
                Listen to and download our collection of sermons and teachings
            </p>

            <!-- Quick Stats -->
            <div class="flex flex-wrap gap-6 justify-center">
                <div class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
                    <div class="text-3xl font-bold"><?= count($sermons) ?></div>
                    <div class="text-sm opacity-75">Total Sermons</div>
                </div>
                <div class="px-6 py-3 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20">
                    <div class="text-3xl font-bold"><?= count($speakers) ?></div>
                    <div class="text-sm opacity-75">Speakers</div>
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
            <span class="text-amber-700 font-semibold">Sermons</span>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-8 bg-gradient-to-b from-gray-50 to-white sticky top-0 z-10 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="" class="space-y-4">
            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text"
                           name="search"
                           value="<?= htmlspecialchars($searchTerm) ?>"
                           placeholder="Search by title, scripture, or speaker..."
                           class="w-full px-6 py-4 pr-12 rounded-xl border-2 border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent shadow-sm">
                    <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-10 py-4 rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transition shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i> Speaker
                    </label>
                    <select name="speaker"
                            onchange="this.form.submit()"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent bg-white">
                        <option value="">All Speakers</option>
                        <?php foreach ($speakers as $speaker): ?>
                        <option value="<?= htmlspecialchars($speaker['speaker']) ?>"
                                <?= $filterSpeaker === $speaker['speaker'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($speaker['speaker']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i> Category
                    </label>
                    <select name="category"
                            onchange="this.form.submit()"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-amber-600 focus:border-transparent bg-white">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category']) ?>"
                                <?= $filterCategory === $cat['category'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <?php if (!empty($searchTerm) || !empty($filterSpeaker) || !empty($filterCategory)): ?>
                <div class="flex items-end">
                    <a href="<?= SITE_URL ?>/pages/sermons.php"
                       class="w-full text-center px-4 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 transition">
                        <i class="fas fa-times mr-2"></i> Clear All
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </form>

        <?php if (!empty($searchTerm) || !empty($filterSpeaker) || !empty($filterCategory) ): ?>
        <div class="mt-4 text-gray-700">
            <strong class="text-amber-700"><?= count($sermons) ?></strong> sermon(s) found
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Sermons Grid -->
<section class="py-24 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($sermons)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $sermonImages = [
                'https://images.unsplash.com/photo-1490730141103-6cac27aaab94?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1507692049790-de58290a4334?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1501612780327-45045538702b?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop&q=80',
                'https://images.unsplash.com/photo-1496449903678-68ddcb189a24?w=600&h=400&fit=crop&q=80'
            ];
            $serIdx = 0;
            foreach ($sermons as $sermon): ?>
            <a href="<?= SITE_URL ?>/pages/sermon-details.php?id=<?= $sermon['id'] ?>" class="group bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 block">
                <!-- Thumbnail with Play Button Overlay -->
                <div class="relative overflow-hidden">
                    <?php if ($sermon['thumbnail']): ?>
                    <img src="<?= SITE_URL . '/' . htmlspecialchars($sermon['thumbnail']) ?>"
                         alt="<?= htmlspecialchars($sermon['title']) ?>"
                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="relative w-full h-56">
                        <img src="<?= $sermonImages[$serIdx % 6] ?>"
                             alt="Sermon"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/70 to-pink-600/70 flex items-center justify-center">
                            <i class="fas fa-bible text-8xl text-white opacity-30 group-hover:scale-110 transition-transform duration-500"></i>
                        </div>
                    </div>
                    <?php endif; $serIdx++; ?>

                    <!-- Play Button Overlay -->
                    <?php if ($sermon['audio_file'] || $sermon['video_url']): ?>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-xl transform scale-90 group-hover:scale-100 transition-transform">
                            <i class="fas fa-play text-amber-700 text-2xl ml-1"></i>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Media Indicators -->
                    <div class="absolute top-3 right-3 flex gap-2">
                        <?php if ($sermon['audio_file']): ?>
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-xs font-bold shadow-md">
                            <i class="fas fa-headphones mr-1"></i> Audio
                        </span>
                        <?php endif; ?>
                        <?php if ($sermon['video_url']): ?>
                        <span class="px-3 py-1 bg-red-600 text-white rounded-full text-xs font-bold shadow-md">
                            <i class="fas fa-video mr-1"></i> Video
                        </span>
                        <?php endif; ?>
                        <?php if ($sermon['pdf_file']): ?>
                        <span class="px-3 py-1 bg-green-600 text-white rounded-full text-xs font-bold shadow-md">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Category & Series Badges -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php if ($sermon['category']): ?>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold">
                            <?= htmlspecialchars($sermon['category']) ?>
                        </span>
                        <?php endif; ?>
                        
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                        <?= htmlspecialchars($sermon['title']) ?>
                    </h3>

                    <!-- Details -->
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-user text-amber-700 w-5 mr-2"></i>
                            <span class="font-medium"><?= htmlspecialchars($sermon['speaker']) ?></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-amber-700 w-5 mr-2"></i>
                            <span><?= formatDate($sermon['sermon_date'], 'M j, Y') ?></span>
                        </div>
                        <?php if ($sermon['scripture_reference']): ?>
                        <div class="flex items-center">
                            <i class="fas fa-book-open text-amber-700 w-5 mr-2"></i>
                            <span class="font-medium"><?= htmlspecialchars($sermon['scripture_reference']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($sermon['duration']) && $sermon['duration']): ?>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-amber-700 w-5 mr-2"></i>
                            <span><?= htmlspecialchars($sermon['duration']) ?> min</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <?php if ($sermon['description']): ?>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3 leading-relaxed">
                        <?= htmlspecialchars($sermon['description']) ?>
                    </p>
                    <?php endif; ?>

                    <!-- Audio Player -->
                    <?php if ($sermon['audio_file']): ?>
                    <div class="mb-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-purple-800">
                                <i class="fas fa-headphones mr-1"></i> Audio Sermon
                            </span>
                        </div>
                        <audio controls class="w-full" controlsList="nodownload">
                            <source src="<?= SITE_URL . '/' . htmlspecialchars($sermon['audio_file']) ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    <?php endif; ?>

                    <!-- Video Embed -->
                    <?php if ($sermon['video_url']): ?>
                    <?php
                    // Extract YouTube video ID
                    $videoUrl = $sermon['video_url'];
                    $videoId = '';
                    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $videoUrl, $match)) {
                        $videoId = $match[1];
                    } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $videoUrl, $match)) {
                        $videoId = $match[1];
                    } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $videoUrl, $match)) {
                        $videoId = $match[1];
                    }
                    ?>
                    <?php if ($videoId): ?>
                    <div class="mb-4">
                        <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-xl shadow-lg">
                            <iframe
                                class="absolute top-0 left-0 w-full h-full"
                                src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="<?= htmlspecialchars($sermon['video_url']) ?>"
                       target="_blank"
                       class="block mb-4 text-center bg-gradient-to-r from-red-600 to-pink-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-red-700 hover:to-pink-700 transition shadow-md">
                        <i class="fab fa-youtube mr-2"></i> Watch Video
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>

                    <!-- Download Buttons -->
                    <div class="flex gap-2 pt-4 border-t border-gray-100">
                        <?php if ($sermon['audio_file']): ?>
                        <a href="<?= SITE_URL . '/' . htmlspecialchars($sermon['audio_file']) ?>"
                           download
                           class="flex-1 text-center bg-blue-600 text-white px-4 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-1"></i> Audio
                        </a>
                        <?php endif; ?>
                        <?php if ($sermon['pdf_file']): ?>
                        <a href="<?= SITE_URL . '/' . htmlspecialchars($sermon['pdf_file']) ?>"
                           download
                           class="flex-1 text-center bg-red-600 text-white px-4 py-3 rounded-xl text-sm font-bold hover:bg-red-700 transition shadow-md hover:shadow-lg">
                            <i class="fas fa-file-pdf mr-1"></i> Notes
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-24">
            <div class="bg-white rounded-3xl shadow-xl p-16 max-w-2xl mx-auto">
                <i class="fas fa-search text-8xl text-gray-300 mb-6"></i>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">No Sermons Found</h3>
                <p class="text-lg text-gray-600 mb-8">
                    <?php if (!empty($searchTerm) || !empty($filterSpeaker) || !empty($filterCategory)): ?>
                        Try adjusting your search or filter criteria
                    <?php else: ?>
                        Check back soon for new sermons and teachings
                    <?php endif; ?>
                </p>
                <?php if (!empty($searchTerm) || !empty($filterSpeaker) || !empty($filterCategory)): ?>
                <a href="<?= SITE_URL ?>/pages/sermons.php"
                   class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                    <i class="fas fa-bible mr-2"></i> View All Sermons
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
