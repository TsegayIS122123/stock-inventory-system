-- ======================================================
-- TKC-Stock: Smart Inventory & Sales Management System
-- Complete Database Schema
-- ======================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS tkc_stock;
USE tkc_stock;

-- ======================================================
-- Table 1: Users (Authentication & Roles)
-- ======================================================
CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager', 'cashier') DEFAULT 'cashier',
    profile_image VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- ======================================================
-- Table 2: Categories
-- ======================================================
CREATE TABLE categories (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    product_count INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
);

-- ======================================================
-- Table 3: Products
-- ======================================================
CREATE TABLE products (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    sku VARCHAR(50) UNIQUE,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    cost_price DECIMAL(10,2) DEFAULT 0.00,
    quantity INT(11) NOT NULL DEFAULT 0,
    min_stock_level INT(11) DEFAULT 5,
    category_id INT(11) UNSIGNED,
    image VARCHAR(255) DEFAULT NULL,
    barcode VARCHAR(100) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_price (price),
    INDEX idx_quantity (quantity)
);

-- ======================================================
-- Table 4: Sales (Transactions)
-- ======================================================
CREATE TABLE sales (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_no VARCHAR(50) NOT NULL UNIQUE,
    user_id INT(11) UNSIGNED NOT NULL,
    customer_name VARCHAR(100) DEFAULT 'Walk-in Customer',
    customer_phone VARCHAR(20) DEFAULT NULL,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    discount_type ENUM('percentage', 'fixed') DEFAULT 'fixed',
    discount_value DECIMAL(10,2) DEFAULT 0.00,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    tax_rate DECIMAL(5,2) DEFAULT 0.00,
    tax_amount DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    payment_method ENUM('cash', 'card', 'mobile') DEFAULT 'cash',
    payment_status ENUM('paid', 'pending', 'cancelled') DEFAULT 'paid',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_invoice (invoice_no),
    INDEX idx_date (created_at),
    INDEX idx_customer (customer_name)
);

-- ======================================================
-- Table 5: Sale Items
-- ======================================================
CREATE TABLE sale_items (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id INT(11) UNSIGNED NOT NULL,
    product_id INT(11) UNSIGNED NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_sale (sale_id),
    INDEX idx_product (product_id)
);

-- ======================================================
-- Table 6: Stock Logs (Audit Trail)
-- ======================================================
CREATE TABLE stock_logs (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    quantity_change INT(11) NOT NULL,
    previous_quantity INT(11) NOT NULL,
    new_quantity INT(11) NOT NULL,
    type ENUM('purchase', 'sale', 'adjustment', 'return') NOT NULL,
    reference_id VARCHAR(100) DEFAULT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_product (product_id),
    INDEX idx_type (type),
    INDEX idx_date (created_at)
);

-- ======================================================
-- Insert Sample Data
-- ======================================================

-- Insert Categories
INSERT INTO categories (name, description) VALUES
('Electronics', 'Smartphones, laptops, tablets, and accessories'),
('Clothing', 'Men and women fashion apparel'),
('Food & Beverages', 'Groceries, drinks, and snacks'),
('Home & Living', 'Furniture, decor, and kitchenware'),
('Beauty & Health', 'Cosmetics, skincare, and health products');

-- Insert Users (password = 'password123' hashed)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@tkcstock.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin', 'admin'),
('manager', 'manager@tkcstock.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Store Manager', 'manager'),
('cashier', 'cashier@tkcstock.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sales Cashier', 'cashier');

-- Insert Products
INSERT INTO products (name, description, sku, price, cost_price, quantity, min_stock_level, category_id) VALUES
('iPhone 14', 'Latest Apple smartphone', 'ELEC-001', 999.00, 850.00, 15, 5, 1),
('Samsung Galaxy S23', 'Android flagship', 'ELEC-002', 899.00, 750.00, 12, 5, 1),
('MacBook Pro', '14-inch laptop', 'ELEC-003', 1999.00, 1700.00, 8, 3, 1),
('Men\'s T-Shirt', 'Cotton casual shirt', 'CLOTH-001', 29.99, 15.00, 50, 10, 2),
('Women\'s Jeans', 'Stretch denim', 'CLOTH-002', 59.99, 35.00, 30, 10, 2),
('Coca-Cola 1L', 'Carbonated soft drink', 'FOOD-001', 2.50, 1.50, 100, 20, 3),
('Milk 1L', 'Fresh whole milk', 'FOOD-002', 3.00, 2.00, 40, 10, 3),
('Coffee Table', 'Wooden coffee table', 'HOME-001', 149.99, 100.00, 10, 3, 4),
('Face Moisturizer', 'Hydrating cream', 'BEAUTY-001', 24.99, 15.00, 25, 5, 5);

-- Sample Sale
INSERT INTO sales (invoice_no, user_id, customer_name, subtotal, total_amount, payment_method) VALUES
('INV-20240001', 3, 'John Doe', 1028.99, 1028.99, 'cash');

INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal) VALUES
(1, 1, 1, 999.00, 999.00),
(1, 6, 2, 2.50, 5.00),
(1, 7, 1, 24.99, 24.99);

-- Update stock after sale
UPDATE products SET quantity = quantity - 1 WHERE id = 1;
UPDATE products SET quantity = quantity - 2 WHERE id = 6;
UPDATE products SET quantity = quantity - 1 WHERE id = 7;

-- Update category counts
UPDATE categories c 
SET product_count = (
    SELECT COUNT(*) FROM products p 
    WHERE p.category_id = c.id AND p.is_active = 1
);
