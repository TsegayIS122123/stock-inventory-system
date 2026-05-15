<?php
// ======================================================
// Sale Model - Handles sales transactions
// ======================================================

class Sale
{
    private $conn;
    private $table = 'sales';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create new sale
    public function create($user_id, $customer_name, $items, $subtotal, $discount, $tax, $total, $payment_method)
    {
        $invoice_no = $this->generateInvoiceNo();

        $this->conn->begin_transaction();

        try {
            // Insert sale
            $query = "INSERT INTO " . $this->table . " 
                      (invoice_no, user_id, customer_name, subtotal, discount_amount, tax_amount, total_amount, payment_method) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sisdddd s", $invoice_no, $user_id, $customer_name, $subtotal, $discount, $tax, $total, $payment_method);
            $stmt->execute();

            $sale_id = $this->conn->insert_id;

            // Insert sale items and update stock
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

    // Add sale item
    private function addSaleItem($sale_id, $product_id, $quantity, $price)
    {
        $subtotal = $quantity * $price;

        $query = "INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiidd", $sale_id, $product_id, $quantity, $price, $subtotal);
        $stmt->execute();
    }

    // Update product stock
    private function updateProductStock($product_id, $quantity)
    {
        $query = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
    }

    // Generate invoice number
    private function generateInvoiceNo()
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

    // Get all sales
    public function getAll($limit = 100)
    {
        $query = "SELECT s.*, u.username as cashier_name 
                  FROM " . $this->table . " s 
                  LEFT JOIN users u ON s.user_id = u.id 
                  ORDER BY s.created_at DESC 
                  LIMIT $limit";

        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    // Get sale by ID
    public function getById($id)
    {
        $query = "SELECT s.*, u.username as cashier_name 
                  FROM " . $this->table . " s 
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

    // Get sale items
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

    // Get sales statistics
    public function getStats()
    {
        $stats = [];

        // Today's sales
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total, COUNT(*) as count 
                                      FROM " . $this->table . " 
                                      WHERE DATE(created_at) = CURDATE() AND payment_status = 'paid'");
        $stats['today'] = $result->fetch_assoc();

        // This month sales
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total, COUNT(*) as count 
                                      FROM " . $this->table . " 
                                      WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        $stats['month'] = $result->fetch_assoc();

        // Total sales all time
        $result = $this->conn->query("SELECT COALESCE(SUM(total_amount), 0) as total, COUNT(*) as count 
                                      FROM " . $this->table);
        $stats['total'] = $result->fetch_assoc();

        return $stats;
    }
}
