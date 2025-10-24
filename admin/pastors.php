<?php
$pageTitle = 'Presbyters Management';
$pageDescription = 'Manage district leadership team';

include 'includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$successMessage = '';
$errorMessage = '';

// Position colors for badges
$positionColors = [
    'senior_pastor' => 'red',
    'assistant_pastor' => 'orange',
    'children_pastor' => 'teal'
];

// Position labels
$positionLabels = [
    'senior_pastor' => 'Senior Pastor',
    'assistant_pastor' => 'Assistant Pastor',
    'children_pastor' => 'Children\'s Pastor'
];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $postAction = $_POST['action'];

        if ($postAction === 'create' || $postAction === 'edit') {
            // Collect form data
            $full_name = trim($_POST['full_name'] ?? '');
            $position = $_POST['position'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $credentials = trim($_POST['credentials'] ?? '');
            $ordination_date = $_POST['ordination_date'] != "" ? $_POST['ordination_date'] : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $display_order = intval($_POST['display_order'] ?? 0);

            // Validation
            if (empty($full_name) || empty($position)) {
                $errorMessage = 'Full name and position are required.';
            } else {
                // Handle photo upload
                $photoPath = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    $uploadResult = uploadFile($_FILES['photo'], 'presbyters', $allowedTypes);

                    if ($uploadResult['success']) {
                        $photoPath = $uploadResult['path'];

                        // Delete old photo if editing
                        if ($postAction === 'edit' && $id) {
                            $oldRecord = fetchOne("SELECT photo FROM pastors WHERE id = ?", [$id], 'i');
                            if ($oldRecord && $oldRecord['photo']) {
                                deleteFile($oldRecord['photo']);
                            }
                        }
                    } else {
                        $errorMessage = $uploadResult['message'];
                    }
                }

                if (empty($errorMessage)) {
                    if ($postAction === 'create') {
                        // Insert new pastor
                        $sql = "INSERT INTO pastors (full_name, position, email, phone, photo, bio, credentials, ordination_date, is_active, display_order)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param('ssssssssii', $full_name, $position, $email, $phone, $photoPath, $bio, $credentials, $ordination_date, $is_active, $display_order);

                        if ($stmt->execute()) {
                            $newId = $db->insert_id;
                            logActivity('create', 'pastors', $newId, "Added pastor: $full_name");
                            $successMessage = 'Pastor added successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to add pastor.';
                        }
                    } else if ($postAction === 'edit' && $id) {
                        // Update existing pastor
                        if ($photoPath) {
                            $sql = "UPDATE pastors SET full_name = ?, position = ?, email = ?, phone = ?, photo = ?, bio = ?, credentials = ?, ordination_date = ?, is_active = ?, display_order = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param('ssssssssiii', $full_name, $position, $email, $phone, $photoPath, $bio, $credentials, $ordination_date, $is_active, $display_order, $id);
                        } else {
                            $sql = "UPDATE pastors SET full_name = ?, position = ?, email = ?, phone = ?, bio = ?, credentials = ?, ordination_date = ?, is_active = ?, display_order = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param('sssssssiis', $full_name, $position, $email, $phone, $bio, $credentials, $ordination_date, $is_active, $display_order, $id);
                        }

                        if ($stmt->execute()) {
                            logActivity('update', 'pastors', $id, "Updated pastor: $full_name");
                            $successMessage = 'Pastor updated successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to update pastor.';
                        }
                    }
                }
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id) {
    $pastor = fetchOne("SELECT * FROM pastors WHERE id = ?", [$id], 'i');
    if ($pastor) {
        // Delete photo if exists
        if ($pastor['photo']) {
            deleteFile($pastor['photo']);
        }

        $sql = "DELETE FROM pastors WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'pastors', $id, "Deleted pastor: " . $pastor['full_name']);
            $successMessage = 'Pastor deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete pastor.';
        }
    }
    $action = 'list';
}

// Get presbyter for editing
$editPastor = null;
if ($action === 'edit' && $id) {
    $editPastor = fetchOne("SELECT * FROM pastors WHERE id = ?", [$id], 'i');
    if (!$editPastor) {
        $errorMessage = 'Pastor not found.';
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
            <h2 class="text-xl font-semibold text-gray-900">All Pastors</h2>
            <a href="?action=create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                <i class="fas fa-plus mr-2"></i> Add Pastor
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pastor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ordination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $pastors = fetchAll("SELECT * FROM pastors ORDER BY display_order ASC, full_name ASC");
                    if (empty($pastors)):
                    ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users-cog text-4xl mb-3 text-gray-300"></i>
                            <p>No pastors found. <a href="?action=create" class="text-blue-600 hover:text-blue-700">Add your first pastor</a></p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($pastors as $pastor): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if ($pastor['photo']): ?>
                                    <img src="<?= SITE_URL . '/' . $pastor['photo'] ?>" alt="<?= htmlspecialchars($pastor['full_name']) ?>" class="w-10 h-10 rounded-full object-cover mr-3">
                                    <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($pastor['full_name']) ?></div>
                                        <?php if ($pastor['credentials']): ?>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($pastor['credentials']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $positionColors[$pastor['position']] ?>-100 text-<?= $positionColors[$pastor['position']] ?>-800">
                                    <?= $positionLabels[$pastor['position']] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($pastor['email']): ?>
                                <div class="text-sm text-gray-900"><i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($pastor['email']) ?></div>
                                <?php endif; ?>
                                <?php if ($pastor['phone']): ?>
                                <div class="text-sm text-gray-500"><i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($pastor['phone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $pastor['ordination_date'] ? formatDate($pastor['ordination_date']) : 'N/A' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($pastor['is_active']): ?>
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
                                <?= $pastor['display_order'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="?action=edit&id=<?= $pastor['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?action=delete&id=<?= $pastor['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete">
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
                <?= $action === 'create' ? 'Add New Presbyter' : 'Edit Presbyter' ?>
            </h2>
        </div>

        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?= $action ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($editPastor['full_name'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Position -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Position <span class="text-red-500">*</span>
                    </label>
                    <select name="position" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Position</option>
                        <?php foreach ($positionLabels as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($editPastor['position'] ?? '') === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($editPastor['email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($editPastor['phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Credentials -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Credentials/Qualifications</label>
                    <input type="text" name="credentials" value="<?= htmlspecialchars($editPastor['credentials'] ?? '') ?>"
                           placeholder="e.g., Rev., Dr., M.Div."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Ordination Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordination Date</label>
                    <input type="date" name="ordination_date" value="<?= $editPastor['ordination_date'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Display Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" name="display_order" value="<?= $editPastor['display_order'] ?? 0 ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                    <?php if ($editPastor && $editPastor['photo']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $editPastor['photo'] ?>" alt="Current photo" class="w-20 h-20 rounded-full object-cover">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG. Max 10MB.</p>
                </div>

                <!-- Bio -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Biography</label>
                    <textarea name="bio" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editPresbyter['bio'] ?? '') ?></textarea>
                </div>

                <!-- Is Active -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?= ($editPastor['is_active'] ?? 1) ? 'checked' : '' ?>
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
                    <i class="fas fa-save mr-2"></i> <?= $action === 'create' ? 'Add Presbyter' : 'Update Presbyter' ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
