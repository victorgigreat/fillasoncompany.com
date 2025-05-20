<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT s.sale_date, p.name as product_name, s.quantity 
        FROM sales s
        JOIN products p ON s.product_id = p.id
        ORDER BY s.sale_date DESC, s.recorded_at DESC
        LIMIT 10
    ");
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sales);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>