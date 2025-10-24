<?php
$pageTitle = 'Site Settings';
$pageDescription = 'Manage website configuration and settings';

include 'includes/header.php';

$db = getDB();
$successMessage = '';
$errorMessage = '';

// Get current settings
$settings = getSiteSettings();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $site_name = trim($_POST['site_name'] ?? '');
    $site_tagline = trim($_POST['site_tagline'] ?? '');
    $site_email = trim($_POST['site_email'] ?? '');
    $site_phone = trim($_POST['site_phone'] ?? '');
    $site_address = trim($_POST['site_address'] ?? '');
    $facebook_url = trim($_POST['facebook_url'] ?? '');
    $instagram_url = trim($_POST['instagram_url'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    // $tiktok_url = trim($_POST['tiktok_url'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $live_stream_url = trim($_POST['live_stream_url'] ?? '');
    $hero_title = trim($_POST['hero_title'] ?? '');
    $hero_subtitle = trim($_POST['hero_subtitle'] ?? '');
    $about_text = trim($_POST['about_text'] ?? '');
    $vision_statement = trim($_POST['vision_statement'] ?? '');
    $mission_statement = trim($_POST['mission_statement'] ?? '');
    
    // Handle file uploads
    $logoPath = null;
    $faviconPath = null;
    $heroImagePath = null;

    // Logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
        $uploadResult = uploadFile($_FILES['logo'], 'settings', $allowedTypes);

        if ($uploadResult['success']) {
            $logoPath = $uploadResult['path'];
            // Delete old logo
            if ($settings && $settings['logo']) {
                deleteFile($settings['logo']);
            }
        } else {
            $errorMessage = 'Logo: ' . $uploadResult['message'];
        }
    }

    // Favicon upload
    if (empty($errorMessage) && isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/jpeg'];
        $uploadResult = uploadFile($_FILES['favicon'], 'settings', $allowedTypes);

        if ($uploadResult['success']) {
            $faviconPath = $uploadResult['path'];
            // Delete old favicon
            if ($settings && $settings['favicon']) {
                deleteFile($settings['favicon']);
            }
        } else {
            $errorMessage = 'Favicon: ' . $uploadResult['message'];
        }
    }

    // Hero Image upload
    if (empty($errorMessage) && isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $uploadResult = uploadFile($_FILES['hero_image'], 'settings', $allowedTypes);

        if ($uploadResult['success']) {
            $heroImagePath = $uploadResult['path'];
            // Delete old hero image
            if ($settings && $settings['hero_image']) {
                deleteFile($settings['hero_image']);
            }
        } else {
            $errorMessage = 'Hero Image: ' . $uploadResult['message'];
        }
    }

    if (empty($errorMessage)) {
        // Build update query
        $updateFields = [
            "site_name = ?",
            "site_tagline = ?",
            "site_email = ?",
            "site_phone = ?",
            "site_address = ?",
            "facebook_url = ?",
            "instagram_url = ?",
            "youtube_url = ?",
            "twitter_url = ?",
            "linkedin_url = ?",
            "live_stream_url = ?",
            "hero_title = ?",
            "hero_subtitle = ?",
            "about_text = ?",
            "vision_statement = ?",
            "mission_statement = ?",
            
        ];

        $params = [
            $site_name,
            $site_tagline,
            $site_email,
            $site_phone,
            $site_address,
            $facebook_url,
            $instagram_url,
            $youtube_url,
            $twitter_url,
            $linkedin_url,
            $live_stream_url,
            $hero_title,
            $hero_subtitle,
            $about_text,
            $vision_statement,
            $mission_statement,
           
        ];

        $types = 'ssssssssssssssss';

        // Add file paths if uploaded
        if ($logoPath) {
            $updateFields[] = "logo = ?";
            $params[] = $logoPath;
            $types .= 's';
        }

        if ($faviconPath) {
            $updateFields[] = "favicon = ?";
            $params[] = $faviconPath;
            $types .= 's';
        }

        if ($heroImagePath) {
            $updateFields[] = "hero_image = ?";
            $params[] = $heroImagePath;
            $types .= 's';
        }

        $params[] = 1; // id = 1 (single row)
        $types .= 'i';

        $sql = "UPDATE site_settings SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            logActivity('update', 'site_settings', 1, "Updated site settings");
            $successMessage = 'Site settings updated successfully!';
            // Refresh settings
            $settings = getSiteSettings();
        } else {
            $errorMessage = 'Failed to update settings.';
        }
    }
}
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

