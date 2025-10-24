<?php
$pageTitle = 'Events Management';
$pageDescription = 'Manage church events and activities';

include 'includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$tab = $_GET['tab'] ?? 'upcoming';
$successMessage = '';
$errorMessage = '';

// Event type colors
$eventTypeColors = [
    'conference' => 'purple',
    'seminar' => 'blue',
    'workshop' => 'indigo',
    'outreach' => 'green',
    'celebration' => 'yellow',
    'training' => 'orange',
    'youth' => 'pink',
    'worship' => 'red',
    'prayer' => 'teal',
    'other' => 'gray'
];

// Event types
$eventTypes = [
    'conference' => 'Conference',
    'seminar' => 'Seminar',
    'workshop' => 'Workshop',
    'outreach' => 'Outreach',
    'celebration' => 'Celebration',
    'training' => 'Training',
    'youth' => 'Youth Event',
    'worship' => 'Worship Service',
    'prayer' => 'Prayer Meeting',
    'other' => 'Other'
];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $postAction = $_POST['action'];

        if ($postAction === 'create' || $postAction === 'edit') {
            // Collect form data
            $title = trim($_POST['title'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $event_type = $_POST['event_type'] ?? 'other';
            $event_date = $_POST['event_date'] ?? '';
            $event_time = $_POST['event_time'] ? $_POST['event_time'] : null;
            $end_date = $_POST['end_date'] ? $_POST['end_date'] : null;
            $end_time = $_POST['end_time'] ? $_POST['end_time'] : null;
            $location = trim($_POST['location'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $contact_person = trim($_POST['contact_person'] ?? '');
            $contact_phone = trim($_POST['contact_phone'] ?? '');
            $contact_email = trim($_POST['contact_email'] ?? '');
            $registration_required = isset($_POST['registration_required']) ? 1 : 0;
            $registration_link = trim($_POST['registration_link'] ?? '');
            $max_attendees = $_POST['max_attendees'] ? intval($_POST['max_attendees']) : null;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            // Auto-generate slug if empty
            if (empty($slug)) {
                $slug = generateSlug($title);
            }

            // Validation
            if (empty($title) || empty($event_date)) {
                $errorMessage = 'Title and event date are required.';
            } else {
                // Check for duplicate slug
                $checkSlug = fetchOne("SELECT id FROM events WHERE slug = ? AND id != ?", [$slug, $id ?? 0], 'si');
                if ($checkSlug) {
                    $slug = $slug . '-' . time();
                }

                // Handle image upload
                $imagePath = null;
                if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                    $uploadResult = uploadFile($_FILES['featured_image'], 'events', $allowedTypes);

                    if ($uploadResult['success']) {
                        $imagePath = $uploadResult['path'];

                        // Delete old image if editing
                        if ($postAction === 'edit' && $id) {
                            $oldRecord = fetchOne("SELECT featured_image FROM events WHERE id = ?", [$id], 'i');
                            if ($oldRecord && $oldRecord['featured_image']) {
                                deleteFile($oldRecord['featured_image']);
                            }
                        }
                    } else {
                        $errorMessage = $uploadResult['message'];
                    }
                }

                if (empty($errorMessage)) {
                    $currentAdminId = $_SESSION['admin_id'];

                    if ($postAction === 'create') {
                        // Insert new event
                        $sql = "INSERT INTO events (title, slug, description, event_type, event_date, event_time, end_date, end_time, location, address, featured_image, contact_person, contact_phone, contact_email, registration_required, registration_link, max_attendees, is_featured, is_active, created_by)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param('ssssssssssssssisiiii', $title, $slug, $description, $event_type, $event_date, $event_time, $end_date, $end_time, $location, $address, $imagePath, $contact_person, $contact_phone, $contact_email, $registration_required, $registration_link, $max_attendees, $is_featured, $is_active, $currentAdminId);

                        if ($stmt->execute()) {
                            $newId = $db->insert_id;
                            logActivity('create', 'events', $newId, "Added event: $title");
                            $successMessage = 'Event added successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to add event.';
                        }
                    } else if ($postAction === 'edit' && $id) {
                        // Update existing event
                        if ($imagePath) {
                            $sql = "UPDATE events SET title = ?, slug = ?, description = ?, event_type = ?, event_date = ?, event_time = ?, end_date = ?, end_time = ?, location = ?, address = ?, featured_image = ?, contact_person = ?, contact_phone = ?, contact_email = ?, registration_required = ?, registration_link = ?, max_attendees = ?, is_featured = ?, is_active = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param('ssssssssssssssisiiii', $title, $slug, $description, $event_type, $event_date, $event_time, $end_date, $end_time, $location, $address, $imagePath, $contact_person, $contact_phone, $contact_email, $registration_required, $registration_link, $max_attendees, $is_featured, $is_active, $id);
                        } else {
                            $sql = "UPDATE events SET title = ?, slug = ?, description = ?, event_type = ?, event_date = ?, event_time = ?, end_date = ?, end_time = ?, location = ?, address = ?, contact_person = ?, contact_phone = ?, contact_email = ?, registration_required = ?, registration_link = ?, max_attendees = ?, is_featured = ?, is_active = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param('sssssssssssssisiiii', $title, $slug, $description, $event_type, $event_date, $event_time, $end_date, $end_time, $location, $address, $contact_person, $contact_phone, $contact_email, $registration_required, $registration_link, $max_attendees, $is_featured, $is_active, $id);
                        }

                        if ($stmt->execute()) {
                            logActivity('update', 'events', $id, "Updated event: $title");
                            $successMessage = 'Event updated successfully!';
                            $action = 'list';
                        } else {
                            $errorMessage = 'Failed to update event.';
                        }
                    }
                }
            }
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id) {
    $event = fetchOne("SELECT * FROM events WHERE id = ?", [$id], 'i');
    if ($event) {
        // Delete image if exists
        if ($event['featured_image']) {
            deleteFile($event['featured_image']);
        }

        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'events', $id, "Deleted event: " . $event['title']);
            $successMessage = 'Event deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete event.';
        }
    }
    $action = 'list';
}

// Get event for editing
$editEvent = null;
if ($action === 'edit' && $id) {
    $editEvent = fetchOne("SELECT * FROM events WHERE id = ?", [$id], 'i');
    if (!$editEvent) {
        $errorMessage = 'Event not found.';
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
    <!-- List View with Tabs -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex space-x-4">
                <a href="?action=list&tab=upcoming" class="px-4 py-2 rounded-lg font-medium transition <?= $tab === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' ?>">
                    <i class="fas fa-calendar-check mr-2"></i> Upcoming Events
                </a>
                <a href="?action=list&tab=past" class="px-4 py-2 rounded-lg font-medium transition <?= $tab === 'past' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' ?>">
                    <i class="fas fa-calendar-times mr-2"></i> Past Events
                </a>
            </div>
            <a href="?action=create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                <i class="fas fa-plus mr-2"></i> Add Event
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    if ($tab === 'upcoming') {
                        $events = fetchAll("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
                    } else {
                        $events = fetchAll("SELECT * FROM events WHERE event_date < CURDATE() ORDER BY event_date DESC");
                    }

                    if (empty($events)):
                    ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-calendar-alt text-4xl mb-3 text-gray-300"></i>
                            <p>No <?= $tab ?> events found. <a href="?action=create" class="text-blue-600 hover:text-blue-700">Create one</a></p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if ($event['featured_image']): ?>
                                    <img src="<?= SITE_URL . '/' . $event['featured_image'] ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="w-16 h-16 rounded-lg object-cover mr-3">
                                    <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar text-gray-400 text-2xl"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($event['title']) ?></div>
                                        <?php if ($event['is_featured']): ?>
                                        <span class="inline-flex items-center text-xs text-yellow-700">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $eventTypeColors[$event['event_type']] ?>-100 text-<?= $eventTypeColors[$event['event_type']] ?>-800">
                                    <?= $eventTypes[$event['event_type']] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="far fa-calendar mr-1"></i> <?= formatDate($event['event_date'], 'd M Y') ?>
                                </div>
                                <?php if ($event['event_time']): ?>
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-clock mr-1"></i> <?= date('g:i A', strtotime($event['event_time'])) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($event['location'] ?? 'TBA') ?></div>
                                <?php if ($event['registration_required']): ?>
                                <div class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i> Registration Required
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($event['is_active']): ?>
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
                                <a href="?action=edit&id=<?= $event['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?action=delete&id=<?= $event['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete">
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
                <?= $action === 'create' ? 'Add New Event' : 'Edit Event' ?>
            </h2>
        </div>

        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="<?= $action ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Event Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="<?= htmlspecialchars($editEvent['title'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (URL-friendly name)
                    </label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($editEvent['slug'] ?? '') ?>"
                           placeholder="Leave empty to auto-generate"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Event Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Event Type <span class="text-red-500">*</span>
                    </label>
                    <select name="event_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <?php foreach ($eventTypes as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($editEvent['event_type'] ?? 'other') === $value ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Event Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Event Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="event_date" value="<?= $editEvent['event_date'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <!-- Event Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Time</label>
                    <input type="time" name="event_time" value="<?= $editEvent['event_time'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                    <input type="date" name="end_date" value="<?= $editEvent['end_date'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time (Optional)</label>
                    <input type="time" name="end_time" value="<?= $editEvent['end_time'] ?? '' ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($editEvent['location'] ?? '') ?>"
                           placeholder="e.g., Main Church Hall"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editEvent['address'] ?? '') ?></textarea>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($editEvent['description'] ?? '') ?></textarea>
                </div>

                <!-- Contact Person -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                    <input type="text" name="contact_person" value="<?= htmlspecialchars($editEvent['contact_person'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="text" name="contact_phone" value="<?= htmlspecialchars($editEvent['contact_phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" name="contact_email" value="<?= htmlspecialchars($editEvent['contact_email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Registration Link -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Link</label>
                    <input type="url" name="registration_link" value="<?= htmlspecialchars($editEvent['registration_link'] ?? '') ?>"
                           placeholder="https://"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Max Attendees -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Attendees</label>
                    <input type="number" name="max_attendees" value="<?= $editEvent['max_attendees'] ?? '' ?>"
                           placeholder="Leave empty for unlimited"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Featured Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                    <?php if ($editEvent && $editEvent['featured_image']): ?>
                    <div class="mb-2">
                        <img src="<?= SITE_URL . '/' . $editEvent['featured_image'] ?>" alt="Current image" class="h-32 rounded-lg object-cover">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="featured_image" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, WebP. Max 10MB.</p>
                </div>

                <!-- Checkboxes -->
                <div class="md:col-span-2 space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="registration_required" value="1" <?= ($editEvent['registration_required'] ?? 0) ? 'checked' : '' ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Registration Required</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" <?= ($editEvent['is_featured'] ?? 0) ? 'checked' : '' ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Event</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?= ($editEvent['is_active'] ?? 1) ? 'checked' : '' ?>
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
                    <i class="fas fa-save mr-2"></i> <?= $action === 'create' ? 'Add Event' : 'Update Event' ?>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
