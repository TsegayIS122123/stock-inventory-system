 <?php
    header('Content-Type: application/json');
    require_once '../config/config.php';
    require_once '../config/database.php';

    $db = getDB();

    // Get profit data for top 5 products
    $query = "SELECT p.name, 
          COALESCE(SUM(si.subtotal - (si.quantity * p.cost_price)), 0) as profit 
          FROM sale_items si 
          JOIN products p ON si.product_id = p.id 
          WHERE p.cost_price > 0
          GROUP BY p.id 
          ORDER BY profit DESC 
          LIMIT 5";

    $result = $db->query($query);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $labels = [];
    $values = [];

    foreach ($data as $row) {
        $labels[] = $row['name'];
        $values[] = floatval($row['profit']);
    }

    // If no data, show dummy message
    if (empty($data)) {
        $labels = ['No Profit Data Available'];
        $values = [1];
    }

    echo json_encode(['labels' => $labels, 'values' => $values]);
    ?>
    