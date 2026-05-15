<?php
$page_title = 'Products';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Product Management</h2>
    <a href="index.php?action=products-create" class="btn-primary text-white px-4 py-2 rounded-lg">
        <i class="fas fa-plus mr-2"></i> Add Product
    </a>
</div>

<!-- Search Bar -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="index.php" class="flex gap-4">
        <input type="hidden" name="action" value="products-search">
        <input type="text" name="q" placeholder="Search by name, SKU, or category..."
            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
        <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
            <i class="fas fa-search mr-2"></i> Search
        </button>
    </form>
</div>

<!-- Products Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name / SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-6 py-4">
                        <?php if ($product['image']): ?>
                            <img src="<?php echo BASE_URL . $product['image']; ?>" class="w-12 h-12 object-cover rounded">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="text-sm text-gray-500">SKU: <?php echo $product['sku']; ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo $product['category_name'] ?? 'Uncategorized'; ?></td>
                    <td class="px-6 py-4 text-sm font-semibold">$<?php echo number_format($product['price'], 2); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $product['quantity'] <= $product['min_stock_level'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                            <?php echo $product['quantity']; ?> units
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($product['is_active']): ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="index.php?action=products-edit&id=<?php echo $product['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <a href="index.php?action=products-delete&id=<?php echo $product['id']; ?>" onclick="return confirmDelete()" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>