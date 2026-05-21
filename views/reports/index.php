<?php
$page_title = 'Reports';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Reports & Analytics</h1>
        <p class="text-gray-500 text-sm">Business insights and performance analytics from your data</p>
    </div>

    <!-- Report Type Tabs -->
    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-3">
        <button onclick="switchReport('sales')" id="tab-sales" class="report-tab px-4 py-2 rounded-lg text-sm font-medium transition-all bg-blue-600 text-white">
            <i class="fas fa-chart-line mr-2"></i> Sales 
        </button>
        <button onclick="switchReport('inventory')" id="tab-inventory" class="report-tab px-4 py-2 rounded-lg text-sm font-medium transition-all text-gray-600 hover:bg-gray-100">
            <i class="fas fa-boxes mr-2"></i> Inventory
        </button>
        <button onclick="switchReport('profit')" id="tab-profit" class="report-tab px-4 py-2 rounded-lg text-sm font-medium transition-all text-gray-600 hover:bg-gray-100">
            <i class="fas fa-chart-pie mr-2"></i> Profit 
        </button>
        <button onclick="switchReport('customer')" id="tab-customer" class="report-tab px-4 py-2 rounded-lg text-sm font-medium transition-all text-gray-600 hover:bg-gray-100">
            <i class="fas fa-users mr-2"></i> Customer 
        </button>
    </div>

    <!-- ==================== SALES REPORT ==================== -->
    <div id="sales-report" class="report-panel">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                <p class="text-gray-500 text-xs">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">$<?php echo number_format($total_revenue, 2); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                <p class="text-gray-500 text-xs">Total Transactions</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $total_transactions; ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
                <p class="text-gray-500 text-xs">Products Sold</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo number_format($total_items_sold); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-orange-500">
                <p class="text-gray-500 text-xs">Avg. Order Value</p>
                <p class="text-2xl font-bold text-gray-800">$<?php echo $total_transactions > 0 ? number_format($total_revenue / $total_transactions, 2) : '0.00'; ?></p>
            </div>
        </div>

        <!-- Period Filter -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm text-gray-600">Period:</span>
                <select id="salesPeriod" class="px-3 py-1.5 border rounded-lg text-sm">
                    <option value="week">Last 7 Days</option>
                    <option value="month" selected>Last 30 Days</option>
                    <option value="year">This Year</option>
                    <option value="all">All Time</option>
                </select>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Sales Trend</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="px-5 py-3 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Top Selling Products</h3>
            </div>
            <div class="p-4">
                <?php if (!empty($top_products)): ?>
                    <div class="space-y-3">
                        <?php foreach ($top_products as $index => $product): ?>
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold"><?php echo $index + 1; ?></div>
                                    <div>
                                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($product['name']); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-800"><?php echo $product['total_sold']; ?> sold</p>
                                    <p class="text-xs text-green-600">$<?php echo number_format($product['revenue'], 2); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-6">No sales data available. Complete some sales first!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Monthly Sales Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Monthly Sales Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Month</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Transactions</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($monthly_sales)): ?>
                            <?php foreach ($monthly_sales as $month): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm"><?php echo date('F Y', strtotime($month['month'] . '-01')); ?></td>
                                    <td class="px-5 py-3 text-right text-sm"><?php echo number_format($month['count']); ?></td>
                                    <td class="px-5 py-3 text-right text-sm font-semibold text-green-600">$<?php echo number_format($month['total'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-500">No sales data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== INVENTORY REPORT ==================== -->
    <div id="inventory-report" class="report-panel hidden">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Inventory Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Current Stock</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Min Stock</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inventory_items)): ?>
                            <?php foreach ($inventory_items as $item): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm"><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td class="px-5 py-3 text-right text-sm"><?php echo $item['quantity']; ?></td>
                                    <td class="px-5 py-3 text-right text-sm"><?php echo $item['min_stock_level']; ?></td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo $item['quantity'] <= 0 ? 'bg-red-100 text-red-700' : ($item['quantity'] <= $item['min_stock_level'] ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'); ?>">
                                            <?php echo $item['quantity'] <= 0 ? 'Out of Stock' : ($item['quantity'] <= $item['min_stock_level'] ? 'Low Stock' : 'In Stock'); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">No inventory data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
            <div class="px-5 py-3 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Stock Value by Category</h3>
            </div>
            <div class="p-4">
                <?php if (!empty($category_stock)): ?>
                    <div class="space-y-3">
                        <?php foreach ($category_stock as $cat): ?>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700"><?php echo htmlspecialchars($cat['name']); ?></span>
                                    <span class="font-semibold">$<?php echo number_format($cat['stock_value'], 2); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <?php $max = max(array_column($category_stock, 'stock_value')); ?>
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo $max > 0 ? ($cat['stock_value'] / $max) * 100 : 0; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-6">No category data</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ==================== PROFIT REPORT ==================== -->
    <div id="profit-report" class="report-panel hidden">
        <!-- Profit Chart - PIE CHART -->
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Profit Distribution by Product</h3>
            <div class="relative" style="height: 350px;">
                <canvas id="profitPieChart"></canvas>
            </div>
            <p class="text-center text-gray-500 text-xs mt-3">Profit share by product</p>
        </div>

        <!-- Profit Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Profit Analysis by Product</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Units Sold</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Revenue</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Cost</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Profit</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-gray-500">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($profit_data)): ?>
                            <?php foreach ($profit_data as $product): ?>
                                <?php $margin = $product['revenue'] > 0 ? ($product['profit'] / $product['revenue']) * 100 : 0; ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm"><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td class="px-5 py-3 text-right text-sm"><?php echo $product['total_sold']; ?></td>
                                    <td class="px-5 py-3 text-right text-sm">$<?php echo number_format($product['revenue'], 2); ?></td>
                                    <td class="px-5 py-3 text-right text-sm">$<?php echo number_format($product['total_cost'], 2); ?></td>
                                    <td class="px-5 py-3 text-right text-sm font-semibold text-green-600">$<?php echo number_format($product['profit'], 2); ?></td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo $margin >= 30 ? 'bg-green-100 text-green-700' : ($margin >= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700'); ?>">
                                            <?php echo round($margin, 1); ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500">No profit data. Add products with cost_price and complete sales!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== CUSTOMER REPORT ==================== -->
    <div id="customer-report" class="report-panel hidden">
        <!-- Customer Chart - BAR CHART -->
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Top Customers by Spending</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="customerBarChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Customer Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Customer Name</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Total Purchases</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500">Transactions</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500">Last Purchase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customer_data)): ?>
                            <?php foreach ($customer_data as $customer): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-3 text-sm"><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                                    <td class="px-5 py-3 text-right text-sm font-semibold">$<?php echo number_format($customer['total_spent'], 2); ?></td>
                                    <td class="px-5 py-3 text-right text-sm"><?php echo $customer['purchase_count']; ?></td>
                                    <td class="px-5 py-3 text-sm"><?php echo date('M d, Y', strtotime($customer['last_purchase'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">No customer data. Add customer names when processing sales!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let salesChart = null;
    let profitPieChart = null;
    let customerBarChart = null;

    function switchReport(type) {
        document.querySelectorAll('.report-panel').forEach(panel => panel.classList.add('hidden'));
        document.getElementById(`${type}-report`).classList.remove('hidden');

        document.querySelectorAll('.report-tab').forEach(tab => {
            tab.classList.remove('bg-blue-600', 'text-white');
            tab.classList.add('text-gray-600');
        });
        const activeTab = document.getElementById(`tab-${type}`);
        activeTab.classList.remove('text-gray-600');
        activeTab.classList.add('bg-blue-600', 'text-white');

        if (type === 'sales') loadSalesChart();
        if (type === 'profit') loadProfitPieChart();
        if (type === 'customer') loadCustomerBarChart();
    }

    function loadSalesChart() {
        const period = document.getElementById('salesPeriod')?.value || 'month';
        fetch(`api/charts.php?period=${period}`)
            .then(res => res.json())
            .then(data => {
                if (salesChart) salesChart.destroy();
                const ctx = document.getElementById('salesChart').getContext('2d');
                salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Sales ($)',
                            data: data.values,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }).catch(() => console.log('No sales data'));
    }

    function loadProfitPieChart() {
        fetch('api/profit_chart.php')
            .then(res => res.json())
            .then(data => {
                if (profitPieChart) profitPieChart.destroy();
                const ctx = document.getElementById('profitPieChart').getContext('2d');
                profitPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }).catch(() => console.log('No profit data'));
    }

    function loadCustomerBarChart() {
        fetch('api/customer_chart.php')
            .then(res => res.json())
            .then(data => {
                if (customerBarChart) customerBarChart.destroy();
                const ctx = document.getElementById('customerBarChart').getContext('2d');
                customerBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Spent ($)',
                            data: data.values,
                            backgroundColor: '#10b981',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }).catch(() => console.log('No customer data'));
    }

    if (document.getElementById('salesPeriod')) {
        document.getElementById('salesPeriod').addEventListener('change', loadSalesChart);
    }

    loadSalesChart();
    loadProfitPieChart();
    loadCustomerBarChart();
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>