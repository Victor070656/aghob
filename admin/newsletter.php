<?php
$pageTitle = 'Newsletter Subscriptions';
$pageDescription = 'Manage newsletter subscribers and export mailing lists';

include 'includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$search = $_GET['search'] ?? '';
$successMessage = '';
$errorMessage = '';

// Handle export to CSV
if ($action === 'export') {
    $sql = "SELECT email, name, is_active, subscribed_at, unsubscribed_at FROM newsletter_subscriptions ORDER BY subscribed_at DESC";
    $subscriptions = fetchAll($sql);

    if (!empty($subscriptions)) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="newsletter-subscriptions-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Add CSV headers
        fputcsv($output, ['Email', 'Name', 'Status', 'Subscribed At', 'Unsubscribed At']);

        // Add data rows
        foreach ($subscriptions as $sub) {
            fputcsv($output, [
                $sub['email'],
                $sub['name'] ?? '',
                $sub['is_active'] ? 'Active' : 'Inactive',
                $sub['subscribed_at'],
                $sub['unsubscribed_at'] ?? ''
            ]);
        }

        fclose($output);
        logActivity('export', 'newsletter_subscriptions', null, "Exported newsletter subscriptions to CSV");
        exit;
    } else {
        $errorMessage = 'No subscriptions to export.';
    }
    $action = 'list';
}

// Handle toggle active status
if ($action === 'toggle_status' && $id) {
    $subscription = fetchOne("SELECT * FROM newsletter_subscriptions WHERE id = ?", [$id], 'i');
    if ($subscription) {
        $newStatus = $subscription['is_active'] ? 0 : 1;
        $unsubscribedAt = $newStatus ? null : date('Y-m-d H:i:s');

        $sql = "UPDATE newsletter_subscriptions SET is_active = ?, unsubscribed_at = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('isi', $newStatus, $unsubscribedAt, $id);

        if ($stmt->execute()) {
            $statusText = $newStatus ? 'resubscribed' : 'unsubscribed';
            logActivity('update', 'newsletter_subscriptions', $id, "User $statusText: " . $subscription['email']);
            $successMessage = 'Subscription status updated successfully!';
        } else {
            $errorMessage = 'Failed to update subscription status.';
        }
    }
    $action = 'list';
}

// Handle delete action
if ($action === 'delete' && $id) {
    $subscription = fetchOne("SELECT * FROM newsletter_subscriptions WHERE id = ?", [$id], 'i');
    if ($subscription) {
        $sql = "DELETE FROM newsletter_subscriptions WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'newsletter_subscriptions', $id, "Deleted subscription: " . $subscription['email']);
            $successMessage = 'Subscription deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete subscription.';
        }
    }
    $action = 'list';
}

// Get statistics
$stats = [
    'total' => fetchOne("SELECT COUNT(*) as count FROM newsletter_subscriptions")['count'] ?? 0,
    'active' => fetchOne("SELECT COUNT(*) as count FROM newsletter_subscriptions WHERE is_active = 1")['count'] ?? 0,
    'inactive' => fetchOne("SELECT COUNT(*) as count FROM newsletter_subscriptions WHERE is_active = 0")['count'] ?? 0,
];
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

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Subscribers</p>
                <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Active Subscriptions</p>
                <p class="text-2xl font-bold text-green-600"><?= $stats['active'] ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Unsubscribed</p>
                <p class="text-2xl font-bold text-gray-600"><?= $stats['inactive'] ?></p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-slash text-gray-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- List View -->
<div class="bg-white rounded-xl shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center space-x-4 flex-1">
            <h2 class="text-xl font-semibold text-gray-900">All Subscriptions</h2>

            <!-- Search -->
            <form method="GET" class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                           placeholder="Search by email..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </form>
        </div>

        <a href="?action=export" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
            <i class="fas fa-download mr-2"></i> Export CSV
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unsubscribed</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                // Build query with search
                if (!empty($search)) {
                    $searchTerm = "%$search%";
                    $sql = "SELECT * FROM newsletter_subscriptions WHERE email LIKE ? OR name LIKE ? ORDER BY subscribed_at DESC";
                    $subscriptions = fetchAll($sql, [$searchTerm, $searchTerm], 'ss');
                } else {
                    $sql = "SELECT * FROM newsletter_subscriptions ORDER BY subscribed_at DESC";
                    $subscriptions = fetchAll($sql);
                }

                if (empty($subscriptions)):
                ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-envelope-open-text text-4xl mb-3 text-gray-300"></i>
                        <p><?= !empty($search) ? 'No subscriptions match your search.' : 'No newsletter subscriptions yet.' ?></p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($subscriptions as $subscription): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($subscription['email']) ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?= htmlspecialchars($subscription['name'] ?? 'N/A') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($subscription['is_active']): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                            <?php else: ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= formatDate($subscription['subscribed_at'], 'd M Y') ?><br>
                            <span class="text-xs text-gray-400"><?= formatDate($subscription['subscribed_at'], 'g:i A') ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $subscription['unsubscribed_at'] ? formatDate($subscription['unsubscribed_at'], 'd M Y') : 'N/A' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <!-- Toggle Status -->
                                <?php if ($subscription['is_active']): ?>
                                <a href="?action=toggle_status&id=<?= $subscription['id'] ?>" class="text-yellow-600 hover:text-yellow-900" title="Unsubscribe">
                                    <i class="fas fa-user-slash"></i> Unsubscribe
                                </a>
                                <?php else: ?>
                                <a href="?action=toggle_status&id=<?= $subscription['id'] ?>" class="text-green-600 hover:text-green-900" title="Resubscribe">
                                    <i class="fas fa-user-check"></i> Resubscribe
                                </a>
                                <?php endif; ?>

                                <!-- Delete -->
                                <a href="?action=delete&id=<?= $subscription['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
