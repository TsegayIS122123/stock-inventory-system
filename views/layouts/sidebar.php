<?php if (isset($_SESSION['user_id'])): ?>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-900 text-white w-64 space-y-6 py-7 px-2">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold">TKC-Stock</h1>
                <p class="text-sm text-gray-400">Inventory System</p>
            </div>

            <!-- Navigation -->
            <nav>
                <a href="index.php?action=dashboard" class="block py-2.5 px-4 rounded transition hover:bg-gray-700 <?php echo ($_GET['action'] ?? '') == 'dashboard' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>

                <a href="index.php?action=products" class="block py-2.5 px-4 rounded transition hover:bg-gray-700 <?php echo strpos($_GET['action'] ?? '', 'product') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-box mr-2"></i> Products
                </a>

                <a href="index.php?action=sales" class="block py-2.5 px-4 rounded transition hover:bg-gray-700 <?php echo strpos($_GET['action'] ?? '', 'sale') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-shopping-cart mr-2"></i> Sales
                </a>

                <a href="index.php?action=sales-create" class="block py-2.5 px-4 rounded transition hover:bg-gray-700">
                    <i class="fas fa-cash-register mr-2"></i> POS
                </a>

                <hr class="my-4 border-gray-700">

                <a href="index.php?action=logout" class="block py-2.5 px-4 rounded transition hover:bg-red-600 text-red-400 hover:text-white" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 left-0 w-64 p-4 bg-gray-800">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold"><?php echo strtoupper(substr($_SESSION['full_name'] ?? $_SESSION['username'], 0, 1)); ?></span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold"><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></p>
                        <p class="text-xs text-gray-400"><?php echo ucfirst($_SESSION['role'] ?? 'user'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm py-4 px-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?php echo $page_title ?? 'Dashboard'; ?>
                    </h2>
                    <div class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1"></i> <?php echo date('l, F j, Y'); ?>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
            <?php endif; ?>