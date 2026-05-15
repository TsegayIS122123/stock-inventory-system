<?php
// ======================================================
// Database Connection (Chapter 5)
// ======================================================

class Database
{
    private $host = 'localhost';
    private $dbname = 'tkc_stock';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }

        return $this->conn;
    }
}

// Global database connection function
function getDB()
{
    $database = new Database();
    return $database->getConnection();
}
