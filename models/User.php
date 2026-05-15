<?php
// ======================================================
// User Model - Handles all user operations (Chapter 4 OOP)
// ======================================================

class User
{
    private $conn;
    private $table = 'users';

    // Properties (Chapter 4 - Class Properties)
    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $role;
    public $is_active;
    public $last_login;

    // Constructor (Chapter 4)
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register new user
    public function register()
    {
        // Check if user exists
        if ($this->emailExists()) {
            return false;
        }

        // Hash password (Chapter 6 - Security)
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table . " 
                  (username, email, password, full_name, role) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $this->username, $this->email, $hashed_password, $this->full_name, $this->role);

        return $stmt->execute();
    }

    // Login user
    public function login()
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (email = ? OR username = ?) AND is_active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $this->email, $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($this->password, $user['password'])) {
                // Update last login
                $this->updateLastLogin($user['id']);
                return $user;
            }
        }

        return false;
    }

    // Check if email exists
    public function emailExists()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    // Update last login
    private function updateLastLogin($user_id)
    {
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    // Get user by ID
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Get all users
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update user
    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET full_name = ?, role = ?, is_active = ? 
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $this->full_name, $this->role, $this->is_active, $this->id);

        return $stmt->execute();
    }

    // Delete user
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        return $stmt->execute();
    }

    // Get user statistics
    public function getStats()
    {
        $stats = [];

        // Total users
        $result = $this->conn->query("SELECT COUNT(*) as total FROM " . $this->table);
        $stats['total_users'] = $result->fetch_assoc()['total'];

        // Users by role
        $result = $this->conn->query("SELECT role, COUNT(*) as count FROM " . $this->table . " GROUP BY role");
        $stats['by_role'] = $result->fetch_all(MYSQLI_ASSOC);

        return $stats;
    }
}
