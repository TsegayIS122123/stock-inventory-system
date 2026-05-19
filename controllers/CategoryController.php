<?php
require_once BASE_PATH . '/models/Category.php';

class CategoryController
{
    private $categoryModel;

    public function __construct($db)
    {
        $this->categoryModel = new Category($db);
    }

    public function add()
    {
        $this->checkAuth();
        $name = $_POST['name'] ?? '';
        if ($name) {
            $this->categoryModel->create($name);
        }
        header('Location: index.php?action=products');
    }

    public function edit()
    {
        $this->checkAuth();
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        if ($id && $name) {
            $this->categoryModel->update($id, $name);
        }
        header('Location: index.php?action=products');
    }

    public function delete()
    {
        $this->checkAuth();
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $this->categoryModel->delete($id);
        }
        header('Location: index.php?action=products');
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }
}
