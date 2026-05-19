<?php if (isset($_SESSION['user_id'])): ?>
    <nav class="sidebar">
        <div style="flex: 1;">
            <a href="index.php?action=dashboard" class="nav-item <?php echo ($_GET['action'] ?? 'dashboard') == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="index.php?action=products" class="nav-item <?php echo ($_GET['action'] ?? '') == 'products' ? 'active' : ''; ?>">
                <i class="fas fa-boxes"></i>
                <span>Products</span>
            </a>
            <a href="index.php?action=sales" class="nav-item <?php echo ($_GET['action'] ?? '') == 'sales' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Sales</span>
            </a>
            <a href="index.php?action=reports" class="nav-item <?php echo ($_GET['action'] ?? '') == 'reports' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="index.php?action=settings" class="nav-item <?php echo ($_GET['action'] ?? '') == 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>

        <a href="index.php?action=logout" class="nav-item logout-item" onclick="return confirm('Logout?')">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>

    <main class="main-content">
    <?php endif; ?>