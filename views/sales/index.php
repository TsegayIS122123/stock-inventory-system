<?php
$page_title = 'Sales';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="p-4">
    <!-- Page Title -->
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-800">Sales</h1>
        <p class="text-xs text-gray-500">Process sales and view transaction history</p>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="bg-white rounded-lg shadow-sm p-3 text-center border-l-4 border-green-500">
            <p class="text-gray-500 text-xs">Today's Sales</p>
            <p class="text-xl font-bold text-gray-800">$<?php echo number_format($todaySales, 2); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-3 text-center border-l-4 border-blue-500">
            <p class="text-gray-500 text-xs">Transactions</p>
            <p class="text-xl font-bold text-gray-800"><?php echo $todayTransactions; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-3 text-center border-l-4 border-purple-500">
            <p class="text-gray-500 text-xs">Cart Items</p>
            <p class="text-xl font-bold text-purple-600" id="cartCount"><?php echo count($cartItems['items']); ?></p>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- LEFT COLUMN: Products List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <!-- Search Bar -->
                <div class="p-3 border-b">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="searchProduct"
                            placeholder="Search products by name or SKU..."
                            class="w-full pl-9 pr-3 py-2 text-sm border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <!-- Products List - Clean Table View -->
                <div class="max-h-[500px] overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-gray-50">
                            <tr class="border-b">
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Product</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Stock</th>
                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500">Price</th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr class="border-b hover:bg-gray-50 product-row"
                                    data-name="<?php echo strtolower(htmlspecialchars($product['name'])); ?>"
                                    data-sku="<?php echo strtolower($product['sku']); ?>">
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                                <?php if ($product['image']): ?>
                                                    <img src="<?php echo $product['image']; ?>" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <i class="fas fa-box text-gray-400 text-xs"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm"><?php echo htmlspecialchars($product['name']); ?></p>
                                                <p class="text-xs text-gray-400"><?php echo $product['sku']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="text-xs <?php echo $product['quantity'] <= 5 ? 'text-red-500' : 'text-gray-500'; ?>">
                                            <?php echo $product['quantity']; ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-right font-semibold text-blue-600">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button onclick="addToCart(<?php echo $product['id']; ?>, <?php echo $product['quantity']; ?>)"
                                            class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition"
                                            <?php echo $product['quantity'] <= 0 ? 'disabled' : ''; ?>>
                                            <i class="fas fa-cart-plus mr-1"></i> Add
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Shopping Cart -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm sticky top-4">
                <!-- Cart Header -->
                <div class="p-3 border-b bg-gray-50 rounded-t-lg">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-blue-500"></i>
                        Shopping Cart
                        <span id="cartBadge" class="ml-auto bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo count($cartItems['items']); ?></span>
                    </h3>
                </div>

                <!-- Cart Items -->
                <div id="cartItems" class="max-h-[400px] overflow-y-auto">
                    <?php if (empty($cartItems['items'])): ?>
                        <div class="text-center py-10 text-gray-400">
                            <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                            <p class="text-sm">Cart is empty</p>
                            <p class="text-xs">Click "Add" on products</p>
                        </div>
                    <?php else: ?>
                        <div class="divide-y">
                            <?php foreach ($cartItems['items'] as $item): ?>
                                <div class="p-3 cart-item" data-id="<?php echo $item['id']; ?>">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm"><?php echo htmlspecialchars($item['name']); ?></p>
                                            <p class="text-blue-600 font-bold text-sm">$<?php echo number_format($item['price'], 2); ?></p>
                                        </div>
                                        <button onclick="removeFromCart(<?php echo $item['id']; ?>)"
                                            class="text-red-400 hover:text-red-600">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex items-center gap-2">
                                            <button onclick="updateQty(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)"
                                                class="w-7 h-7 bg-gray-100 rounded hover:bg-gray-200 transition">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <span class="w-8 text-center text-sm font-medium" id="qty-<?php echo $item['id']; ?>">
                                                <?php echo $item['quantity']; ?>
                                            </span>
                                            <button onclick="updateQty(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>, <?php echo $item['max_stock']; ?>)"
                                                class="w-7 h-7 bg-gray-100 rounded hover:bg-gray-200 transition">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        <p class="font-semibold text-sm">$<span id="subtotal-<?php echo $item['id']; ?>"><?php echo number_format($item['subtotal'], 2); ?></span></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Cart Summary -->
                <div class="p-3 border-t bg-gray-50">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal:</span>
                            <span class="font-semibold">$<span id="cartSubtotal"><?php echo number_format($cartItems['total'], 2); ?></span></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t">
                            <span>Total:</span>
                            <span class="text-blue-600">$<span id="cartTotal"><?php echo number_format($cartItems['total'], 2); ?></span></span>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <form method="POST" action="index.php?action=checkout" class="mt-3 space-y-2" id="checkoutForm">
                        <input type="text" name="customer_name" placeholder="Customer name (optional)"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:border-blue-500">
                        <select name="payment_method" class="w-full px-3 py-2 text-sm border rounded-lg">
                            <option value="cash">💵 Cash</option>
                            <option value="card">💳 Card</option>
                            <option value="mobile">📱 Mobile Payment</option>
                        </select>
                        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg font-semibold hover:bg-green-600 transition text-sm">
                            <i class="fas fa-check-circle mr-2"></i> Complete Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales History Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-3 border-b">
                <h3 class="font-semibold text-gray-800">Recent Transactions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="border-b">
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Invoice</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Customer</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500">Amount</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Payment</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400">No sales yet</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach (array_slice($sales, 0, 5) as $sale): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2 font-medium text-xs"><?php echo $sale['invoice_no']; ?></td>
                                    <td class="px-3 py-2 text-xs"><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                                    <td class="px-3 py-2 text-right font-semibold text-xs">$<?php echo number_format($sale['total_amount'], 2); ?></td>
                                    <td class="px-3 py-2 text-xs capitalize"><?php echo $sale['payment_method']; ?></td>
                                    <td class="px-3 py-2 text-xs"><?php echo date('M d, h:i A', strtotime($sale['created_at'])); ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="index.php?action=sales-invoice&id=<?php echo $sale['id']; ?>"
                                            class="text-blue-500 hover:text-blue-700" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($sales) > 5): ?>
                <div class="p-2 text-center border-t">
                    <a href="#" class="text-xs text-blue-500">View all <?php echo count($sales); ?> transactions</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Search products
    $('#searchProduct').on('keyup', function() {
        let search = $(this).val().toLowerCase();
        $('.product-row').each(function() {
            let name = $(this).data('name');
            let sku = $(this).data('sku');
            let match = name.includes(search) || (sku && sku.includes(search));
            $(this).toggle(match);
        });
    });

    // Add to cart
    function addToCart(id, stock) {
        if (stock <= 0) {
            showToast('Out of stock!', 'error');
            return;
        }

        $.post('index.php?action=add-to-cart', {
                product_id: id,
                quantity: 1
            })
            .done(function() {
                location.reload();
            })
            .fail(function() {
                location.reload();
            });
    }

    // Update quantity
    function updateQty(id, quantity, maxStock = 999) {
        if (quantity < 0) return;
        if (maxStock && quantity > maxStock) {
            showToast('Not enough stock!', 'error');
            return;
        }

        $.post('index.php?action=update-cart', {
                product_id: id,
                quantity: quantity
            })
            .done(function() {
                location.reload();
            })
            .fail(function() {
                location.reload();
            });
    }

    // Remove from cart
    function removeFromCart(id) {
        if (confirm('Remove this item from cart?')) {
            window.location.href = 'index.php?action=remove-from-cart&id=' + id;
        }
    }

    // Toast notification
    function showToast(message, type = 'success') {
        let bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        let toast = $(`<div class="fixed top-20 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2 text-sm">
        <i class="fas ${icon}"></i> ${message}
    </div>`);
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, function() {
            $(this).remove();
        }), 3000);
    }

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>