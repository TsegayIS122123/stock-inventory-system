<?php
$page_title = 'Products';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Products</h1>
            <p class="text-xs text-gray-500">Manage your inventory</p>
        </div>
        <button onclick="openProductModal()"
            class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Add Product
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-sm p-3 mb-4">
        <div class="flex flex-wrap gap-3 items-center">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="searchInput" placeholder="Search by name or SKU..."
                        class="w-full pl-8 pr-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div class="w-48">
                <select id="categoryFilter" class="w-full px-3 py-1.5 text-sm border rounded-lg">
                    <option value="all">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?> (<?php echo $cat['product_count'] ?? 0; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button onclick="openCategoryModal()" class="text-blue-600 text-sm hover:text-blue-800">
                <i class="fas fa-tags mr-1"></i> Manage Categories
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Image</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name / SKU</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Category</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Price</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Stock</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                <?php foreach ($products as $product): ?>
                    <tr class="border-b hover:bg-gray-50 transition product-row"
                        data-name="<?php echo strtolower(htmlspecialchars($product['name'])); ?>"
                        data-sku="<?php echo strtolower($product['sku']); ?>"
                        data-category="<?php echo $product['category_id']; ?>">
                        <td class="px-4 py-2">
                            <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                <?php if ($product['image']): ?>
                                    <img src="<?php echo $product['image']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-box text-gray-400 text-sm"></i>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="font-medium text-sm"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="text-xs text-gray-500">SKU: <?php echo $product['sku']; ?></div>
                        </td>
                        <td class="px-4 py-2 text-sm"><?php echo $product['category_name'] ?? 'Uncategorized'; ?></td>
                        <td class="px-4 py-2 text-right font-semibold text-sm">$<?php echo number_format($product['price'], 2); ?></td>
                        <td class="px-4 py-2 text-right">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full 
                            <?php echo $product['quantity'] <= 0 ? 'bg-red-100 text-red-700' : ($product['quantity'] <= $product['min_stock_level'] ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'); ?>">
                                <?php echo $product['quantity']; ?> units
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="adjustStock(<?php echo $product['id']; ?>, 'add')" class="text-green-600 hover:text-green-800 text-sm" title="Add Stock"><i class="fas fa-plus-circle"></i></button>
                                <button onclick="adjustStock(<?php echo $product['id']; ?>, 'remove')" class="text-red-600 hover:text-red-800 text-sm" title="Remove Stock"><i class="fas fa-minus-circle"></i></button>
                                <button onclick="editProduct(<?php echo $product['id']; ?>)" class="text-blue-600 hover:text-blue-800 text-sm" title="Edit"><i class="fas fa-edit"></i></button>
                                <button onclick="viewProduct(<?php echo $product['id']; ?>)" class="text-gray-600 hover:text-gray-800 text-sm" title="View"><i class="fas fa-eye"></i></button>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="text-red-600 hover:text-red-800 text-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="emptyState" class="hidden text-center py-8">
        <i class="fas fa-box-open text-gray-300 text-4xl mb-2"></i>
        <p class="text-gray-500 text-sm">No products found</p>
    </div>
</div>

<!-- ========== PRODUCT MODAL ========== -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-5 py-3 flex justify-between items-center">
            <h2 id="modalTitle" class="font-semibold">Add Product</h2>
            <button onclick="closeModal('productModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="productForm" method="POST" action="index.php?action=products-store" class="p-5">
            <input type="hidden" name="id" id="productId">
            <div class="space-y-3">
                <div><label class="block text-xs font-medium mb-1">Product Name *</label><input type="text" name="name" id="productName" required class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium mb-1">SKU</label><input type="text" name="sku" id="productSku" class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                    <div><label class="block text-xs font-medium mb-1">Category</label>
                        <select name="category_id" id="productCategory" class="w-full px-3 py-1.5 text-sm border rounded-lg">
                            <option value="">Select</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium mb-1">Price *</label><input type="number" name="price" id="productPrice" step="0.01" required class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                    <div><label class="block text-xs font-medium mb-1">Quantity</label><input type="number" name="quantity" id="productQuantity" value="0" class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                </div>
                <div><label class="block text-xs font-medium mb-1">Min Stock Alert</label><input type="number" name="min_stock_level" id="productMinStock" value="5" class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                <div><label class="block text-xs font-medium mb-1">Description</label><textarea name="description" id="productDescription" rows="2" class="w-full px-3 py-1.5 text-sm border rounded-lg"></textarea></div>
                <div><label class="block text-xs font-medium mb-1">Image URL</label><input type="text" name="image_url" id="productImage" placeholder="https://..." class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                <div id="imagePreview" class="hidden"><img id="previewImg" class="w-16 h-16 object-cover rounded"></div>
            </div>
            <div class="flex justify-end gap-2 mt-5 pt-3 border-t">
                <button type="button" onclick="closeModal('productModal')" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- ========== CATEGORY MODAL ========== -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="border-b px-5 py-3 flex justify-between items-center">
            <h2 class="font-semibold">Manage Categories</h2>
            <button onclick="closeModal('categoryModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-5">
            <form id="categoryForm" class="mb-4">
                <div class="flex gap-2">
                    <input type="text" id="newCategoryName" placeholder="New category name" class="flex-1 px-3 py-1.5 text-sm border rounded-lg">
                    <button type="submit" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">Add</button>
                </div>
            </form>
            <div id="categoriesList" class="max-h-64 overflow-y-auto">
                <?php foreach ($categories as $cat): ?>
                    <div class="flex justify-between items-center py-2 border-b">
                        <div><span class="text-sm"><?php echo htmlspecialchars($cat['name']); ?></span><span class="text-xs text-gray-500 ml-2">(<?php echo $cat['product_count'] ?? 0; ?> products)</span></div>
                        <div class="flex gap-2">
                            <button onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars($cat['name']); ?>')" class="text-blue-600 text-sm">Edit</button>
                            <?php if (($cat['product_count'] ?? 0) == 0): ?>
                                <button onclick="deleteCategory(<?php echo $cat['id']; ?>)" class="text-red-600 text-sm">Delete</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========== STOCK MODAL ========== -->
<div id="stockModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-sm">
        <div class="border-b px-5 py-3 flex justify-between items-center">
            <h2 id="stockModalTitle" class="font-semibold">Adjust Stock</h2>
            <button onclick="closeModal('stockModal')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-5">
            <p id="stockProductName" class="text-sm font-medium mb-3"></p>
            <p id="currentStock" class="text-xs text-gray-500 mb-3"></p>
            <div class="flex gap-3">
                <input type="number" id="stockQuantity" class="flex-1 px-3 py-1.5 text-sm border rounded-lg" placeholder="Quantity" value="1">
                <button id="stockActionBtn" class="px-4 py-1.5 text-white rounded-lg">Apply</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentProduct = null;
    let stockAction = null;

    // Search & Filter
    function filterProducts() {
        let search = document.getElementById('searchInput').value.toLowerCase();
        let category = document.getElementById('categoryFilter').value;
        let rows = document.querySelectorAll('.product-row');
        let visible = 0;
        rows.forEach(row => {
            let name = row.getAttribute('data-name');
            let sku = row.getAttribute('data-sku');
            let cat = row.getAttribute('data-category');
            let matchSearch = search === '' || name.includes(search) || sku.includes(search);
            let matchCategory = category === 'all' || cat == category;
            row.style.display = (matchSearch && matchCategory) ? '' : 'none';
            if (matchSearch && matchCategory) visible++;
        });
        document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
    }

    document.getElementById('searchInput').addEventListener('keyup', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);

    // Image preview
    document.getElementById('productImage').addEventListener('input', function() {
        let url = this.value;
        if (url) {
            document.getElementById('previewImg').src = url;
            document.getElementById('imagePreview').classList.remove('hidden');
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
    });

    // Stock Adjustment
    function adjustStock(id, action) {
        fetch('index.php?action=get-product&id=' + id).then(res => res.json()).then(product => {
            currentProduct = product;
            stockAction = action;
            document.getElementById('stockProductName').innerText = product.name;
            document.getElementById('currentStock').innerHTML = `Current stock: <strong>${product.quantity}</strong> units`;
            document.getElementById('stockModalTitle').innerText = action === 'add' ? 'Add Stock' : 'Remove Stock';
            document.getElementById('stockActionBtn').className = action === 'add' ? 'px-4 py-1.5 bg-green-600 text-white rounded-lg' : 'px-4 py-1.5 bg-red-600 text-white rounded-lg';
            document.getElementById('stockActionBtn').innerText = action === 'add' ? 'Add Stock' : 'Remove Stock';
            document.getElementById('stockModal').style.display = 'flex';
        });
    }

    document.getElementById('stockActionBtn').addEventListener('click', function() {
        let quantity = parseInt(document.getElementById('stockQuantity').value);
        if (isNaN(quantity) || quantity <= 0) {
            alert('Please enter a valid quantity');
            return;
        }
        let change = stockAction === 'add' ? quantity : -quantity;
        fetch('index.php?action=adjust-stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: currentProduct.id,
                quantity_change: change
            })
        }).then(() => location.reload());
    });

    // Category Management
    function openCategoryModal() {
        document.getElementById('categoryModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let name = document.getElementById('newCategoryName').value;
        if (!name) return;
        fetch('index.php?action=category-add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'name=' + encodeURIComponent(name)
        }).then(() => location.reload());
    });

    function editCategory(id, oldName) {
        let newName = prompt('Edit category name:', oldName);
        if (newName && newName !== oldName) {
            fetch('index.php?action=category-edit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id + '&name=' + encodeURIComponent(newName)
            }).then(() => location.reload());
        }
    }

    function deleteCategory(id) {
        if (confirm('Delete this category? (Only empty categories can be deleted)')) {
            fetch('index.php?action=category-delete&id=' + id).then(() => location.reload());
        }
    }

    // Product CRUD 
    function openProductModal() {
        document.getElementById('modalTitle').innerText = 'Add Product';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        // Remove any existing action attribute to use default
        document.getElementById('productForm').removeAttribute('action');
        document.getElementById('productModal').style.display = 'flex';
    }

    function editProduct(id) {
        fetch('index.php?action=get-product&id=' + id)
            .then(res => res.json())
            .then(product => {
                document.getElementById('modalTitle').innerText = 'Edit Product';
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productSku').value = product.sku;
                document.getElementById('productCategory').value = product.category_id;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productQuantity').value = product.quantity;
                document.getElementById('productMinStock').value = product.min_stock_level;
                document.getElementById('productDescription').value = product.description;
                if (product.image) {
                    document.getElementById('productImage').value = product.image;
                    document.getElementById('previewImg').src = product.image;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                // Set action for update
                document.getElementById('productForm').setAttribute('action', 'index.php?action=products-update');
                document.getElementById('productModal').style.display = 'flex';
            });
    }

    function viewProduct(id) {
        fetch('index.php?action=get-product&id=' + id)
            .then(res => res.json())
            .then(p => {
                alert(`Product Details:\n\nName: ${p.name}\nSKU: ${p.sku}\nPrice: $${parseFloat(p.price).toFixed(2)}\nStock: ${p.quantity}\nCategory: ${p.category_name || 'Uncategorized'}`);
            });
    }

    function deleteProduct(id) {
        if (confirm('Delete this product?')) {
            window.location.href = 'index.php?action=products-delete&id=' + id;
        }
    }

    // Ensure form submits normally - NO PREVENT DEFAULT
    // document.getElementById('productForm').onsubmit = function() {
    //     console.log('Form submitting to:', this.action);
    //     return true; // Allow normal form submission
    // };
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>