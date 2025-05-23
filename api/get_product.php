<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid or missing product ID');
    }

    $product_id = (int)$_GET['id'];
    
    $query = "SELECT * 
              FROM products 
              WHERE id = ?";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('Product not found');
    }

    echo json_encode([
        'success' => true,
        'product' => [
            'id' => $product['id'],
            'name' => $product['name'],
            'description' => $product['description'],
            'current_stock' => $product['current_stock'],
            'price' => $product['price'],
            'cost_price' => $product['cost_price'],
            'low_stock_threshold' => $product['low_stock_threshold']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>