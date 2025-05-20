<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT a.addition_date, p.name as product_name, a.quantity 
        FROM stock_additions a
        JOIN products p ON a.product_id = p.id
        ORDER BY a.addition_date DESC, a.recorded_at DESC
        LIMIT 10
    ");
    $additions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($additions);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>