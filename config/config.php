<?php
// ======================================================
// TKC-Stock Configuration File
// ======================================================

// Error Reporting (Turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time Zone
date_default_timezone_set('Africa/Addis_Ababa');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL (Update if needed)
define('BASE_URL', 'http://localhost/tkc-stock/');
define('BASE_PATH', dirname(__DIR__));

// Application Name
define('APP_NAME', 'TKC-Stock Inventory System');

// Upload Directories
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('PRODUCT_IMG_DIR', UPLOAD_DIR . 'products/');

// Create upload directories if not exists
if (!file_exists(PRODUCT_IMG_DIR)) {
    mkdir(PRODUCT_IMG_DIR, 0777, true);
}
