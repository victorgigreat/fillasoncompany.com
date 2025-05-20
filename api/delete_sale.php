<?php
require_once '../config.php';

header('Content-Type: application/json');

$saleId = $_POST['id'];

try {
    // First get the sale details to know how much stock to return
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM sales WHERE id = ?");
    $stmt->execute([$saleId]);
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$sale) {
        throw new Exception("Sale not found");
    }
    
    $pdo->beginTransaction();
    
    // Delete the sale
    $stmt = $pdo->prepare("DELETE FROM sales WHERE id = ?");
    $stmt->execute([$saleId]);
    
    // Return the stock
    $stmt = $pdo->prepare("UPDATE products SET current_stock = current_stock + ? WHERE id = ?");
    $stmt->execute([$sale['quantity'], $sale['product_id']]);
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>