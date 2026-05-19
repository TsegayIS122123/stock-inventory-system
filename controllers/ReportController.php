<?php
class ReportController
{
    private $db;
    private $saleModel;

    public function __construct($db)
    {
        $this->db = $db;
        require_once BASE_PATH . '/models/Sale.php';
        $this->saleModel = new Sale($db);
    }

    public function index()
    {
        $this->checkAuth();

        $sale_stats = $this->saleModel->getStats();

        // Monthly sales
        $result = $this->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count, SUM(total_amount) as total FROM sales GROUP BY month ORDER BY month DESC LIMIT 6");
        $monthly_sales = $result->fetch_all(MYSQLI_ASSOC);

        // Total items sold
        $result = $this->db->query("SELECT SUM(quantity) as total FROM sale_items");
        $total_items_sold = $result->fetch_assoc()['total'] ?? 0;

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
