<?php
class Sale
{
    private $conn;
    private $table = 'sales';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Generate invoice number
    public function generateInvoiceNo()
    {
        $year = date('Y');
        $month = date('m');

        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $counter = str_pad($result['count'] + 1, 4, '0', STR_PAD_LEFT);
        return "INV-{$year}{$month}-{$counter}";
    }

    // Create new sale
    public function create($user_id, $customer_name, $items, $subtotal, $discount, $tax, $total, $payment_method)
    {
        $invoice_no = $this->generateInvoiceNo();

        $this->conn->begin_transaction();

        try {
            $query = "INSERT INTO sales (invoice_no, user_id, customer_name, subtotal, discount_amount, tax_amount, total_amount, payment_method) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sisdddd s", $invoice_no, $user_id, $customer_name, $subtotal, $discount, $tax, $total, $payment_method);
            $stmt->execute();

            $sale_id = $this->conn->insert_id;

            foreach ($items as $item) {
                $this->addSaleItem($sale_id, $item['product_id'], $item['quantity'], $item['price']);
                $this->updateProductStock($item['product_id'], $item['quantity']);
            }

            $this->conn->commit();
            return $sale_id;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    private function addSaleItem($sale_id, $product_id, $quantity, $price)
    {
        $subtotal = $quantity * $price;

        $query = "INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiidd", $sale_id, $product_id, $quantity, $price, $subtotal);
        $stmt->execute();
    }

    private function updateProductStock($product_id, $quantity)
    {
        $query = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
    }

    // Get all sales
    public function getAll($limit = 100)
    {
        $query = "SELECT s.*, u.username as cashier_name 
                  FROM sales s 
                  LEFT JOIN users u ON s.user_id = u.id 
                  ORDER BY s.created_at DESC 
                  LIMIT $limit";

        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    // Get sale by ID
    public function getById($id)
    {
        $query = "SELECT s.*, u.username as cashier_name 
                  FROM sales s 
                  LEFT JOIN users u ON s.user_id = u.id 
                  WHERE s.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $sale = $stmt->get_result()->fetch_assoc();

        if ($sale) {
            $sale['items'] = $this->getSaleItems($id);
        }

        return $sale;
    }

    public function getSaleItems($sale_id)
    {
        $query = "SELECT si.*, p.name as product_name, p.sku 
                  FROM sale_items si 
                  JOIN products p ON si.product_id = p.id 
                  WHERE si.sale_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sale_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ========== ADD THESE MISSING METHODS ==========

    // Get sales statistics
    public function getStats()
    {
        $stats = [];

        // Today's sales
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total, COUNT(*) as count FROM sales WHERE DATE(created_at) = CURDATE() AND payment_status = 'paid'");
        $stats['today'] = $result->fetch_assoc();

        // This month sales
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total, COUNT(*) as count FROM sales WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        $stats['month'] = $result->fetch_assoc();

        // Total sales all time
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total, COUNT(*) as count FROM sales");
        $stats['total'] = $result->fetch_assoc();

        return $stats;
    }

    // Get today's sales
    public function getTodaySales()
    {
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE DATE(created_at) = CURDATE() AND payment_status = 'paid'");
        return floatval($result->fetch_assoc()['total']);
    }

    // Get today's transactions
    public function getTodayTransactions()
    {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM sales WHERE DATE(created_at) = CURDATE()");
        return $result->fetch_assoc()['count'];
    }

    // Get top products
    public function getTopProducts($limit = 5)
    {
        $query = "SELECT p.id, p.name, SUM(si.quantity) as total_sold, SUM(si.subtotal) as revenue 
                  FROM sale_items si 
                  JOIN products p ON si.product_id = p.id 
                  GROUP BY p.id 
                  ORDER BY total_sold DESC 
                  LIMIT $limit";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get recent sales
    public function getRecent($limit = 5)
    {
        $query = "SELECT s.*, u.username as cashier_name 
                  FROM sales s 
                  LEFT JOIN users u ON s.user_id = u.id 
                  ORDER BY s.created_at DESC 
                  LIMIT $limit";
        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    // Get total profit
    public function getTotalProfit()
    {
        $result = $this->conn->query("SELECT SUM(si.quantity * (si.price - p.cost_price)) as profit 
                                      FROM sale_items si 
                                      JOIN products p ON si.product_id = p.id");
        $profit = $result->fetch_assoc()['profit'];
        return floatval($profit ?? 0);
    }

    // Get total transactions
    public function getTotalTransactions()
    {
        $result = $this->conn->query("SELECT COUNT(*) as count FROM sales");
        return $result->fetch_assoc()['count'];
    }

    // Get average transaction
    public function getAverageTransaction()
    {
        $result = $this->conn->query("SELECT AVG(total_amount) as avg FROM sales");
        return floatval($result->fetch_assoc()['avg'] ?? 0);
    }

    // Get weekly sales
    public function getWeeklySales()
    {
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        return floatval($result->fetch_assoc()['total']);
    }

    // Get monthly sales
    public function getMonthlySales()
    {
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        return floatval($result->fetch_assoc()['total']);
    }
}
