<?php
// ======================================================
// Product Model - CRUD Operations 
// ======================================================

class Product
{
    private $conn;
    private $table = 'products';

    public $id;
    public $name;
    public $description;
    public $sku;
    public $price;
    public $cost_price;
    public $quantity;
    public $min_stock_level;
    public $category_id;
    public $image;
    public $is_active;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create product (CRUD - Create)
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (name, description, sku, price, cost_price, quantity, min_stock_level, category_id, image) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sssdddiis",
            $this->name,
            $this->description,
            $this->sku,
            $this->price,
            $this->cost_price,
            $this->quantity,
            $this->min_stock_level,
            $this->category_id,
            $this->image
        );

        if ($stmt->execute()) {
            $this->id = $this->conn->insert_id;

            // Log stock change - FIX: Pass only 3 parameters
            $this->logStockChange($this->id, $this->quantity, 'purchase');

            return true;
        }

        return false;
    }

    // Get all products (CRUD - Read)
    public function getAll($limit = null, $offset = 0)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.is_active = 1 
                  ORDER BY p.created_at DESC";

        if ($limit) {
            $query .= " LIMIT $limit OFFSET $offset";
        }

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get product by ID
    public function getById($id)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Update product (CRUD - Update)
    public function update()
    {
        $old_product = $this->getById($this->id);

        $query = "UPDATE " . $this->table . " 
                  SET name = ?, description = ?, sku = ?, price = ?, 
                      cost_price = ?, quantity = ?, min_stock_level = ?, 
                      category_id = ?, image = ? 
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sssdddiisi",
            $this->name,
            $this->description,
            $this->sku,
            $this->price,
            $this->cost_price,
            $this->quantity,
            $this->min_stock_level,
            $this->category_id,
            $this->image,
            $this->id
        );

        $result = $stmt->execute();

        if ($result && $old_product['quantity'] != $this->quantity) {
            $change = $this->quantity - $old_product['quantity'];
            // FIX: Pass only 3 parameters (product_id, change, type)
            $this->logStockChange($this->id, $change, 'adjustment');
        }

        return $result;
    }

    // Delete product (CRUD - Delete)
    public function delete()
    {
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }

    // Reduce stock (during sale)
    public function reduceStock($product_id, $quantity)
    {
        $query = "UPDATE " . $this->table . " SET quantity = quantity - ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $quantity, $product_id);

        return $stmt->execute();
    }

    // Check low stock products
    public function getLowStockProducts()
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE quantity <= min_stock_level AND quantity > 0 AND is_active = 1";

        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    // Get out of stock products
    public function getOutOfStock()
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE quantity = 0 AND is_active = 1";

        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    // Search products
    public function search($keyword)
    {
        $keyword = "%{$keyword}%";
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.name LIKE ? OR p.sku LIKE ? OR c.name LIKE ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get product statistics
    public function getStats()
    {
        $stats = [];

        // Total products
        $result = $this->conn->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1");
        $stats['total_products'] = $result->fetch_assoc()['total'];

        // Total stock value
        $result = $this->conn->query("SELECT SUM(price * quantity) as value FROM " . $this->table);
        $stats['total_value'] = $result->fetch_assoc()['value'] ?? 0;

        // Low stock count
        $stats['low_stock'] = count($this->getLowStockProducts());

        // Out of stock count
        $stats['out_of_stock'] = count($this->getOutOfStock());

        return $stats;
    }

    // Log stock changes - FIXED VERSION
    // FIX: Method now accepts exactly 3 parameters (product_id, change, type)
    private function logStockChange($product_id, $change, $type)
    {
        $query = "INSERT INTO stock_logs (product_id, user_id, quantity_change, new_quantity, type) 
              VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $user_id = $_SESSION['user_id'] ?? 1;
        $new_quantity = $change; // For new products
        $stmt->bind_param("iiids", $product_id, $user_id, $change, $new_quantity, $type);
        $stmt->execute();
    }

    // Add this method to Product class
    public function adjustStock($product_id, $quantity_change, $reason = 'adjustment')
    {
        $current = $this->getById($product_id);
        $new_quantity = $current['quantity'] + $quantity_change;

        if ($new_quantity < 0) {
            return false; // Cannot go below zero
        }

        $this->conn->begin_transaction();

        try {
            // Update product quantity
            $query = "UPDATE " . $this->table . " SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $new_quantity, $product_id);
            $stmt->execute();

            // Log stock change
            $query = "INSERT INTO stock_logs (product_id, user_id, quantity_change, previous_quantity, new_quantity, type) 
                  VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $user_id = $_SESSION['user_id'] ?? 1;
            $stmt->bind_param("iiiids", $product_id, $user_id, $quantity_change, $current['quantity'], $new_quantity, $reason);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
