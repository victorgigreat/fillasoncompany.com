<?php
require_once '../config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

try {
    switch($action) {
        case 'recent':
            // Get recent products
            $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($products);
            break;
            
        case 'check_stock':
            // Check product stock
            $product_id = $_GET['product_id'];
            $quantity = $_GET['quantity'] ?? 1;
            
            $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!$product) {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
                exit;
            }
            
            if($product['stock'] < $quantity) {
                echo json_encode(['success' => false, 'message' => 'Insufficient stock', 'stock' => $product['stock']]);
            } else {
                echo json_encode(['success' => true, 'stock' => $product['stock']]);
            }
            break;
            
        case 'delete':
            // Delete product
            if(!isset($_POST['id'])) {
                throw new Exception("Product ID is required");
            }
            
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
            break;
            
        default:
            throw new Exception("Invalid action");
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>