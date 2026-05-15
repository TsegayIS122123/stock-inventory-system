<?php
$page_title = 'Sales History';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Sales Transactions</h2>
        <a href="index.php?action=sales-create" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i> New Sale
        </a>
    </div>

    <?php if (empty($sales)): ?>
        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Sales Yet</h3>
            <p class="text-gray-500 mb-4">Start by creating your first sale</p>
            <a href="index.php?action=sales-create" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i> Create First Sale
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td class="px-6 py-4 font-medium"><?php echo $sale['invoice_no']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                            <td class="px-6 py-4 font-semibold">$<?php echo number_format($sale['total_amount'], 2); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <?php echo ucfirst($sale['payment_method']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($sale['created_at'])); ?></td>
                            <td class="px-6 py-4"><?php echo $sale['cashier_name'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="index.php?action=sales-invoice&id=<?php echo $sale['id']; ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-print"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>