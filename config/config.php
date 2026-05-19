<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Africa/Addis_Ababa');

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', 'http://localhost/tkc-stock/');
define('BASE_PATH', dirname(__DIR__));

define('APP_NAME', 'TKC-Stock');
define('APP_VERSION', '2.0.0');

define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('PRODUCT_IMG_DIR', UPLOAD_DIR . 'products/');

if (!file_exists(PRODUCT_IMG_DIR)) {
    mkdir(PRODUCT_IMG_DIR, 0777, true);
}
