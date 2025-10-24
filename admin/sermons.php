<?php
$pageTitle = 'Sermons Management';
$pageDescription = 'Manage sermons and media content';

include 'includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$search = $_GET['search'] ?? '';
$filterSpeaker = $_GET['speaker'] ?? '';
$filterCategory = $_GET['category'] ?? '';
$successMessage = '';
$errorMessage = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $postAction = $_POST['action'];

        if ($postAction === 'create' || $postAction === 'edit') {
            // Collect form data
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $speaker = trim($_POST['speaker'] ?? '');
            $sermon_date = $_POST['sermon_date'] ?? '';
            $scripture_reference = trim($_POST['scripture_reference'] ?? '');
            $series_name = trim($_POST['series_name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $duration = $_POST['duration'] ? intval($_POST['duration']) : null;
            $video_url = trim($_POST['video_url'] ?? '');
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            // Auto-generate slug if empty
            if (empty($slug)) {
                $slug = generateSlug($title);
            }

            // Validation
            if (empty($title) || empty($speaker) || empty($sermon_date)) {
                $errorMessage = 'Title, speaker, and sermon date are required.';
            } else {
                // Check for duplicate slug
                $checkSlug = fetchOne("SELECT id FROM sermons WHERE slug = ? AND id != ?", [$slug, $id ?? 0], 'si');
                if ($checkSlug) {
                    $slug = $slug . '-' . time();
                }

                // Handle file uploads
                $audioPath = null;
                $audioSize = null;
                $pdfPath = null;
                $pdfSize = null;
                $thumbnailPath = null;

                // Audio file upload
                if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
                    $allowedAudioTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-m4a'];
                    $uploadResult = uploadFile($_FILES['audio_file'], 'sermons/audio', $allowedAudioTypes);

                    if ($uploadResult['success']) {
                        $audioPath = $uploadResult['path'];
                        $audioSize = $uploadResult['size'];

                        // Delete old audio if editing
                        if ($postAction === 'edit' && $id) {
                            $oldRecord = fetchOne("SELECT audio_file FROM sermons WHERE id = ?", [$id], 'i');
                            if ($oldRecord && $oldRecord['audio_file']) {
                                deleteFile($oldRecord['audio_file']);
                            }
                        }
                    } else {
                        $errorMessage = 'Audio: ' . $uploadResult['message'];
                    }
                }

                // PDF file upload
                if (empty($errorMessage) && isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                    $allowedPdfTypes = ['application/pdf'];
                    $uploadResult = uploadFile($_FILES['pdf_file'], 'sermons/pdf', $allowedPdfTypes);

                    if ($uploadResult['success']) {
                        $pdfPath = $uploadResult['path'];
                        $pdfSize = $uploadResult['size'];

                        // Delete old PDF if editing
                        if ($postAction === 'edit' && $id) {
                            $oldRecord = fetchOne("SELECT pdf_file FROM sermons WHERE id = ?", [$id], 'i');
                            if ($oldRecord && $oldRecord['pdf_file']) {
                                deleteFile($oldRecord['pdf_file']);
                            }
                        }
                    } else {
                        $errorMessage = 'PDF: ' . $uploadResult['message'];
                    }
                }

                // Thumbnail upload
                if (empty($errorMessage) && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    $uploadResult = uploadFile($_FILES['thumbnail'], 'sermons/thumbnails', $allowedImageTypes);

                    if ($uploadResult['success']) {
                        $thumbnailPath = $uploadResult['path'];

                        // Delete old thumbnail if editing
                        if ($postAction === 'edit' && $id) {
                            $oldRecord = fetchOne("SELECT thumbnail FROM sermons WHERE id = ?", [$id], 'i');
                            if ($oldRecord && $oldRecord['thumbnail']) {
                                deleteFile($oldRecord['thumbnail']);
                            }
                        }
                    } else {
                        $errorMessage = 'Thumbnail: ' . $uploadResult['message'];
                    }
                }

                if (empty($errorMessage)) {
                    $currentAdminId = $_SESSION['admin_id'];

                    if ($postAction === 'create') {
                        // Insert new sermon
                        $sql = "INSERT INTO sermons (title, slug, description, speaker, sermon_date, scripture_reference, series_name, category, duration, audio_file, audio_size, video_url, pdf_file, pdf_size, thumbnail, is_featured, is_active, created_by)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param('ssssssssisisisiiii', $title, $slug, $description, $speaker, $sermon_date, $scripture_reference, $series_name, $category, $duration, $audioPath, $audioSize, $video_url, $pdfPath, $pdfSize, $thumbnailPath, $is_featured, $is_active, $currentAdminId);

                        if ($stmt->execute()) {
                            $newId = $db->insert_id;
                            logActivity('create', 'sermons', $newId, "Added sermon: $title");
                            $successMessage = 'Sermon added successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to add sermon.';
                        }
                    } else if ($postAction === 'edit' && $id) {
                        // Build update query based on uploaded files
                        $updateFields = [];
                        $params = [];
                        $types = '';

                        $updateFields[] = "title = ?";
                        $params[] = $title;
                        $types .= 's';

                        $updateFields[] = "slug = ?";
                        $params[] = $slug;
                        $types .= 's';

                        $updateFields[] = "description = ?";
                        $params[] = $description;
                        $types .= 's';

                        $updateFields[] = "speaker = ?";
                        $params[] = $speaker;
                        $types .= 's';

                        $updateFields[] = "sermon_date = ?";
                        $params[] = $sermon_date;
                        $types .= 's';

                        $updateFields[] = "scripture_reference = ?";
                        $params[] = $scripture_reference;
                        $types .= 's';

                        $updateFields[] = "series_name = ?";
                        $params[] = $series_name;
                        $types .= 's';

                        $updateFields[] = "category = ?";
                        $params[] = $category;
                        $types .= 's';

                        $updateFields[] = "duration = ?";
                        $params[] = $duration;
                        $types .= 'i';

                        if ($audioPath) {
                            $updateFields[] = "audio_file = ?";
                            $params[] = $audioPath;
                            $types .= 's';

                            $updateFields[] = "audio_size = ?";
                            $params[] = $audioSize;
                            $types .= 'i';
                        }

                        $updateFields[] = "video_url = ?";
                        $params[] = $video_url;
                        $types .= 's';

                        if ($pdfPath) {
                            $updateFields[] = "pdf_file = ?";
                            $params[] = $pdfPath;
                            $types .= 's';

                            $updateFields[] = "pdf_size = ?";
                            $params[] = $pdfSize;
                            $types .= 'i';
                        }

                        if ($thumbnailPath) {
                            $updateFields[] = "thumbnail = ?";
                            $params[] = $thumbnailPath;
                            $types .= 's';
                        }

                        $updateFields[] = "is_featured = ?";
                        $params[] = $is_featured;
                        $types .= 'i';

                        $updateFields[] = "is_active = ?";
                        $params[] = $is_active;
                        $types .= 'i';

                        $params[] = $id;
                        $types .= 'i';

                        $sql = "UPDATE sermons SET " . implode(", ", $updateFields) . " WHERE id = ?";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param($types, ...$params);

                        if ($stmt->execute()) {
                            logActivity('update', 'sermons', $id, "Updated sermon: $title");
                            $successMessage = 'Sermon updated successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to update sermon.';
                        }
                    }
                }
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id) {
    $sermon = fetchOne("SELECT * FROM sermons WHERE id = ?", [$id], 'i');
    if ($sermon) {
        // Delete files if they exist
        if ($sermon['audio_file']) deleteFile($sermon['audio_file']);
        if ($sermon['pdf_file']) deleteFile($sermon['pdf_file']);
        if ($sermon['thumbnail']) deleteFile($sermon['thumbnail']);

        $sql = "DELETE FROM sermons WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'sermons', $id, "Deleted sermon: " . $sermon['title']);
            $successMessage = 'Sermon deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete sermon.';
        }
    }
    $action = 'list';
}

// Get sermon for editing
$editSermon = null;
if ($action === 'edit' && $id) {
    $editSermon = fetchOne("SELECT * FROM sermons WHERE id = ?", [$id], 'i');
    if (!$editSermon) {
        $errorMessage = 'Sermon not found.';
        $action = 'list';
    }
}

// Get unique speakers and categories for filters
$speakers = fetchAll("SELECT DISTINCT speaker FROM sermons WHERE speaker IS NOT NULL AND speaker != '' ORDER BY speaker");
$categories = fetchAll("SELECT DISTINCT category FROM sermons WHERE category IS NOT NULL AND category != '' ORDER BY category");
?>

<!-- Success/Error Messages -->
<?php if ($successMessage): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 auto-hide">
    <i class="fas fa-check-circle mr-2"></i> <?= $successMessage ?>
</div>
<?php endif; ?>

<?php if ($errorMessage): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 auto-hide">
    <i class="fas fa-exclamation-circle mr-2"></i> <?= $errorMessage ?>
</div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="action" value="list">

            <div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                       placeholder="Search sermons..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <select name="speaker" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Speakers</option>
                    <?php foreach ($speakers as $s): ?>
                    <option value="<?= htmlspecialchars($s['speaker']) ?>" <?= $filterSpeaker === $s['speaker'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['speaker']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= htmlspecialchars($c['category']) ?>" <?= $filterCategory === $c['category'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['category']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="?action=list" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- List View -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">All Sermons</h2>
            <a href="?action=create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                <i class="fas fa-plus mr-2"></i> Add Sermon
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sermon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speaker</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Build query with filters
                    $whereClauses = [];
                    $params = [];
                    $types = '';

                    if ($search) {
                        $whereClauses[] = "(title LIKE ? OR description LIKE ? OR scripture_reference LIKE ?)";
                        $searchParam = "%$search%";
                        $params[] = $searchParam;
                        $params[] = $searchParam;
                        $params[] = $searchParam;
                        $types .= 'sss';
                    }

                    if ($filterSpeaker) {
                        $whereClauses[] = "speaker = ?";
                        $params[] = $filterSpeaker;
                        $types .= 's';
                    }

                    if ($filterCategory) {
                        $whereClauses[] = "category = ?";
                        $params[] = $filterCategory;
                        $types .= 's';
                    }

                    $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
                    $sermons = fetchAll("SELECT * FROM sermons $whereSQL ORDER BY sermon_date DESC", $params, $types);

                    if (empty($sermons)):
                    ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-microphone text-4xl mb-3 text-gray-300"></i>
                            <p>No sermons found. <a href="?action=create" class="text-blue-600 hover:text-blue-700">Upload your first sermon</a></p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($sermons as $sermon): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if ($sermon['thumbnail']): ?>
                                    <img src="<?= SITE_URL . '/' . $sermon['thumbnail'] ?>" alt="<?= htmlspecialchars($sermon['title']) ?>" class="w-16 h-16 rounded-lg object-cover mr-3">
                                    <?php else: ?>
                                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-bible text-purple-600 text-2xl"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($sermon['title']) ?></div>
                                        <?php if ($sermon['scripture_reference']): ?>
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-book mr-1"></i> <?= htmlspecialchars($sermon['scripture_reference']) ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($sermon['is_featured']): ?>
                                        <span class="inline-flex items-center text-xs text-yellow-700 mt-1">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($sermon['speaker']) ?></div>
                                <?php if ($sermon['series_name']): ?>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($sermon['series_name']) ?></div>
                                <?php endif; ?>
                                <?php if ($sermon['category']): ?>
                                <span class="inline-flex mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?= htmlspecialchars($sermon['category']) ?>
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= formatDate($sermon['sermon_date'], 'd M Y') ?>
                                <?php if ($sermon['duration']): ?>
                                <div class="text-xs text-gray-400">
                                    <i class="far fa-clock mr-1"></i> <?= $sermon['duration'] ?> min
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1 text-xs">
                                    <?php if ($sermon['audio_file']): ?>
                                    <span class="text-green-600">
                                        <i class="fas fa-volume-up mr-1"></i> Audio (<?= formatFileSize($sermon['audio_size']) ?>)
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($sermon['video_url']): ?>
                                    <span class="text-red-600">
                                        <i class="fas fa-video mr-1"></i> Video
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($sermon['pdf_file']): ?>
                                    <span class="text-blue-600">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF (<?= formatFileSize($sermon['pdf_size']) ?>)
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($sermon['is_active']): ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                <?php else: ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="?action=edit&id=<?= $sermon['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?action=delete&id=<?= $sermon['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($action === 'create' || $action === 'edit'): ?>
    <!-- Create/Edit Form -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= $action === 'create' ? 'Add New Sermon' : 'Edit Sermon' ?>
            </h2>
        </div>

        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?= $action ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sermon Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="<?= htmlspecialchars($editSermon['title'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (URL-friendly name)
                    </label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($editSermon['slug'] ?? '') ?>"
                           placeholder="Leave empty to auto-generate"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Speaker -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Speaker <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="speaker" value="<?= htmlspecialchars($editSermon['speaker'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Sermon Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sermon Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="sermon_date" value="<?= $editSermon['sermon_date'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Scripture Reference -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Scripture Reference</label>
                    <input type="text" name="scripture_reference" value="<?= htmlspecialchars($editSermon['scripture_reference'] ?? '') ?>"
                           placeholder="e.g., John 3:16-17"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Series Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Series Name</label>
                    <input type="text" name="series_name" value="<?= htmlspecialchars($editSermon['series_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($editSermon['category'] ?? '') ?>"
                           placeholder="e.g., Sunday Service, Bible Study"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input type="number" name="duration" value="<?= $editSermon['duration'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editSermon['description'] ?? '') ?></textarea>
                </div>

                <!-- Audio File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Audio File</label>
                    <?php if ($editSermon && $editSermon['audio_file']): ?>
                    <div class="mb-2 text-sm text-gray-600">
                        <i class="fas fa-volume-up mr-1"></i> Current: <?= basename($editSermon['audio_file']) ?>
                        (<?= formatFileSize($editSermon['audio_size']) ?>)
                    </div>
                    <?php endif; ?>
                    <input type="file" name="audio_file" accept="audio/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">MP3, WAV, M4A. Max 10MB.</p>
                </div>

                <!-- Video URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                    <input type="url" name="video_url" value="<?= htmlspecialchars($editSermon['video_url'] ?? '') ?>"
                           placeholder="https://youtube.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- PDF File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PDF Notes/Outline</label>
                    <?php if ($editSermon && $editSermon['pdf_file']): ?>
                    <div class="mb-2 text-sm text-gray-600">
                        <i class="fas fa-file-pdf mr-1"></i> Current: <?= basename($editSermon['pdf_file']) ?>
                        (<?= formatFileSize($editSermon['pdf_size']) ?>)
                    </div>
                    <?php endif; ?>
                    <input type="file" name="pdf_file" accept="application/pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">PDF only. Max 10MB.</p>
                </div>

                <!-- Thumbnail Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Image</label>
                    <?php if ($editSermon && $editSermon['thumbnail']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $editSermon['thumbnail'] ?>" alt="Current thumbnail" class="h-24 rounded-lg object-cover">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG. Max 10MB.</p>
                </div>

                <!-- Checkboxes -->
                <div class="md:col-span-2 space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" <?= ($editSermon['is_featured'] ?? 0) ? 'checked' : '' ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Sermon</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?= ($editSermon['is_active'] ?? 1) ? 'checked' : '' ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active (visible on website)</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
                <a href="?action=list" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    <i class="fas fa-save mr-2"></i> <?= $action === 'create' ? 'Add Sermon' : 'Update Sermon' ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
