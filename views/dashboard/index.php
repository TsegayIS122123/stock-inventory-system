<?php
$page_title = 'Dashboard';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Total Products</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $product_stats['total_products'] ?? 0; ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-box text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Stock Value</p>
                <p class="text-3xl font-bold text-gray-800">$<?php echo number_format($product_stats['total_value'] ?? 0, 2); ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Today's Sales</p>
                <p class="text-3xl font-bold text-gray-800">$<?php echo number_format($sale_stats['today']['total'] ?? 0, 2); ?></p>
                <p class="text-xs text-gray-400"><?php echo $sale_stats['today']['count'] ?? 0; ?> transactions</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Low Stock</p>
                <p class="text-3xl font-bold text-orange-600"><?php echo count($low_stock); ?></p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
<?php if (count($low_stock) > 0): ?>
    <div class="bg-orange-100 border-l-4 border-orange-500 p-4 mb-8 rounded">
        <div class="flex items-center">
            <i class="fas fa-bell text-orange-500 text-xl mr-3"></i>
            <p class="text-orange-700"><strong>Low Stock Alert!</strong> The following products are running low:</p>
        </div>
        <div class="mt-2 ml-8">
            <?php foreach ($low_stock as $product): ?>
                <span class="inline-block bg-orange-200 text-orange-800 px-2 py-1 rounded text-sm mr-2 mb-2">
                    <?php echo htmlspecialchars($product['name']); ?> (<?php echo $product['quantity']; ?> left)
                </span>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="index.php?action=products-create" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
        <i class="fas fa-plus-circle text-blue-500 text-4xl mb-3"></i>
        <h3 class="font-semibold text-lg">Add Product</h3>
        <p class="text-gray-500 text-sm">Add new products to inventory</p>
    </a>

    <a href="index.php?action=sales-create" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
        <i class="fas fa-cash-register text-green-500 text-4xl mb-3"></i>
        <h3 class="font-semibold text-lg">New Sale</h3>
        <p class="text-gray-500 text-sm">Process a new transaction</p>
    </a>

    <a href="index.php?action=products" class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
        <i class="fas fa-boxes text-purple-500 text-4xl mb-3"></i>
        <h3 class="font-semibold text-lg">Manage Inventory</h3>
        <p class="text-gray-500 text-sm">Update stock levels</p>
    </a>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>