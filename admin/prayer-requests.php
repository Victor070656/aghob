<?php
$pageTitle = 'Prayer Requests Management';
$pageDescription = 'Manage and respond to prayer requests from visitors';

include 'includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? 'all';
$successMessage = '';
$errorMessage = '';

// Handle status update
if ($action === 'update_status' && $id) {
    $newStatus = $_GET['new_status'] ?? '';
    if (in_array($newStatus, ['pending', 'praying', 'answered'])) {
        $sql = "UPDATE prayer_requests SET status = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('si', $newStatus, $id);

        if ($stmt->execute()) {
            $request = fetchOne("SELECT full_name FROM prayer_requests WHERE id = ?", [$id], 'i');
            logActivity('update', 'prayer_requests', $id, "Updated prayer request status to: $newStatus");
            $successMessage = 'Prayer request status updated successfully!';
        } else {
            $errorMessage = 'Failed to update status.';
        }
    }
    $action = 'list';
}

// Handle delete action
if ($action === 'delete' && $id) {
    $request = fetchOne("SELECT * FROM prayer_requests WHERE id = ?", [$id], 'i');
    if ($request) {
        $sql = "DELETE FROM prayer_requests WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'prayer_requests', $id, "Deleted prayer request from: " . $request['full_name']);
            $successMessage = 'Prayer request deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete prayer request.';
        }
    }
    $action = 'list';
}

// Get prayer request for viewing
$viewRequest = null;
if ($action === 'view' && $id) {
    $viewRequest = fetchOne("SELECT * FROM prayer_requests WHERE id = ?", [$id], 'i');
    if (!$viewRequest) {
        $errorMessage = 'Prayer request not found.';
        $action = 'list';
    }
}

// Get statistics
$stats = [
    'all' => fetchOne("SELECT COUNT(*) as count FROM prayer_requests")['count'] ?? 0,
    'pending' => fetchOne("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'pending'")['count'] ?? 0,
    'praying' => fetchOne("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'praying'")['count'] ?? 0,
    'answered' => fetchOne("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'answered'")['count'] ?? 0,
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

<?php if ($action === 'list'): ?>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['all'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-praying-hands text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600"><?= $stats['pending'] ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Praying</p>
                    <p class="text-2xl font-bold text-blue-600"><?= $stats['praying'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hands text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Answered</p>
                    <p class="text-2xl font-bold text-green-600"><?= $stats['answered'] ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- List View -->
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Status Tabs -->
        <div class="border-b border-gray-200">
            <div class="flex space-x-8 px-6">
                <a href="?status=all" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'all' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    All (<?= $stats['all'] ?>)
                </a>
                <a href="?status=pending" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'pending' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    Pending (<?= $stats['pending'] ?>)
                </a>
                <a href="?status=praying" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'praying' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    Praying (<?= $stats['praying'] ?>)
                </a>
                <a href="?status=answered" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'answered' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    Answered (<?= $stats['answered'] ?>)
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name & Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prayer Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Build query based on status filter
                    if ($status === 'all') {
                        $sql = "SELECT * FROM prayer_requests ORDER BY created_at DESC";
                        $requests = fetchAll($sql);
                    } else {
                        $sql = "SELECT * FROM prayer_requests WHERE status = ? ORDER BY created_at DESC";
                        $requests = fetchAll($sql, [$status], 's');
                    }

                    if (empty($requests)):
                    ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-praying-hands text-4xl mb-3 text-gray-300"></i>
                            <p>No prayer requests found.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <?php if ($request['is_anonymous']): ?>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user-secret text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">Anonymous</div>
                                        <div class="text-sm text-gray-500">Identity hidden</div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($request['full_name']) ?></div>
                                        <?php if ($request['email']): ?>
                                        <div class="text-sm text-gray-500"><i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($request['email']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($request['phone']): ?>
                                        <div class="text-sm text-gray-500"><i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($request['phone']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars(substr($request['prayer_request'], 0, 100)) ?><?= strlen($request['prayer_request']) > 100 ? '...' : '' ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($request['status'] === 'pending'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                                <?php elseif ($request['status'] === 'praying'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-hands mr-1"></i> Praying
                                </span>
                                <?php else: ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Answered
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= formatDate($request['created_at'], 'd M Y') ?><br>
                                <span class="text-xs text-gray-400"><?= formatDate($request['created_at'], 'g:i A') ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View -->
                                    <button onclick="viewRequest(<?= $request['id'] ?>)" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Status Actions -->
                                    <?php if ($request['status'] === 'pending'): ?>
                                    <a href="?action=update_status&id=<?= $request['id'] ?>&new_status=praying" class="text-blue-600 hover:text-blue-900" title="Mark as Praying">
                                        <i class="fas fa-hands"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($request['status'] === 'praying'): ?>
                                    <a href="?action=update_status&id=<?= $request['id'] ?>&new_status=answered" class="text-green-600 hover:text-green-900" title="Mark as Answered">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Delete -->
                                    <a href="?action=delete&id=<?= $request['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
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

<?php endif; ?>

<!-- View Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between border-b pb-3 mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Prayer Request Details</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="modalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
function viewRequest(id) {
    // Fetch request details via AJAX
    fetch('ajax/get-prayer-request.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.request;
                let statusBadge = '';

                if (request.status === 'pending') {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i> Pending</span>';
                } else if (request.status === 'praying') {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fas fa-hands mr-1"></i> Praying</span>';
                } else {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i> Answered</span>';
                }

                let contactInfo = '';
                if (request.is_anonymous == 1) {
                    contactInfo = '<div class="bg-gray-100 p-4 rounded-lg mb-4"><p class="text-gray-600"><i class="fas fa-user-secret mr-2"></i>This request was submitted anonymously</p></div>';
                } else {
                    contactInfo = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-gray-900">${request.full_name}</p>
                            </div>
                            ${request.email ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <p class="text-gray-900"><i class="fas fa-envelope mr-1"></i> ${request.email}</p>
                            </div>
                            ` : ''}
                            ${request.phone ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <p class="text-gray-900"><i class="fas fa-phone mr-1"></i> ${request.phone}</p>
                            </div>
                            ` : ''}
                        </div>
                    `;
                }

                const content = `
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        ${statusBadge}
                    </div>

                    ${contactInfo}

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prayer Request</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-line">${request.prayer_request}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-gray-500">
                        <div>
                            <i class="fas fa-calendar mr-1"></i> Submitted: ${request.created_at}
                        </div>
                        ${request.ip_address ? `
                        <div>
                            <i class="fas fa-globe mr-1"></i> IP: ${request.ip_address}
                        </div>
                        ` : ''}
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        ${request.status === 'pending' ? `
                            <a href="?action=update_status&id=${request.id}&new_status=praying" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-hands mr-2"></i> Mark as Praying
                            </a>
                        ` : ''}
                        ${request.status === 'praying' ? `
                            <a href="?action=update_status&id=${request.id}&new_status=answered" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-check-circle mr-2"></i> Mark as Answered
                            </a>
                        ` : ''}
                        <button onclick="closeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                            Close
                        </button>
                    </div>
                `;

                document.getElementById('modalContent').innerHTML = content;
                document.getElementById('viewModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load prayer request details');
        });
}

function closeModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
