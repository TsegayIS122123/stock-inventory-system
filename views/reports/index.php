<?php
$page_title = 'Reports';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="p-6">
    <div class="page-header mb-6">
        <h2 class="text-2xl font-bold">Reports & Analytics</h2>
        <p class="text-gray-500">Business insights and performance analytics</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">$<?php echo number_format($sale_stats['total']['total'] ?? 0, 2); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Total Transactions</p>
            <p class="text-2xl font-bold text-blue-600"><?php echo $sale_stats['total']['count'] ?? 0; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-gray-500 text-sm">Products Sold</p>
            <p class="text-2xl font-bold text-purple-600"><?php echo number_format($total_items_sold); ?></p>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="font-semibold text-lg mb-4">Sales Trend (Last 7 Days)</h3>
        <canvas id="salesChart" height="250"></canvas>
    </div>

    <!-- Monthly Sales Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-lg">Monthly Sales Report</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Month</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Transactions</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($monthly_sales)): ?>
                        <?php foreach ($monthly_sales as $month): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm"><?php echo date('F Y', strtotime($month['month'] . '-01')); ?></td>
                                <td class="px-6 py-3 text-right text-sm"><?php echo $month['count']; ?></td>
                                <td class="px-6 py-3 text-right text-sm font-semibold">$<?php echo number_format($month['total'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-8 text-gray-500">No data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    fetch('api/charts.php?period=week')
        .then(res => res.json())
        .then(data => {
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sales ($)',
                        data: data.values,
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        });
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>