<?php
$page_title = 'Products';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

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
                                <button onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>')" class="text-red-600 hover:text-red-800 text-sm" title="Delete"><i class="fas fa-trash"></i></button>
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
                    <div><label class="block text-xs font-medium mb-1">Selling Price *</label><input type="number" name="price" id="productPrice" step="0.01" required class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                    <div><label class="block text-xs font-medium mb-1">Cost Price</label><input type="number" name="cost_price" id="productCostPrice" step="0.01" class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-medium mb-1">Quantity</label><input type="number" name="quantity" id="productQuantity" value="0" class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                    <div><label class="block text-xs font-medium mb-1">Min Stock Alert</label><input type="number" name="min_stock_level" id="productMinStock" value="5" class="w-full px-3 py-1.5 text-sm border rounded-lg"></div>
                </div>
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
            showToast('Please enter a valid quantity', 'error');
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

    // ========== PRODUCT CRUD WITH MODALS ==========

    function openProductModal() {
        document.getElementById('modalTitle').innerText = 'Add Product';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('productForm').setAttribute('action', 'index.php?action=products-store');
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
                document.getElementById('productCostPrice').value = product.cost_price;
                document.getElementById('productQuantity').value = product.quantity;
                document.getElementById('productMinStock').value = product.min_stock_level;
                document.getElementById('productDescription').value = product.description;
                if (product.image) {
                    document.getElementById('productImage').value = product.image;
                    document.getElementById('previewImg').src = product.image;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                document.getElementById('productForm').setAttribute('action', 'index.php?action=products-update');
                document.getElementById('productModal').style.display = 'flex';
            });
    }

    // ========== NEW: VIEW PRODUCT MODAL (Beautiful) ==========
    function viewProduct(id) {
        fetch('index.php?action=get-product&id=' + id)
            .then(res => res.json())
            .then(product => {
                // Create view modal dynamically
                let viewModalHtml = `
                    <div id="viewProductModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                        <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
                            <div class="sticky top-0 bg-white border-b px-5 py-3 flex justify-between items-center">
                                <h2 class="font-semibold text-lg">Product Details</h2>
                                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="p-5 space-y-3">
                                <div class="flex justify-center">
                                    ${product.image ? `<img src="${product.image}" class="w-32 h-32 object-cover rounded-lg shadow">` : '<div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400 text-4xl"></i></div>'}
                                </div>
                                <div class="border-b pb-2">
                                    <p class="text-xs text-gray-500">Product Name</p>
                                    <p class="font-semibold text-gray-800">${escapeHtml(product.name)}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs text-gray-500">SKU</p>
                                        <p class="font-medium text-sm">${product.sku || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Category</p>
                                        <p class="font-medium text-sm">${product.category_name || 'Uncategorized'}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Selling Price</p>
                                        <p class="font-bold text-blue-600">$${parseFloat(product.price).toFixed(2)}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Cost Price</p>
                                        <p class="font-medium text-sm">$${parseFloat(product.cost_price).toFixed(2)}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Current Stock</p>
                                        <p class="font-bold ${product.quantity <= product.min_stock_level ? 'text-red-600' : 'text-green-600'}">${product.quantity} units</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Min Stock Level</p>
                                        <p class="font-medium text-sm">${product.min_stock_level} units</p>
                                    </div>
                                </div>
                                ${product.description ? `<div><p class="text-xs text-gray-500">Description</p><p class="text-sm text-gray-700">${escapeHtml(product.description)}</p></div>` : ''}
                                <div class="pt-3 text-xs text-gray-400 border-t">
                                    <p>Created: ${new Date(product.created_at).toLocaleString()}</p>
                                    <p>Last Updated: ${new Date(product.updated_at).toLocaleString()}</p>
                                </div>
                                <div class="flex justify-end gap-2 pt-3">
                                    <button onclick="closeViewModal(); editProduct(${product.id})" class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <button onclick="closeViewModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-400">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                // Remove existing view modal if any
                if (document.getElementById('viewProductModal')) {
                    document.getElementById('viewProductModal').remove();
                }
                document.body.insertAdjacentHTML('beforeend', viewModalHtml);
            });
    }

    function closeViewModal() {
        const modal = document.getElementById('viewProductModal');
        if (modal) modal.remove();
    }

    // ========== NEW: DELETE CONFIRM MODAL (Beautiful) ==========
    function deleteProduct(id, name) {
        // Show beautiful confirmation modal
        let deleteModalHtml = `
            <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg w-full max-w-sm">
                    <div class="p-5 text-center">
                        <div class="mx-auto w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-trash-alt text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirm Delete</h3>
                        <p class="text-gray-500 text-sm mb-4">Are you sure you want to delete "<span class="font-semibold">${escapeHtml(name)}</span>"? This action cannot be undone.</p>
                        <div class="flex justify-center gap-3">
                            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </button>
                            <button onclick="confirmDeleteProduct(${id})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (document.getElementById('deleteConfirmModal')) {
            document.getElementById('deleteConfirmModal').remove();
        }
        document.body.insertAdjacentHTML('beforeend', deleteModalHtml);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        if (modal) modal.remove();
    }

    function confirmDeleteProduct(id) {
        window.location.href = 'index.php?action=products-delete&id=' + id;
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
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