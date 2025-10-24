<?php
$pageTitle = 'Contact Messages Management';
$pageDescription = 'Manage and respond to contact form submissions';

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
    if (in_array($newStatus, ['new', 'read', 'responded', 'archived'])) {
        $sql = "UPDATE contact_submissions SET status = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('si', $newStatus, $id);

        if ($stmt->execute()) {
            $message = fetchOne("SELECT name FROM contact_submissions WHERE id = ?", [$id], 'i');
            logActivity('update', 'contact_submissions', $id, "Updated contact message status to: $newStatus");
            $successMessage = 'Contact message status updated successfully!';
        } else {
            $errorMessage = 'Failed to update status.';
        }
    }
    $action = 'list';
}

// Handle delete action
if ($action === 'delete' && $id) {
    $message = fetchOne("SELECT * FROM contact_submissions WHERE id = ?", [$id], 'i');
    if ($message) {
        $sql = "DELETE FROM contact_submissions WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            logActivity('delete', 'contact_submissions', $id, "Deleted contact message from: " . $message['name']);
            $successMessage = 'Contact message deleted successfully!';
        } else {
            $errorMessage = 'Failed to delete message.';
        }
    }
    $action = 'list';
}

// Get message for viewing
$viewMessage = null;
if ($action === 'view' && $id) {
    $viewMessage = fetchOne("SELECT * FROM contact_submissions WHERE id = ?", [$id], 'i');
    if (!$viewMessage) {
        $errorMessage = 'Contact message not found.';
        $action = 'list';
    } else {
        // Auto-mark as read if it's new
        if ($viewMessage['status'] === 'new') {
            $sql = "UPDATE contact_submissions SET status = 'read' WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $viewMessage['status'] = 'read';
            logActivity('update', 'contact_submissions', $id, "Marked contact message as read");
        }
    }
}

