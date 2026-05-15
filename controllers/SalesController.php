<?php
require_once BASE_PATH . '/models/Sale.php';
require_once BASE_PATH . '/models/Product.php';

class SalesController
{
    private $saleModel;
    private $productModel;

    public function __construct($db)
    {
        $this->saleModel = new Sale($db);
        $this->productModel = new Product($db);

        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Show sales list
    public function index()
    {
        $this->checkAuth();

        $sales = $this->saleModel->getAll();
        require_once BASE_PATH . '/views/sales/index.php';
    }

    // Show POS / cart page
    public function create()
    {
        $this->checkAuth();

        $products = $this->productModel->getAll();
        $cart_items = $this->getCartItems();

        require_once BASE_PATH . '/views/sales/create.php';
    }

    // Add to cart
    public function addToCart()
    {
        $this->checkAuth();

        $product_id = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;

        $product = $this->productModel->getById($product_id);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
            return;
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image']
            ];
        }

        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    }

    // Update cart
    public function updateCart()
    {
        $this->checkAuth();

        $product_id = $_POST['product_id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 0;

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }

        echo json_encode(['success' => true]);
    }

    // Remove from cart
    public function removeFromCart()
    {
        $this->checkAuth();

        $product_id = $_GET['id'] ?? 0;
        unset($_SESSION['cart'][$product_id]);

        header('Location: index.php?action=sales-create');
    }

    // Process checkout
    public function checkout()
    {
        $this->checkAuth();

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Cart is empty";
            header('Location: index.php?action=sales-create');
            return;
        }

        $customer_name = $_POST['customer_name'] ?? 'Walk-in Customer';
        $payment_method = $_POST['payment_method'] ?? 'cash';
        $discount = $_POST['discount'] ?? 0;

        $items = [];
        $subtotal = 0;

        foreach ($_SESSION['cart'] as $item) {
            $items[] = [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ];
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculate totals
        $discount_amount = $discount;
        $tax_rate = 0; // 0% for simplicity
        $tax_amount = ($subtotal - $discount_amount) * ($tax_rate / 100);
        $total = ($subtotal - $discount_amount) + $tax_amount;

        $sale_id = $this->saleModel->create(
            $_SESSION['user_id'],
            $customer_name,
            $items,
            $subtotal,
            $discount_amount,
            $tax_amount,
            $total,
            $payment_method
        );

        if ($sale_id) {
            // Clear cart
            $_SESSION['cart'] = [];
            $_SESSION['success'] = "Sale completed successfully!";
            header("Location: index.php?action=sales-invoice&id=$sale_id");
        } else {
            $_SESSION['error'] = "Failed to process sale";
            header('Location: index.php?action=sales-create');
        }
    }

    // Show invoice
    public function invoice()
    {
        $this->checkAuth();

        $id = $_GET['id'] ?? 0;
        $sale = $this->saleModel->getById($id);

        if (!$sale) {
            $_SESSION['error'] = "Sale not found";
            header('Location: index.php?action=sales');
            return;
        }

        require_once BASE_PATH . '/views/sales/invoice.php';
    }

    // Get cart items with details
    private function getCartItems()
    {
        $items = [];
        $total = 0;

        foreach ($_SESSION['cart'] as $item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            $total += $item['subtotal'];
            $items[] = $item;
        }

        return ['items' => $items, 'total' => $total];
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
}