<!-- Settings Form -->
<form method="POST" enctype="multipart/form-data">
    <!-- General Information -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-info-circle mr-2"></i> General Information
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                    <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Site Tagline -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                    <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <?php if ($settings && $settings['logo']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $settings['logo'] ?>" alt="Current logo" class="h-16">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG. Recommended: 200x50px</p>
                </div>

                <!-- Favicon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <?php if ($settings && $settings['favicon']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $settings['favicon'] ?>" alt="Current favicon" class="h-8">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="favicon" accept="image/x-icon,image/png"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">ICO, PNG. Recommended: 32x32px or 64x64px</p>
                </div>

                <!-- About Text -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">About Text</label>
                    <textarea name="about_text" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($settings['about_text'] ?? '') ?></textarea>
                </div>

                <!-- Vision Statement -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vision Statement</label>
                    <textarea name="vision_statement" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($settings['vision_statement'] ?? '') ?></textarea>
                </div>

                <!-- Mission Statement -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mission Statement</label>
                    <textarea name="mission_statement" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($settings['mission_statement'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-phone mr-2"></i> Contact Information
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="site_email" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="site_phone" value="<?= htmlspecialchars($settings['site_phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="site_address" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($settings['site_address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Media -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-share-alt mr-2"></i> Social Media Links
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Facebook -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook URL
                    </label>
                    <input type="url" name="facebook_url" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>"
                           placeholder="https://facebook.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram URL
                    </label>
                    <input type="url" name="instagram_url" value="<?= htmlspecialchars($settings['instagram_url'] ?? '') ?>"
                           placeholder="https://instagram.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- YouTube -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-youtube text-red-600 mr-1"></i> YouTube URL
                    </label>
                    <input type="url" name="youtube_url" value="<?= htmlspecialchars($settings['youtube_url'] ?? '') ?>"
                           placeholder="https://youtube.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Twitter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-twitter text-blue-400 mr-1"></i> Twitter URL
                    </label>
                    <input type="url" name="twitter_url" value="<?= htmlspecialchars($settings['twitter_url'] ?? '') ?>"
                           placeholder="https://twitter.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

               

                <!-- LinkedIn -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-linkedin text-blue-700 mr-1"></i> LinkedIn URL
                    </label>
                    <input type="url" name="linkedin_url" value="<?= htmlspecialchars($settings['linkedin_url'] ?? '') ?>"
                           placeholder="https://linkedin.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Live Stream URL -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-video text-red-600 mr-1"></i> Live Stream URL
                    </label>
                    <input type="url" name="live_stream_url" value="<?= htmlspecialchars($settings['live_stream_url'] ?? '') ?>"
                           placeholder="https://..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-600 to-orange-700">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-image mr-2"></i> Hero Section (Homepage)
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hero Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                    <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Hero Subtitle -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                    <textarea name="hero_subtitle" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?></textarea>
                </div>

                <!-- Hero Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Background Image</label>
                    <?php if ($settings && $settings['hero_image']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $settings['hero_image'] ?>" alt="Current hero image" class="h-40 rounded-lg object-cover">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="hero_image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, WebP. Recommended: 1920x1080px or larger</p>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Submit Button -->
    <div class="flex items-center justify-end">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition text-lg">
            <i class="fas fa-save mr-2"></i> Save All Settings
        </button>
    </div>
</form>

<?php include 'includes/footer.php'; ?>
