<?php
require_once BASE_PATH . '/models/Product.php';
require_once BASE_PATH . '/models/Sale.php';
require_once BASE_PATH . '/models/Category.php';

class DashboardController
{
    private $productModel;
    private $saleModel;
    private $categoryModel;

    public function __construct($db)
    {
        $this->productModel = new Product($db);
        $this->saleModel = new Sale($db);
        $this->categoryModel = new Category($db);
    }

    public function index()
    {
        $this->checkAuth();

        $product_stats = $this->productModel->getStats();
        $sale_stats = $this->saleModel->getStats();  // This line was causing error
        $low_stock = $this->productModel->getLowStockProducts();
        $recent_sales = $this->saleModel->getRecent(5);
        $top_products = $this->saleModel->getTopProducts(5);
        $categories = $this->categoryModel->getAll();

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
