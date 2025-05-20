<?php
require_once '../config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch($action) {
        case 'recent':
            // Get recent orders
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($orders);
            break;
            
        case 'cancel':
            // Cancel order
            if(!isset($_POST['id'])) {
                throw new Exception("Order ID is required");
            }
            
            $pdo->beginTransaction();
            
            // Get order items to restore stock
            $stmt = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
            $stmt->execute([$_POST['id']]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Restore stock
            foreach($items as $item) {
                $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            // Update order status
            $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            
            $pdo->commit();
            
            echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
            break;
            
        case 'place':
            // Place order (AJAX version)
            $customer_name = trim($_POST['customer_name']);
            $customer_email = trim($_POST['customer_email']);
            $products = $_POST['products'];
            $quantities = $_POST['quantities'];
            
            if(empty($customer_name) || empty($customer_email) || empty($products)) {
                throw new Exception("All fields are required");
            }
            
            $pdo->beginTransaction();
            
            // Calculate total amount and check stock
            $total_amount = 0;
            $out_of_stock = false;
            $out_of_stock_items = [];
            
            foreach($products as $key => $product_id) {
                $quantity = $quantities[$key];
                
                // Check product stock
                $stmt = $pdo->prepare("SELECT stock, price FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($product['stock'] < $quantity) {
                    $out_of_stock = true;
                    $out_of_stock_items[] = $product_id;
                }
                
                $total_amount += $product['price'] * $quantity;
            }
            
            if($out_of_stock) {
                throw new Exception("Some items are out of stock. Please adjust quantities.");
            }
            
            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, total_amount) VALUES (?, ?, ?)");
            $stmt->execute([$customer_name, $customer_email, $total_amount]);
            $order_id = $pdo->lastInsertId();
            
            // Add order items and update stock
            foreach($products as $key => $product_id) {
                $quantity = $quantities[$key];
                
                // Get product price
                $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Add order item
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);
                
                // Update product stock
                $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$quantity, $product_id]);
            }
            
            $pdo->commit();
            
            echo json_encode(['success' => true, 'message' => 'Order placed successfully', 'order_id' => $order_id]);
            break;
            
        default:
            throw new Exception("Invalid action");
    }
} catch(Exception $e) {
    if($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>