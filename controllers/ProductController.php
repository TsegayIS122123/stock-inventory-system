<?php
require_once BASE_PATH . '/models/Product.php';
require_once BASE_PATH . '/models/Category.php';

class ProductController
{
    private $productModel;
    private $categoryModel;

    public function __construct($db)
    {
        $this->productModel = new Product($db);
        $this->categoryModel = new Category($db);
    }

    // List all products
    public function index()
    {
        $this->checkAuth();

        $products = $this->productModel->getAll();
        require_once BASE_PATH . '/views/products/index.php';
    }

    // Show create product form
    public function create()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        $categories = $this->categoryModel->getAll();
        require_once BASE_PATH . '/views/products/create.php';
    }

    // Store product
    public function store()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=products');
            return;
        }

        // Handle image upload (Chapter 3)
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_path = $this->uploadImage($_FILES['image']);
        }

        // Set product properties
        $this->productModel->name = $_POST['name'];
        $this->productModel->description = $_POST['description'] ?? '';
        $this->productModel->sku = $_POST['sku'];
        $this->productModel->price = $_POST['price'];
        $this->productModel->cost_price = $_POST['cost_price'] ?? 0;
        $this->productModel->quantity = $_POST['quantity'];
        $this->productModel->min_stock_level = $_POST['min_stock_level'] ?? 5;
        $this->productModel->category_id = $_POST['category_id'] ?? null;
        $this->productModel->image = $image_path;

        if ($this->productModel->create()) {
            $_SESSION['success'] = "Product added successfully";
            header('Location: index.php?action=products');
        } else {
            $_SESSION['error'] = "Failed to add product";
            header('Location: index.php?action=products-create');
        }
    }

    // Edit product form
    public function edit()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        $id = $_GET['id'] ?? 0;
        $product = $this->productModel->getById($id);
        $categories = $this->categoryModel->getAll();

        require_once BASE_PATH . '/views/products/edit.php';
    }

    // Update product
    public function update()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=products');
            return;
        }

        $this->productModel->id = $_POST['id'];
        $this->productModel->name = $_POST['name'];
        $this->productModel->description = $_POST['description'] ?? '';
        $this->productModel->sku = $_POST['sku'];
        $this->productModel->price = $_POST['price'];
        $this->productModel->cost_price = $_POST['cost_price'] ?? 0;
        $this->productModel->quantity = $_POST['quantity'];
        $this->productModel->min_stock_level = $_POST['min_stock_level'] ?? 5;
        $this->productModel->category_id = $_POST['category_id'] ?? null;

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $this->productModel->image = $this->uploadImage($_FILES['image']);
        }

        if ($this->productModel->update()) {
            $_SESSION['success'] = "Product updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update product";
        }

        header('Location: index.php?action=products');
    }

    // Delete product
    public function delete()
    {
        $this->checkAuth();
        $this->checkRole(['admin']);

        $this->productModel->id = $_GET['id'] ?? 0;

        if ($this->productModel->delete()) {
            $_SESSION['success'] = "Product deleted successfully";
        } else {
            $_SESSION['error'] = "Failed to delete product";
        }

        header('Location: index.php?action=products');
    }

    // Search products
    public function search()
    {
        $this->checkAuth();

        $keyword = $_GET['q'] ?? '';
        $products = $this->productModel->search($keyword);

        // Return JSON for AJAX requests
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($products);
            exit();
        }

        require_once BASE_PATH . '/views/products/index.php';
    }

    // Upload image
    private function uploadImage($file)
    {
        $target_dir = PRODUCT_IMG_DIR;
        $filename = time() . '_' . basename($file["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF & WEBP files are allowed";
            return null;
        }

        if ($file["size"] > 2000000) {
            $_SESSION['error'] = "File is too large (max 2MB)";
            return null;
        }

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return 'uploads/products/' . $filename;
        }

        return null;
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    private function checkRole($roles)
    {
        if (!in_array($_SESSION['role'], $roles)) {
            $_SESSION['error'] = "You don't have permission to access this page";
            header('Location: index.php?action=dashboard');
            exit();
        }
    }
}
