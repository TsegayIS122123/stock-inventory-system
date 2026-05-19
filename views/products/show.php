<?php
$page_title = 'Product Details';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Product Details</h2>
        <div>
            <a href="index.php?action=products-edit&id=<?php echo $product['id']; ?>"
                class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="index.php?action=products" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <?php if ($product['image']): ?>
                <img src="<?php echo BASE_URL . $product['image']; ?>" class="max-w-full max-h-96 object-contain mx-auto rounded-lg">
            <?php else: ?>
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-gray-400 text-6xl"></i>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="space-y-4">
            <div class="border-b pb-3">
                <h3 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="text-gray-500 text-sm">SKU: <?php echo $product['sku']; ?></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Category</p>
                    <p class="font-semibold"><?php echo $product['category_name'] ?? 'Uncategorized'; ?></p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    <p>
                        <?php if ($product['is_active']): ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-lg p-3 text-center">
                    <p class="text-gray-500 text-sm">Selling Price</p>
                    <p class="text-2xl font-bold text-blue-600">$<?php echo number_format($product['price'], 2); ?></p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-center">
                    <p class="text-gray-500 text-sm">Cost Price</p>
                    <p class="text-2xl font-bold text-gray-600">$<?php echo number_format($product['cost_price'], 2); ?></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Current Stock</p>
                    <p class="text-xl font-bold <?php echo $product['quantity'] <= $product['min_stock_level'] ? 'text-red-600' : 'text-green-600'; ?>">
                        <?php echo $product['quantity']; ?> units
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Min Stock Level</p>
                    <p class="text-xl font-bold"><?php echo $product['min_stock_level']; ?> units</p>
                </div>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Profit Margin</p>
                <?php
                $profit = $product['price'] - $product['cost_price'];
                $margin = $product['cost_price'] > 0 ? ($profit / $product['cost_price']) * 100 : 0;
                ?>
                <p class="text-xl font-bold text-green-600">$<?php echo number_format($profit, 2); ?> (<?php echo round($margin, 2); ?>%)</p>
            </div>

            <?php if ($product['description']): ?>
                <div>
                    <p class="text-gray-500 text-sm">Description</p>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            <?php endif; ?>

            <div class="pt-4 border-t">
                <p class="text-gray-400 text-xs">Created: <?php echo date('F j, Y g:i A', strtotime($product['created_at'])); ?></p>
                <p class="text-gray-400 text-xs">Last Updated: <?php echo date('F j, Y g:i A', strtotime($product['updated_at'])); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>