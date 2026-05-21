<?php
require_once BASE_PATH . '/models/Sale.php';

class ReportController
{
    private $db;
    private $saleModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->saleModel = new Sale($db);
    }

    public function index()
    {
        $this->checkAuth();

        // Sales Report Data
        $result = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE payment_status = 'paid'");
        $total_revenue = $result->fetch_assoc()['total'];

        $result = $this->db->query("SELECT COUNT(*) as count FROM sales WHERE payment_status = 'paid'");
        $total_transactions = $result->fetch_assoc()['count'];

        $result = $this->db->query("SELECT COALESCE(SUM(quantity), 0) as total FROM sale_items");
        $total_items_sold = $result->fetch_assoc()['total'];

        // Top products
        $top_products = $this->saleModel->getTopProducts(5);

        // Monthly sales
        $result = $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total FROM sales WHERE payment_status = 'paid' GROUP BY month ORDER BY month DESC LIMIT 6");
        $monthly_sales = $result->fetch_all(MYSQLI_ASSOC);

        // Inventory Report Data
        $result = $this->db->query("SELECT id, name, quantity, min_stock_level FROM products WHERE is_active = 1 ORDER BY name");
        $inventory_items = $result->fetch_all(MYSQLI_ASSOC);

        // Category stock value
        $result = $this->db->query("SELECT c.name, COALESCE(SUM(p.price * p.quantity), 0) as stock_value FROM categories c LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1 GROUP BY c.id ORDER BY stock_value DESC");
        $category_stock = $result->fetch_all(MYSQLI_ASSOC);

        // Profit Report Data
        $result = $this->db->query("SELECT p.name, COALESCE(SUM(si.quantity), 0) as total_sold, COALESCE(SUM(si.subtotal), 0) as revenue, COALESCE(SUM(si.quantity * p.cost_price), 0) as total_cost, COALESCE(SUM(si.subtotal - (si.quantity * p.cost_price)), 0) as profit FROM sale_items si JOIN products p ON si.product_id = p.id GROUP BY p.id ORDER BY profit DESC LIMIT 10");
        $profit_data = $result->fetch_all(MYSQLI_ASSOC);

        // Customer Report Data
        $result = $this->db->query("SELECT customer_name, COUNT(*) as purchase_count, COALESCE(SUM(total_amount), 0) as total_spent, MAX(created_at) as last_purchase FROM sales WHERE customer_name != 'Walk-in Customer' AND payment_status = 'paid' GROUP BY customer_name ORDER BY total_spent DESC LIMIT 10");
        $customer_data = $result->fetch_all(MYSQLI_ASSOC);

        require_once BASE_PATH . '/views/reports/index.php';
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
}
