<?php
// ======================================================
// TKC-Stock: Front Controller (Router)
// ======================================================

require_once 'config/config.php';
require_once 'config/database.php';

// Get database connection
$db = getDB();

// Get action from URL
$action = $_GET['action'] ?? 'login';

// Route to appropriate controller
switch ($action) {
    // Auth routes
    case 'login':
        require_once 'controllers/UserController.php';
        $controller = new UserController($db);
        $controller->showLogin();
        break;

    case 'do-login':
        require_once 'controllers/UserController.php';
        $controller = new UserController($db);
        $controller->login();
        break;

    case 'register':
        require_once 'controllers/UserController.php';
        $controller = new UserController($db);
        $controller->showRegister();
        break;

    case 'do-register':
        require_once 'controllers/UserController.php';
        $controller = new UserController($db);
        $controller->register();
        break;

    case 'logout':
        require_once 'controllers/UserController.php';
        $controller = new UserController($db);
        $controller->logout();
        break;

    // Dashboard
    case 'dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController($db);
        $controller->index();
        break;

    // Product routes
    case 'products':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->index();
        break;

    case 'products-create':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->create();
        break;

    case 'products-store':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->store();
        break;

    case 'products-edit':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->edit();
        break;

    case 'products-update':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->update();
        break;

    case 'products-delete':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->delete();
        break;

    case 'products-search':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->search();
        break;

    // Sales routes
    case 'sales':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->index();
        break;

    case 'sales-create':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->create();
        break;

    case 'add-to-cart':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->addToCart();
        break;

    case 'update-cart':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->updateCart();
        break;

    case 'remove-from-cart':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->removeFromCart();
        break;

    case 'checkout':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->checkout();
        break;

    case 'sales-invoice':
        require_once 'controllers/SalesController.php';
        $controller = new SalesController($db);
        $controller->invoice();
        break;
    case 'products-show':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->show();
        break;
    case 'get-product':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->getProductJson();
        break;
    // Stock adjustment
    case 'adjust-stock':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController($db);
        $controller->adjustStock();
        break;

    // Category management
    case 'category-add':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->add();
        break;

    case 'category-edit':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->edit();
        break;

    case 'category-delete':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController($db);
        $controller->delete();
        break;
    // Add these cases to your switch statement:

    case 'reports':
        require_once 'controllers/ReportController.php';
        $controller = new ReportController($db);
        $controller->index();
        break;

    case 'settings':
        require_once 'controllers/SettingsController.php';
        $controller = new SettingsController($db);
        $controller->index();
        break;

    case 'profile':
        // Profile page (can be added)
        break;

    // Default
    default:
        header('Location: index.php?action=login');
        break;
}
