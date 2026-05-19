<?php
$page_title = 'Reports';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="page-header">
    <h2>Reports & Analytics</h2>
    <p>Business insights and performance analytics</p>
</div>

<div class="stats-row" style="margin-bottom: 20px;">
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <h3>Total Revenue</h3>
            <p class="stat-number">$<?php echo number_format($sale_stats['total']['total'] ?? 0, 2); ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-info">
            <h3>Total Transactions</h3>
            <p class="stat-number"><?php echo $sale_stats['total']['count'] ?? 0; ?></p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <h3>Products Sold</h3>
            <p class="stat-number"><?php echo number_format($total_items_sold); ?></p>
        </div>
    </div>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h3>Monthly Sales Report</h3>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Transactions</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($monthly_sales)): ?>
                    <?php foreach ($monthly_sales as $month): ?>
                        <tr>
                            <td><?php echo date('F Y', strtotime($month['month'] . '-01')); ?></td>
                            <td><?php echo $month['count']; ?></td>
                            <td>$<?php echo number_format($month['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <td>
                    <td colspan="3" style="text-align:center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>