<?php
$page_title = 'Login';
require_once BASE_PATH . '/views/layouts/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">TKC-Stock</h1>
            <p class="text-gray-500 mt-2">Smart Inventory & Sales Management System</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 alert">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 alert">
                <?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=do-login">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email or Username</label>
                <input type="text" name="email" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="btn-primary w-full text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray-600">Demo Credentials:</p>
            <p class="text-sm text-gray-500">Email: admin@tkcstock.com / Password: password123</p>
            <p class="text-sm text-gray-500">Email: manager@tkcstock.com / Password: password123</p>
            <p class="text-sm text-gray-500">Email: cashier@tkcstock.com / Password: password123</p>
        </div>

        <div class="text-center mt-4">
            <a href="index.php?action=register" class="text-blue-500 hover:underline">Don't have an account? Register</a>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>