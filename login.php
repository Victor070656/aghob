<?php
require_once 'config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $db = getDB();
        $sql = "SELECT * FROM admins WHERE username = ? AND is_active = 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($admin = $result->fetch_assoc()) {
            // Direct password comparison (no hashing)
            if ($password === $admin['password']) {
                // Set session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_role'] = $admin['role'];

                // Update last login
                $updateSql = "UPDATE admins SET last_login = NOW() WHERE id = ?";
                $updateStmt = $db->prepare($updateSql);
                $updateStmt->bind_param('i', $admin['id']);
                $updateStmt->execute();

                // Log activity
                logActivity('login', 'admins', $admin['id'], 'Admin logged in');

                header('Location: admin/index.php');
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Please enter both username and password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AGC Northern Rivers District</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 text-white rounded-full mb-4">
                <i class="fas fa-church text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">AGC Northern Rivers</h1>
            <p class="text-gray-600">District Administration Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sign In</h2>

            <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-6">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i> Username
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter your username"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i> Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter your password"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="index.php" class="text-sm text-blue-600 hover:text-blue-700">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Website
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-600">
            <p>&copy; <?= date('Y') ?> Assemblies of God Church House of Bread</p>
        </div>
    </div>
</body>
</html>
