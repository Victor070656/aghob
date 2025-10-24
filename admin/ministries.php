<?php
$pageTitle = 'Ministries Management';
$pageDescription = 'Manage church ministries and departments';

include 'includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$successMessage = '';
$errorMessage = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $postAction = $_POST['action'];

        if ($postAction === 'create' || $postAction === 'edit') {
            // Collect form data
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $leader_name = trim($_POST['leader_name'] ?? '');
            $leader_phone = trim($_POST['leader_phone'] ?? '');
            $leader_email = trim($_POST['leader_email'] ?? '');
            $meeting_day = trim($_POST['meeting_day'] ?? '');
            $meeting_time = trim($_POST['meeting_time'] ?? '');
            $meeting_location = trim($_POST['meeting_location'] ?? '');
            $activities = trim($_POST['activities'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $display_order = intval($_POST['display_order'] ?? 0);

            // Auto-generate slug if empty
            if (empty($slug)) {
                $slug = generateSlug($name);
            }

            // Validation
            if (empty($name)) {
                $errorMessage = 'Ministry name is required.';
            } else {
                // Check for duplicate slug
                $checkSlug = fetchOne("SELECT id FROM ministries WHERE slug = ? AND id != ?", [$slug, $id ?? 0], 'si');
                if ($checkSlug) {
                    $slug = $slug . '-' . time();
                }

                // Handle image upload
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                    $uploadResult = uploadFile($_FILES['image'], 'ministries', $allowedTypes);

                    if ($uploadResult['success']) {
                        $imagePath = $uploadResult['path'];

                        // Delete old image if editing
                        if ($postAction === 'edit' && $id) {
                            $oldRecord = fetchOne("SELECT image FROM ministries WHERE id = ?", [$id], 'i');
                            if ($oldRecord && $oldRecord['image']) {
                                deleteFile($oldRecord['image']);
                            }
                        }
                    } else {
                        $errorMessage = $uploadResult['message'];
                    }
                }

                if (empty($errorMessage)) {
                    if ($postAction === 'create') {
                        // Insert new ministry
                        $sql = "INSERT INTO ministries (name, slug, description, leader_name, leader_phone, leader_email, meeting_day, meeting_time, meeting_location, activities, image, is_active, display_order)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param('sssssssssssii', $name, $slug, $description, $leader_name, $leader_phone, $leader_email, $meeting_day, $meeting_time, $meeting_location, $activities, $imagePath, $is_active, $display_order);

                        if ($stmt->execute()) {
                            $newId = $db->insert_id;
                            logActivity('create', 'ministries', $newId, "Added ministry: $name");
                            $successMessage = 'Ministry added successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to add ministry.';
                        }
                    } else if ($postAction === 'edit' && $id) {
                        // Update existing ministry
                        if ($imagePath) {
                            $sql = "UPDATE ministries SET name = ?, slug = ?, description = ?, leader_name = ?, leader_phone = ?, leader_email = ?, meeting_day = ?, meeting_time = ?, meeting_location = ?, activities = ?, image = ?, is_active = ?, display_order = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param('sssssssssssiis', $name, $slug, $description, $leader_name, $leader_phone, $leader_email, $meeting_day, $meeting_time, $meeting_location, $activities, $imagePath, $is_active, $display_order, $id);
                        } else {
                            $sql = "UPDATE ministries SET name = ?, slug = ?, description = ?, leader_name = ?, leader_phone = ?, leader_email = ?, meeting_day = ?, meeting_time = ?, meeting_location = ?, activities = ?, is_active = ?, display_order = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param('ssssssssssiis', $name, $slug, $description, $leader_name, $leader_phone, $leader_email, $meeting_day, $meeting_time, $meeting_location, $activities, $is_active, $display_order, $id);
                        }

                        if ($stmt->execute()) {
                            logActivity('update', 'ministries', $id, "Updated ministry: $name");
                            $successMessage = 'Ministry updated successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to update ministry.';
                        }
                    }
                }
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id) {
    $ministry = fetchOne("SELECT * FROM ministries WHERE id = ?", [$id], 'i');
    if ($ministry) {
        // Delete image if exists
        if ($ministry['image']) {
            deleteFile($ministry['image']);
        }

        $sql = "DELETE FROM ministries WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'ministries', $id, "Deleted ministry: " . $ministry['name']);
            $successMessage = 'Ministry deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete ministry.';
        }
    }
    $action = 'list';
}

// Get ministry for editing
$editMinistry = null;
if ($action === 'edit' && $id) {
    $editMinistry = fetchOne("SELECT * FROM ministries WHERE id = ?", [$id], 'i');
    if (!$editMinistry) {
        $errorMessage = 'Ministry not found.';
        $action = 'list';
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

<?php if ($action === 'list'): ?>
    <!-- List View -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">All Ministries</h2>
            <a href="?action=create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                <i class="fas fa-plus mr-2"></i> Add Ministry
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ministry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leader</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meeting Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $ministries = fetchAll("SELECT * FROM ministries ORDER BY display_order ASC, name ASC");
                    if (empty($ministries)):
                    ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-hands-helping text-4xl mb-3 text-gray-300"></i>
                            <p>No ministries found. <a href="?action=create" class="text-blue-600 hover:text-blue-700">Add your first ministry</a></p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($ministries as $ministry): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if ($ministry['image']): ?>
                                    <img src="<?= SITE_URL . '/' . $ministry['image'] ?>" alt="<?= htmlspecialchars($ministry['name']) ?>" class="w-16 h-16 rounded-lg object-cover mr-3">
                                    <?php else: ?>
                                    <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-hands-helping text-orange-600 text-2xl"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($ministry['name']) ?></div>
                                        <?php if ($ministry['description']): ?>
                                        <div class="text-sm text-gray-500 max-w-md truncate">
                                            <?= htmlspecialchars(substr($ministry['description'], 0, 80)) ?>...
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($ministry['leader_name']): ?>
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-user mr-1"></i> <?= htmlspecialchars($ministry['leader_name']) ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($ministry['leader_phone']): ?>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($ministry['leader_phone']) ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($ministry['leader_email']): ?>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($ministry['leader_email']) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($ministry['meeting_day']): ?>
                                <div class="text-sm text-gray-900">
                                    <i class="far fa-calendar mr-1"></i> <?= htmlspecialchars($ministry['meeting_day']) ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($ministry['meeting_time']): ?>
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i> <?= htmlspecialchars($ministry['meeting_time']) ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($ministry['meeting_location']): ?>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($ministry['meeting_location']) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($ministry['is_active']): ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                                <?php else: ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $ministry['display_order'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="?action=edit&id=<?= $ministry['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?action=delete&id=<?= $ministry['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete">
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
                <?= $action === 'create' ? 'Add New Ministry' : 'Edit Ministry' ?>
            </h2>
        </div>

        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?= $action ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ministry Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ministry Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="<?= htmlspecialchars($editMinistry['name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (URL-friendly name)
                    </label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($editMinistry['slug'] ?? '') ?>"
                           placeholder="Leave empty to auto-generate"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editMinistry['description'] ?? '') ?></textarea>
                </div>

                <!-- Leader Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leader Name</label>
                    <input type="text" name="leader_name" value="<?= htmlspecialchars($editMinistry['leader_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Leader Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leader Phone</label>
                    <input type="text" name="leader_phone" value="<?= htmlspecialchars($editMinistry['leader_phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Leader Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Leader Email</label>
                    <input type="email" name="leader_email" value="<?= htmlspecialchars($editMinistry['leader_email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Meeting Day -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Day</label>
                    <input type="text" name="meeting_day" value="<?= htmlspecialchars($editMinistry['meeting_day'] ?? '') ?>"
                           placeholder="e.g., Every Sunday, Wednesdays"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Meeting Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Time</label>
                    <input type="text" name="meeting_time" value="<?= htmlspecialchars($editMinistry['meeting_time'] ?? '') ?>"
                           placeholder="e.g., 6:00 PM - 8:00 PM"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Meeting Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Location</label>
                    <input type="text" name="meeting_location" value="<?= htmlspecialchars($editMinistry['meeting_location'] ?? '') ?>"
                           placeholder="e.g., Church Hall, Room 101"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Display Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="<?= $editMinistry['display_order'] ?? 0 ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                </div>

                <!-- Activities -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activities</label>
                    <textarea name="activities" rows="4"
                              placeholder="Describe the ministry's activities and programs..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editMinistry['activities'] ?? '') ?></textarea>
                </div>

                <!-- Featured Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                    <?php if ($editMinistry && $editMinistry['image']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $editMinistry['image'] ?>" alt="Current image" class="h-32 rounded-lg object-cover">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, WebP. Max 10MB.</p>
                </div>

                <!-- Is Active -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?= ($editMinistry['is_active'] ?? 1) ? 'checked' : '' ?>
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
                    <i class="fas fa-save mr-2"></i> <?= $action === 'create' ? 'Add Ministry' : 'Update Ministry' ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
