<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/config.php';
require_once '../config/database.php';

$db = getDB();
$period = $_GET['period'] ?? 'week';

$labels = [];
$values = [];

switch ($period) {
    case 'week':
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('D', strtotime($date));
            $result = $db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE DATE(created_at) = '$date'");
            $values[] = floatval($result->fetch_assoc()['total']);
        }
        break;
    case 'month':
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M j', strtotime($date));
            $result = $db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE DATE(created_at) = '$date'");
            $values[] = floatval($result->fetch_assoc()['total']);
        }
        break;
    case 'year':
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 1));
            $result = $db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM sales WHERE MONTH(created_at) = $i AND YEAR(created_at) = YEAR(CURDATE())");
            $values[] = floatval($result->fetch_assoc()['total']);
        }
        break;
}

echo json_encode(['labels' => $labels, 'values' => $values]);
