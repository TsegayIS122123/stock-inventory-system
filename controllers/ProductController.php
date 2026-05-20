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
        $categories = $this->categoryModel->getAll();

        require_once BASE_PATH . '/views/products/index.php';
    }

    // Show create product form (not used in new modal system)
    public function create()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        // Redirect to products page with modal
        header('Location: index.php?action=products');
        exit();
    }

    // Store product
    public function store()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method";
            header('Location: index.php?action=products');
            exit();
        }

        // Get form data
        $name = trim($_POST['name'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $sku = trim($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $min_stock = intval($_POST['min_stock_level'] ?? 5);
        $image = !empty($_POST['image_url']) ? trim($_POST['image_url']) : null;

        // Generate SKU if empty
        if (empty($sku)) {
            $sku = 'PROD-' . strtoupper(uniqid());
        }

        // Validate
        if (empty($name) || $price <= 0) {
            $_SESSION['error'] = "Product name and valid price are required";
            header('Location: index.php?action=products');
            exit();
        }

        // Set properties
        $this->productModel->name = $name;
        $this->productModel->description = $description;
        $this->productModel->sku = $sku;
        $this->productModel->price = $price;
        $this->productModel->cost_price = floatval($_POST['cost_price'] ?? 0);
        $this->productModel->quantity = $quantity;
        $this->productModel->min_stock_level = $min_stock;
        $this->productModel->category_id = $category_id;
        $this->productModel->image = $image;

        // Create product
        if ($this->productModel->create()) {
            $_SESSION['success'] = "Product added successfully";
            header('Location: index.php?action=products');
            exit();
        } else {
            $_SESSION['error'] = "Failed to add product. Please check your data.";
            header('Location: index.php?action=products');
            exit();
        }
    }

    // Edit product form (not used - we use modal)
    public function edit()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        header('Location: index.php?action=products');
        exit();
    }

    // Update product
    public function update()
    {
        $this->checkAuth();
        $this->checkRole(['admin', 'manager']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Invalid request method";
            header('Location: index.php?action=products');
            exit();
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = "Invalid product ID";
            header('Location: index.php?action=products');
            exit();
        }

        // Get form data
        $name = trim($_POST['name'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);
        $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $sku = trim($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $min_stock = intval($_POST['min_stock_level'] ?? 5);

        // Handle image
        $image = null;
        if (!empty($_POST['image_url'])) {
            $image = trim($_POST['image_url']);
        } elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        // Validate
        if (empty($name) || $price <= 0) {
            $_SESSION['error'] = "Product name and valid price are required";
            header('Location: index.php?action=products');
            exit();
        }

        // Set product properties
        $this->productModel->id = $id;
        $this->productModel->name = $name;
        $this->productModel->description = $description;
        $this->productModel->sku = $sku;
        $this->productModel->price = $price;
        $this->productModel->cost_price = floatval($_POST['cost_price'] ?? 0);
        $this->productModel->quantity = $quantity;
        $this->productModel->min_stock_level = $min_stock;
        $this->productModel->category_id = $category_id;

        // Only update image if a new one was provided
        if ($image) {
            $this->productModel->image = $image;
        }

        if ($this->productModel->update()) {
            $_SESSION['success'] = "Product updated successfully";
            header('Location: index.php?action=products');
            exit();
        } else {
            $_SESSION['error'] = "Failed to update product";
            header('Location: index.php?action=products');
            exit();
        }
    }

    // Show single product details
    public function show()
    {
        $this->checkAuth();

        $id = $_GET['id'] ?? 0;
        $product = $this->productModel->getById($id);

        if (!$product) {
            $_SESSION['error'] = "Product not found";
            header('Location: index.php?action=products');
            return;
        }

        require_once BASE_PATH . '/views/products/show.php';
    }

    // Get single product as JSON
    public function getProductJson()
    {
        $this->checkAuth();

        $id = $_GET['id'] ?? 0;
        $product = $this->productModel->getById($id);

        // Ensure category_name is included
        if ($product && !isset($product['category_name'])) {
            $category = $this->categoryModel->getById($product['category_id']);
            $product['category_name'] = $category ? $category['name'] : null;
        }

        header('Content-Type: application/json');
        echo json_encode($product);
        exit();
    }

    // Adjust stock
    public function adjustStock()
    {
        $this->checkAuth();

        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = $data['product_id'] ?? 0;
        $quantity_change = intval($data['quantity_change'] ?? 0);

        if ($product_id && $quantity_change != 0) {
            $result = $this->productModel->adjustStock($product_id, $quantity_change, 'manual');
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit();
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
        exit();
    }

    // Search products
    public function search()
    {
        $this->checkAuth();

        $keyword = $_GET['q'] ?? '';
        $products = $this->productModel->search($keyword);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode($products);
            exit();
        }

        require_once BASE_PATH . '/views/products/index.php';
    }

    // Generate SKU
    private function generateSKU()
    {
        return 'PROD-' . strtoupper(uniqid());
    }

    // Upload image
    private function uploadImage($file)
    {
        // Create uploads directory if not exists
        $upload_dir = BASE_PATH . '/public/uploads/products/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', basename($file["name"]));
        $target_file = $upload_dir . $filename;
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
            return BASE_URL . 'public/uploads/products/' . $filename;
        }

        return null;
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please login first";
            header('Location: index.php?action=login');
            exit();
        }
    }

    private function checkRole($roles)
    {
        // Normalize role to lowercase for comparison
        $userRole = strtolower(trim($_SESSION['role'] ?? ''));
        $roles = array_map('strtolower', $roles);
        
        if (!in_array($userRole, $roles)) {
            $_SESSION['error'] = "You don't have permission. Your role is: " . $_SESSION['role'];
            header('Location: index.php?action=products');
            exit();
        }
    }
}
