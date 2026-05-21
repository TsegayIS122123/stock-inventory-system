
    <?php
    header('Content-Type: application/json');
    require_once '../config/config.php';
    require_once '../config/database.php';

    $db = getDB();

    $result = $db->query("SELECT customer_name, SUM(total_amount) as total FROM sales WHERE customer_name != 'Walk-in Customer' GROUP BY customer_name ORDER BY total DESC LIMIT 5");
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $labels = [];
    $values = [];

    foreach ($data as $row) {
        $labels[] = $row['customer_name'];
        $values[] = floatval($row['total']);
    }

    echo json_encode(['labels' => $labels, 'values' => $values]);
    ?>
    