// Get statistics
$stats = [
    'all' => fetchOne("SELECT COUNT(*) as count FROM contact_submissions")['count'] ?? 0,
    'new' => fetchOne("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'new'")['count'] ?? 0,
    'read' => fetchOne("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'read'")['count'] ?? 0,
    'responded' => fetchOne("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'responded'")['count'] ?? 0,
    'archived' => fetchOne("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'archived'")['count'] ?? 0,
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
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Messages</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['all'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">New</p>
                    <p class="text-2xl font-bold text-red-600"><?= $stats['new'] ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope-open text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Read</p>
                    <p class="text-2xl font-bold text-blue-600"><?= $stats['read'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-envelope-open-text text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Responded</p>
                    <p class="text-2xl font-bold text-green-600"><?= $stats['responded'] ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-reply text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Archived</p>
                    <p class="text-2xl font-bold text-gray-600"><?= $stats['archived'] ?></p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-archive text-gray-600 text-xl"></i>
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
                <a href="?status=new" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'new' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    New (<?= $stats['new'] ?>)
                </a>
                <a href="?status=read" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'read' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    Read (<?= $stats['read'] ?>)
                </a>
                <a href="?status=responded" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'responded' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    Responded (<?= $stats['responded'] ?>)
                </a>
                <a href="?status=archived" class="py-4 px-1 border-b-2 font-medium text-sm <?= $status === 'archived' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                    Archived (<?= $stats['archived'] ?>)
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name & Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Build query based on status filter
                    if ($status === 'all') {
                        $sql = "SELECT * FROM contact_submissions ORDER BY created_at DESC";
                        $messages = fetchAll($sql);
                    } else {
                        $sql = "SELECT * FROM contact_submissions WHERE status = ? ORDER BY created_at DESC";
                        $messages = fetchAll($sql, [$status], 's');
                    }

                    if (empty($messages)):
                    ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-envelope text-4xl mb-3 text-gray-300"></i>
                            <p>No contact messages found.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                        <tr class="hover:bg-gray-50 <?= $message['status'] === 'new' ? 'bg-blue-50' : '' ?>">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 flex items-center">
                                            <?= htmlspecialchars($message['name']) ?>
                                            <?php if ($message['status'] === 'new'): ?>
                                            <span class="ml-2 w-2 h-2 bg-red-500 rounded-full"></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($message['email']) ?></div>
                                        <?php if ($message['phone']): ?>
                                        <div class="text-sm text-gray-500"><i class="fas fa-phone mr-1"></i> <?= htmlspecialchars($message['phone']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($message['subject'] ?? 'No subject') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars(substr($message['message'], 0, 80)) ?><?= strlen($message['message']) > 80 ? '...' : '' ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($message['status'] === 'new'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i> New
                                </span>
                                <?php elseif ($message['status'] === 'read'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-envelope-open-text mr-1"></i> Read
                                </span>
                                <?php elseif ($message['status'] === 'responded'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-reply mr-1"></i> Responded
                                </span>
                                <?php else: ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-archive mr-1"></i> Archived
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= formatDate($message['created_at'], 'd M Y') ?><br>
                                <span class="text-xs text-gray-400"><?= formatDate($message['created_at'], 'g:i A') ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View -->
                                    <button onclick="viewMessage(<?= $message['id'] ?>)" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Reply -->
                                    <a href="mailto:<?= htmlspecialchars($message['email']) ?>?subject=Re: <?= htmlspecialchars($message['subject'] ?? 'Your message') ?>" class="text-green-600 hover:text-green-900" title="Reply via Email">
                                        <i class="fas fa-reply"></i>
                                    </a>

                                    <!-- Status Actions -->
                                    <?php if ($message['status'] !== 'read'): ?>
                                    <a href="?action=update_status&id=<?= $message['id'] ?>&new_status=read" class="text-blue-600 hover:text-blue-900" title="Mark as Read">
                                        <i class="fas fa-envelope-open"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($message['status'] !== 'responded'): ?>
                                    <a href="?action=update_status&id=<?= $message['id'] ?>&new_status=responded" class="text-green-600 hover:text-green-900" title="Mark as Responded">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($message['status'] !== 'archived'): ?>
                                    <a href="?action=update_status&id=<?= $message['id'] ?>&new_status=archived" class="text-gray-600 hover:text-gray-900" title="Archive">
                                        <i class="fas fa-archive"></i>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Delete -->
                                    <a href="?action=delete&id=<?= $message['id'] ?>" class="text-red-600 hover:text-red-900 confirm-delete" title="Delete">
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
            <h3 class="text-xl font-semibold text-gray-900">Contact Message Details</h3>
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
function viewMessage(id) {
    // Fetch message details via AJAX
    fetch('ajax/get-contact-message.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const msg = data.message;
                let statusBadge = '';

                if (msg.status === 'new') {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"><i class="fas fa-exclamation-circle mr-1"></i> New</span>';
                } else if (msg.status === 'read') {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fas fa-envelope-open-text mr-1"></i> Read</span>';
                } else if (msg.status === 'responded') {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-reply mr-1"></i> Responded</span>';
                } else {
                    statusBadge = '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fas fa-archive mr-1"></i> Archived</span>';
                }

                const content = `
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        ${statusBadge}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <p class="text-gray-900">${msg.name}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900"><i class="fas fa-envelope mr-1"></i> ${msg.email}</p>
                        </div>
                        ${msg.phone ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-gray-900"><i class="fas fa-phone mr-1"></i> ${msg.phone}</p>
                        </div>
                        ` : ''}
                        ${msg.subject ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <p class="text-gray-900">${msg.subject}</p>
                        </div>
                        ` : ''}
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-line">${msg.message}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-gray-500">
                        <div>
                            <i class="fas fa-calendar mr-1"></i> Received: ${msg.created_at}
                        </div>
                        ${msg.ip_address ? `
                        <div>
                            <i class="fas fa-globe mr-1"></i> IP: ${msg.ip_address}
                        </div>
                        ` : ''}
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <a href="mailto:${msg.email}?subject=Re: ${msg.subject || 'Your message'}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-reply mr-2"></i> Reply via Email
                        </a>
                        ${msg.status !== 'responded' ? `
                            <a href="?action=update_status&id=${msg.id}&new_status=responded" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-check mr-2"></i> Mark as Responded
                            </a>
                        ` : ''}
                        ${msg.status !== 'archived' ? `
                            <a href="?action=update_status&id=${msg.id}&new_status=archived" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                <i class="fas fa-archive mr-2"></i> Archive
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
            alert('Failed to load message details');
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
