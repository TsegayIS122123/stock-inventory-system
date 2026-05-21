<?php
$page_title = 'Dashboard';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<!-- Page Title -->
<div class="page-header">
    <h2>Dashboard Overview</h2>
    <p>Welcome back! Here's what's happening with your business today.</p>
</div>

<!-- Stats Row - 4 Cards in One Line -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-box-open"></i>
        </div>
        <div class="stat-info">
            <h3>Total Products</h3>
            <p class="stat-number"><?php echo number_format($product_stats['total_products'] ?? 0); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <h3>Inventory Value</h3>
            <p class="stat-number">$<?php echo number_format($product_stats['total_value'] ?? 0, 2); ?></p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-info">
            <h3>Today's Sales</h3>
            <p class="stat-number">$<?php echo number_format($sale_stats['today']['total'] ?? 0, 2); ?></p>
            <p class="stat-change"><?php echo $sale_stats['today']['count'] ?? 0; ?> transactions</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
            <h3>Low Stock Items</h3>
            <p class="stat-number"><?php echo count($low_stock); ?></p>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
<?php if (count($low_stock) > 0): ?>
    <div class="alert-box">
        <i class="fas fa-bell"></i>
        <strong>Low Stock Alert!</strong>
        <?php foreach ($low_stock as $product): ?>
            <span class="stock-item"><?php echo htmlspecialchars($product['name']); ?> (<?php echo $product['quantity']; ?>)</span>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Quick Actions Row - 4 Cards in One Line -->
<div class="actions-row">
    <a href="index.php?action=products-create" class="action-btn">
        <i class="fas fa-plus-circle"></i>
        <span>Add Product</span>
    </a>
    <a href="index.php?action=sales" class="action-btn">
        <i class="fas fa-cash-register"></i>
        <span>New Sale</span>
    </a>
    <a href="index.php?action=reports" class="action-btn">
        <i class="fas fa-chart-pie"></i>
        <span>View Reports</span>
    </a>
    <a href="index.php?action=settings" class="action-btn">
        <i class="fas fa-info-circle"></i>
        <span>About System</span>
    </a>
</div>

<!-- Two Column Layout -->
<div class="two-column">
    <!-- Recent Sales -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3>Recent Sales Activity</h3>
            <a href="index.php?action=sales" class="view-all">View All</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_sales)): ?>
                        <?php foreach ($recent_sales as $sale): ?>
                            <tr>
                                <td><?php echo $sale['invoice_no']; ?></td>
                                <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                                <td>$<?php echo number_format($sale['total_amount'], 2); ?></td>
                                <td><?php echo date('M d', strtotime($sale['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:20px;">No sales yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3>Stock Alerts</h3>
            <a href="index.php?action=products" class="view-all">View All</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>Min Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($low_stock)): ?>
                        <?php foreach ($low_stock as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo $product['min_stock_level']; ?></td>
                                <td><span style="color:#e74c3c; font-size:10px;">⚠ Low Stock</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:20px;">All products well stocked</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Top Selling Products and Categories Row -->
<div class="two-column">
    <!-- Top Selling Products -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3>Top Selling Products</h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($top_products)): ?>
                        <?php foreach ($top_products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo $product['total_sold']; ?></td>
                                <td>$<?php echo number_format($product['revenue'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align:center; padding:20px;">No data yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Categories Overview -->
    <div class="dashboard-section">
        <div class="section-header">
            <h3>Categories Overview</h3>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <div class="category-card">
                    <i class="fas fa-tag"></i>
                    <p><?php echo htmlspecialchars($cat['name']); ?></p>
                    <span><?php echo $cat['product_count'] ?? 0; ?> products</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>