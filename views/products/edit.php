<?php
$page_title = 'Edit Product';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Product</h2>
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

    <form method="POST" action="index.php?action=products-update" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">SKU</label>
                    <input type="text" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Category</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"
                                <?php echo ($category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Selling Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="price" step="0.01" required value="<?php echo $product['price']; ?>"
                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Cost Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="cost_price" step="0.01" value="<?php echo $product['cost_price']; ?>"
                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Current Quantity</label>
                    <input type="number" name="quantity" required value="<?php echo $product['quantity']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Minimum Stock Alert Level</label>
                    <input type="number" name="min_stock_level" value="<?php echo $product['min_stock_level']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <!-- Current Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Current Image</label>
                    <?php if ($product['image']): ?>
                        <div class="mb-3">
                            <img src="<?php echo BASE_URL . $product['image']; ?>" class="w-32 h-32 object-cover rounded-lg shadow">
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">No image uploaded</p>
                    <?php endif; ?>
                </div>

                <!-- New Image -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Change Image (optional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                        onclick="document.getElementById('image-input').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Click to upload new image</p>
                        <input type="file" name="image" id="image-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div id="image-preview" class="mt-3 hidden">
                        <img id="preview-img" class="w-32 h-32 object-cover rounded-lg shadow">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t">
            <a href="index.php?action=products" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-save mr-2"></i> Update Product
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