<?php
$page_title = 'Add New Product';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Add New Product</h2>
        <a href="index.php?action=products" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Products
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 alert">
            <?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=products-store" enctype="multipart/form-data" class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Product Name -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="Enter product name">
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">SKU (Stock Keeping Unit)</label>
                    <input type="text" name="sku"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="Auto-generated if empty">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        placeholder="Product description..."></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Price -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Selling Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="price" step="0.01" required
                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="0.00">
                    </div>
                </div>

                <!-- Cost Price -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Cost Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="cost_price" step="0.01"
                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                            placeholder="0.00">
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Initial Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" required value="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Min Stock Level -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Minimum Stock Alert Level</label>
                    <input type="number" name="min_stock_level" value="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Alert when stock drops below this number</p>
                </div>

                <!-- Product Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Product Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                        onclick="document.getElementById('image-input').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-400">JPG, PNG, GIF, WEBP (Max 2MB)</p>
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div id="image-preview" class="mt-3 hidden">
                        <img id="preview-img" class="w-32 h-32 object-cover rounded-lg shadow">
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-4 border-t">
            <a href="index.php?action=products" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-save mr-2"></i> Save Product
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>