<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    $productId = (int)$_POST['product_id'];
    $threshold = (int)$_POST['threshold'];

    $stmt = $pdo->prepare("UPDATE products SET low_stock_threshold = ? WHERE id = ?");
    $stmt->execute([$threshold, $productId]);

    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>