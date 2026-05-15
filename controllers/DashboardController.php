<?php
require_once BASE_PATH . '/models/Product.php';
require_once BASE_PATH . '/models/Sale.php';
require_once BASE_PATH . '/models/User.php';

class DashboardController
{
    private $productModel;
    private $saleModel;
    private $userModel;

    public function __construct($db)
    {
        $this->productModel = new Product($db);
        $this->saleModel = new Sale($db);
        $this->userModel = new User($db);
    }

    public function index()
    {
        $this->checkAuth();

        // Get all statistics
        $product_stats = $this->productModel->getStats();
        $sale_stats = $this->saleModel->getStats();
        $low_stock = $this->productModel->getLowStockProducts();

        require_once BASE_PATH . '/views/dashboard/index.php';
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
}
