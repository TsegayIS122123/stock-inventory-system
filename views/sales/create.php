<?php
$page_title = 'Point of Sale';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Product Search & List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <div class="flex gap-2">
                <input type="text" id="search-product" placeholder="Search product by name or barcode..."
                    class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                <button id="search-btn" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="font-semibold text-lg mb-4">Products</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="products-list">
                <?php foreach ($products as $product): ?>
                    <div class="border rounded-lg p-3 hover:shadow-lg transition cursor-pointer product-item"
                        data-id="<?php echo $product['id']; ?>"
                        data-name="<?php echo htmlspecialchars($product['name']); ?>"
                        data-price="<?php echo $product['price']; ?>">
                        <?php if ($product['image']): ?>
                            <img src="<?php echo BASE_URL . $product['image']; ?>" class="w-full h-24 object-cover rounded mb-2">
                        <?php else: ?>
                            <div class="w-full h-24 bg-gray-200 rounded mb-2 flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        <h4 class="font-semibold text-sm"><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p class="text-blue-600 font-bold">$<?php echo number_format($product['price'], 2); ?></p>
                        <p class="text-xs text-gray-500">Stock: <?php echo $product['quantity']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Shopping Cart -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-4 sticky top-6">
            <h3 class="font-semibold text-lg mb-4">Shopping Cart</h3>

            <div id="cart-items" class="max-h-96 overflow-y-auto mb-4">
                <?php if (empty($cart_items['items'])): ?>
                    <p class="text-gray-500 text-center py-8">Cart is empty</p>
                <?php else: ?>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items['items'] as $item): ?>
                                <tr class="border-b">
                                    <td class="py-2"><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td class="text-center">
                                        <input type="number" value="<?php echo $item['quantity']; ?>"
                                            class="w-16 px-2 py-1 border rounded text-center cart-qty"
                                            data-id="<?php echo $item['id']; ?>">
                                    </td>
                                    <td class="text-right">$<?php echo number_format($item['subtotal'], 2); ?></td>
                                    <td class="text-right">
                                        <a href="index.php?action=remove-from-cart&id=<?php echo $item['id']; ?>" class="text-red-500">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span class="font-semibold">$<?php echo number_format($cart_items['total'], 2); ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Discount:</span>
                    <input type="number" id="discount" class="w-24 px-2 py-1 border rounded text-right" value="0">
                </div>
                <div class="flex justify-between text-lg font-bold mb-4">
                    <span>Total:</span>
                    <span id="total-amount">$<?php echo number_format($cart_items['total'], 2); ?></span>
                </div>

                <form method="POST" action="index.php?action=checkout">
                    <input type="text" name="customer_name" placeholder="Customer name"
                        class="w-full px-3 py-2 border rounded-lg mb-3">
                    <select name="payment_method" class="w-full px-3 py-2 border rounded-lg mb-3">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile">Mobile Payment</option>
                    </select>
                    <input type="hidden" name="discount" id="discount-hidden" value="0">
                    <button type="submit" class="btn-primary w-full text-white py-2 rounded-lg font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> Complete Sale
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Add to cart
        $('.product-item').click(function() {
            var productId = $(this).data('id');
            $.post('index.php?action=add-to-cart', {
                product_id: productId,
                quantity: 1
            }, function(response) {
                location.reload();
            });
        });

        // Update quantity
        $('.cart-qty').change(function() {
            var productId = $(this).data('id');
            var quantity = $(this).val();
            $.post('index.php?action=update-cart', {
                product_id: productId,
                quantity: quantity
            }, function(response) {
                location.reload();
            });
        });
    });
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>