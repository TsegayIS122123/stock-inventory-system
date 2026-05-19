<?php
class SettingsController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $this->checkAuth();
        require_once BASE_PATH . '/views/settings/index.php';
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=dashboard');
            exit();
        }
    }
}
