<?php
$page_title = 'Invoice';
require_once BASE_PATH . '/views/layouts/header.php';
?>

<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Invoice Header -->
        <div class="text-center border-b pb-4 mb-4">
            <h1 class="text-3xl font-bold text-blue-600">TKC-Stock</h1>
            <p class="text-gray-500">Smart Inventory & Sales Management System</p>
            <p class="text-gray-400 text-sm mt-2">Invoice #: <span class="font-bold text-gray-700"><?php echo $sale['invoice_no']; ?></span></p>
        </div>

        <!-- Customer Info -->
        <div class="mb-6">
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($sale['customer_name']); ?></p>
            <p><strong>Date:</strong> <?php echo date('F j, Y h:i A', strtotime($sale['created_at'])); ?></p>
            <p><strong>Cashier:</strong> <?php echo $sale['cashier_name']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst($sale['payment_method']); ?></p>
        </div>

        <!-- Items Table -->
        <table class="w-full border-collapse mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Product</th>
                    <th class="p-2 text-right">Qty</th>
                    <th class="p-2 text-right">Price</th>
                    <th class="p-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sale['items'] as $item): ?>
                    <tr class="border-b">
                        <td class="p-2"><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td class="p-2 text-right"><?php echo $item['quantity']; ?></td>
                        <td class="p-2 text-right">$<?php echo number_format($item['price'], 2); ?></td>
                        <td class="p-2 text-right">$<?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="3" class="p-2 text-right font-bold">Total:</td>
                    <td class="p-2 text-right font-bold text-blue-600">$<?php echo number_format($sale['total_amount'], 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="text-center text-gray-400 text-sm border-t pt-4">
            <p>Thank you for your purchase!</p>
            <p>For inquiries, please contact: support@tkcstock.com</p>
        </div>
    </div>

    <div class="text-center mt-4 space-x-3">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            <i class="fas fa-print mr-2"></i> Print Invoice
        </button>
        <a href="index.php?action=sales" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Back to Sales
        </a>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>