<?php
require_once '../config.php';

header('Content-Type: application/json');

$productId = $_POST['product_id'];
$quantity = $_POST['quantity'];
$returnDate = $_POST['return_date'];
$reason = $_POST['reason'] ?? '';

try {
    $pdo->beginTransaction();
    
    // Record the return
    $stmt = $pdo->prepare("INSERT INTO returned_products (product_id, quantity, reason, return_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$productId, $quantity, $reason, $returnDate]);
    
    // Update product stock
    $stmt = $pdo->prepare("UPDATE products SET current_stock = current_stock + ? WHERE id = ?");
    $stmt->execute([$quantity, $productId]);
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>