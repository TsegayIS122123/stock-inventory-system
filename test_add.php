<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = getDB();

$name = 'Test Product ' . date('H:i:s');
$price = 99.99;
$quantity = 10;

$query = "INSERT INTO products (name, price, quantity, sku) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($query);
$sku = 'TEST-' . rand(1000, 9999);
$stmt->bind_param("sdss", $name, $price, $quantity, $sku);

if ($stmt->execute()) {
    echo "<h2 style='color:green'>✓ Product added successfully! ID: " . $db->insert_id . "</h2>";
    echo "<p>Name: $name</p>";
    echo "<p>SKU: $sku</p>";
    echo "<p>Price: $$price</p>";
    echo "<p>Quantity: $quantity</p>";
    echo "<p><a href='index.php?action=products'>Go to Products Page</a></p>";
} else {
    echo "<h2 style='color:red'>✗ Error: " . $db->error . "</h2>";
}
