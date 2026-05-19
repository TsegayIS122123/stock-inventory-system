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

        // Initialize cart in session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Main sales page - shows both POS and history
    public function index()
    {
        $this->checkAuth();

        $products = $this->productModel->getAll();
        $sales = $this->saleModel->getAll();
        $todaySales = $this->saleModel->getTodaySales();
        $todayTransactions = $this->saleModel->getTodayTransactions();
        $cartItems = $this->getCartItems();

        require_once BASE_PATH . '/views/sales/index.php';
    }

    // Add to cart (AJAX)
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

        if ($product['quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock! Available: ' . $product['quantity']]);
            return;
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $newQty = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
            if ($newQty > $product['quantity']) {
                echo json_encode(['success' => false, 'message' => 'Not enough stock!']);
                return;
            }
            $_SESSION['cart'][$product_id]['quantity'] = $newQty;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image'],
                'max_stock' => $product['quantity']
            ];
        }

        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
    }

    // Update cart quantity (AJAX)
    public function updateCart()
    {
        $this->checkAuth();

        $product_id = $_POST['product_id'] ?? 0;
        $quantity = intval($_POST['quantity'] ?? 0);

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $product = $this->productModel->getById($product_id);
            if ($product && $quantity <= $product['quantity']) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
                return;
            }
        }

        echo json_encode(['success' => true]);
    }

    // Remove from cart
    public function removeFromCart()
    {
        $this->checkAuth();

        $product_id = $_GET['id'] ?? 0;
        unset($_SESSION['cart'][$product_id]);

        header('Location: index.php?action=sales');
    }

    // Process checkout
    public function checkout()
    {
        $this->checkAuth();

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Cart is empty";
            header('Location: index.php?action=sales');
            return;
        }

        $customer_name = trim($_POST['customer_name'] ?? '');
        if (empty($customer_name)) {
            $customer_name = 'Walk-in Customer';
        }

        $payment_method = $_POST['payment_method'] ?? 'cash';

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

        $tax_rate = 0; // No tax for simplicity
        $tax_amount = 0;
        $discount_amount = 0;
        $total = $subtotal;

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
            $_SESSION['cart'] = [];
            $_SESSION['success'] = "Sale completed successfully! Invoice #" . $this->saleModel->generateInvoiceNo();
            header("Location: index.php?action=sales");
        } else {
            $_SESSION['error'] = "Failed to process sale";
            header('Location: index.php?action=sales');
        }
    }

    // Get invoice
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

    // Get cart items with totals
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
