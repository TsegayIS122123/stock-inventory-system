<?php
$page_title = 'Sales Management';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sales Management</h1>
        <p class="text-gray-500 text-sm">Process sales and view transaction history</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
            <p class="text-gray-500 text-sm">Today's Sales</p>
            <p class="text-2xl font-bold text-gray-800">$<?php echo number_format($todaySales, 2); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Today's Transactions</p>
            <p class="text-2xl font-bold text-gray-800"><?php echo $todayTransactions; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
            <p class="text-gray-500 text-sm">Cart Items</p>
            <p class="text-2xl font-bold text-gray-800" id="cartCount"><?php echo count($cartItems['items']); ?></p>
        </div>
    </div>

    <!-- Two Column Layout: POS + Cart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Products List (POS) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-4 border-b">
                    <h3 class="font-semibold text-gray-800">Point of Sale</h3>
                    <div class="mt-3">
                        <input type="text" id="searchProduct" placeholder="Search products by name or SKU..."
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div class="p-4 max-h-[500px] overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="productsGrid">
                        <?php foreach ($products as $product): ?>
                            <div class="product-item border rounded-lg p-3 hover:shadow-md transition cursor-pointer"
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-stock="<?php echo $product['quantity']; ?>">

                                <div class="w-full h-24 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                                    <?php if ($product['image']): ?>
                                        <img src="<?php echo $product['image']; ?>" class="h-full w-full object-cover rounded-lg">
                                    <?php else: ?>
                                        <i class="fas fa-box text-gray-400 text-3xl"></i>
                                    <?php endif; ?>
                                </div>

                                <h4 class="font-medium text-sm truncate"><?php echo htmlspecialchars($product['name']); ?></h4>
                                <p class="text-blue-600 font-bold text-sm">$<?php echo number_format($product['price'], 2); ?></p>
                                <p class="text-xs text-gray-500">Stock: <?php echo $product['quantity']; ?></p>

                                <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, <?php echo $product['quantity']; ?>)"
                                    class="mt-2 w-full bg-blue-500 text-white py-1 rounded-lg text-xs hover:bg-blue-600 transition">
                                    <i class="fas fa-cart-plus mr-1"></i> Add
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Shopping Cart -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm sticky top-6">
                <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-blue-500"></i>
                        Shopping Cart
                    </h3>
                </div>

                <div id="cartItems" class="p-4 max-h-[400px] overflow-y-auto">
                    <?php if (empty($cartItems['items'])): ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-cart-plus text-4xl mb-2"></i>
                            <p>Cart is empty</p>
                            <p class="text-xs">Click on products to add</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($cartItems['items'] as $item): ?>
                                <div class="cart-item border-b pb-3" data-id="<?php echo $item['id']; ?>">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm"><?php echo htmlspecialchars($item['name']); ?></p>
                                            <p class="text-blue-600 font-bold text-sm">$<?php echo number_format($item['price'], 2); ?></p>
                                        </div>
                                        <button onclick="removeFromCart(<?php echo $item['id']; ?>)" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center gap-2">
                                            <button onclick="updateCartItem(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)"
                                                class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">-</button>
                                            <span class="w-8 text-center text-sm" id="qty-<?php echo $item['id']; ?>"><?php echo $item['quantity']; ?></span>
                                            <button onclick="updateCartItem(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>, <?php echo $item['max_stock']; ?>)"
                                                class="w-6 h-6 bg-gray-200 rounded hover:bg-gray-300">+</button>
                                        </div>
                                        <p class="font-semibold text-sm">$<span id="subtotal-<?php echo $item['id']; ?>"><?php echo number_format($item['subtotal'], 2); ?></span></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-4 border-t bg-gray-50">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">$<span id="cartSubtotal"><?php echo number_format($cartItems['total'], 2); ?></span></span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="font-bold">Total:</span>
                            <span class="font-bold text-lg text-blue-600">$<span id="cartTotal"><?php echo number_format($cartItems['total'], 2); ?></span></span>
                        </div>
                    </div>

                    <form method="POST" action="index.php?action=checkout" class="mt-4 space-y-3">
                        <input type="text" name="customer_name" placeholder="Customer name (optional)"
                            class="w-full px-3 py-2 border rounded-lg text-sm">
                        <select name="payment_method" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile">Mobile Payment</option>
                        </select>
                        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg font-semibold hover:bg-green-600 transition">
                            <i class="fas fa-check-circle mr-2"></i> Complete Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales History Table -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Recent Sales History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Invoice #</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Customer</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">Amount</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Payment</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Date</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Cashier</th>
                            <th class="px-4 py-2 text-center text-xs text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">No sales yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($sales as $sale): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm font-medium"><?php echo $sale['invoice_no']; ?></td>
                                    <td class="px-4 py-2 text-sm"><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                                    <td class="px-4 py-2 text-right font-semibold">$<?php echo number_format($sale['total_amount'], 2); ?></td>
                                    <td class="px-4 py-2 text-sm capitalize"><?php echo $sale['payment_method']; ?></td>
                                    <td class="px-4 py-2 text-sm"><?php echo date('M d, Y h:i A', strtotime($sale['created_at'])); ?></td>
                                    <td class="px-4 py-2 text-sm"><?php echo $sale['cashier_name']; ?></td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="index.php?action=sales-invoice&id=<?php echo $sale['id']; ?>" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Search products
    $('#searchProduct').on('keyup', function() {
        let search = $(this).val().toLowerCase();
        $('.product-item').each(function() {
            let name = $(this).data('name').toLowerCase();
            $(this).toggle(name.includes(search));
        });
    });

    // Add to cart
    function addToCart(id, name, price, stock) {
        if (stock <= 0) {
            alert('Out of stock!');
            return;
        }

        $.post('index.php?action=add-to-cart', {
            product_id: id,
            quantity: 1
        }, function(response) {
            location.reload();
        }).fail(function() {
            location.reload();
        });
    }

    // Update cart item
    function updateCartItem(id, quantity, maxStock = 999) {
        if (quantity < 0) return;
        if (maxStock && quantity > maxStock) {
            alert('Not enough stock!');
            return;
        }

        $.post('index.php?action=update-cart', {
            product_id: id,
            quantity: quantity
        }, function() {
            location.reload();
        });
    }

    // Remove from cart
    function removeFromCart(id) {
        if (confirm('Remove this item from cart?')) {
            window.location.href = 'index.php?action=remove-from-cart&id=' + id;
        }
    }
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>