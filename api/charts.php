<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../config/database.php';

$db = getDB();

$result = $db->query("SELECT p.name, COALESCE(SUM(si.subtotal - (si.quantity * p.cost_price)), 0) as profit FROM sale_items si JOIN products p ON si.product_id = p.id GROUP BY p.id ORDER BY profit DESC LIMIT 5");
$data = $result->fetch_all(MYSQLI_ASSOC);

$labels = [];
$values = [];

foreach ($data as $row) {
    $labels[] = $row['name'];
    $values[] = floatval($row['profit']);
}

echo json_encode(['labels' => $labels, 'values' => $values]);
