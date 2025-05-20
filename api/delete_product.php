<?php
require_once '../config.php';

header('Content-Type: application/json');

$productId = $_POST['id'];

try {
    $pdo->beginTransaction();
    
    // First delete related sales
    $stmt = $pdo->prepare("DELETE FROM sales WHERE product_id = ?");
    $stmt->execute([$productId]);
    
    // Then delete related stock additions
    $stmt = $pdo->prepare("DELETE FROM stock_additions WHERE product_id = ?");
    $stmt->execute([$productId]);
    
    // Then delete related returns
    $stmt = $pdo->prepare("DELETE FROM returned_products WHERE product_id = ?");
    $stmt->execute([$productId]);
    
    // Finally delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>