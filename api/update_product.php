<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    // Validate input
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('Invalid or missing product ID');
    }
    
    if (empty($_POST['name'])) {
        throw new Exception('Product name is required');
    }
    
    if (!isset($_POST['stock']) || !is_numeric($_POST['stock']) || $_POST['stock'] < 0) {
        throw new Exception('Invalid stock quantity');
    }
    
    if (!isset($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] < 0) {
        throw new Exception('Invalid selling price');
    }
    
    if (!isset($_POST['cost_price']) || !is_numeric($_POST['cost_price']) || $_POST['cost_price'] < 0) {
        throw new Exception('Invalid cost price');
    }
    
    if (!isset($_POST['low_stock_threshold']) || !is_numeric($_POST['low_stock_threshold']) || $_POST['low_stock_threshold'] < 1) {
        throw new Exception('Invalid low stock threshold');
    }

    if ($_POST['cost_price'] > $_POST['price']) {
        throw new Exception('Cost price cannot be greater than selling price');
    }

    $product_id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $stock = (int)$_POST['stock'];
    $price = (float)$_POST['price'];
    $cost_price = (float)$_POST['cost_price'];
    $low_stock_threshold = (int)$_POST['low_stock_threshold'];

    // Check if product exists and belongs to user
    $query = "SELECT id FROM products WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$product_id]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Product not found');
    }

    // Update product
    $query = "UPDATE products 
              SET name = ?, description = ?, current_stock = ?, price = ?, cost_price = ?, low_stock_threshold = ? 
              WHERE id = ?";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $description, $stock, $price, $cost_price, $low_stock_threshold, $product_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>