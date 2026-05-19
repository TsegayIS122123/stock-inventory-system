<?php
class Category
{
    private $conn;
    private $table = 'categories';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all categories
    public function getAll()
    {
        $query = "SELECT c.*, COUNT(p.id) as product_count 
                  FROM " . $this->table . " c
                  LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
                  GROUP BY c.id
                  ORDER BY c.name";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get category by ID
    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create new category
    public function create($name, $description = null)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // Update category
    public function update($id, $name, $description = null)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    // Delete category (only if no products)
    public function delete($id)
    {
        // Check if category has products
        $check = $this->conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            return false; // Cannot delete category with products
        }

        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
