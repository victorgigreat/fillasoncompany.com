<?php
require_once '../config.php';

header('Content-Type: application/json');

$productId = $_POST['product_id'];
$quantity = $_POST['quantity'];
$additionDate = $_POST['addition_date'];

try {
    $pdo->beginTransaction();
    
    // Record the stock addition
    $stmt = $pdo->prepare("INSERT INTO stock_additions (product_id, quantity, addition_date) VALUES (?, ?, ?)");
    $stmt->execute([$productId, $quantity, $additionDate]);
    
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