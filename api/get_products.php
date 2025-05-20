<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name, current_stock FROM products ORDER BY name");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>