<?php
require_once '../config.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? date('Y-m-d');

try {
    $stmt = $pdo->prepare("
        SELECT s.id, p.name as product_name, s.quantity, s.recorded_at 
        FROM sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.sale_date = ?
        ORDER BY s.recorded_at DESC
    ");
    $stmt->execute([$date]);
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sales);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>