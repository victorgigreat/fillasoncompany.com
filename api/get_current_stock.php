<?php
// require_once '../config.php';

// header('Content-Type: application/json');

// try {
//     $stmt = $pdo->query("SELECT id, name, current_stock, price FROM products ORDER BY current_stock ASC, name");
//     $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     echo json_encode($stock);
// } catch (PDOException $e) {
//     http_response_code(500);
//     echo json_encode(['error' => $e->getMessage()]);
// }

require_once '../config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT p.id, p.name, p.current_stock, p.price, p.low_stock_threshold 
        FROM products p
        ORDER BY p.name
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($products);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>