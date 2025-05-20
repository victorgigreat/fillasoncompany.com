<?php
// require_once '../config.php';

// header('Content-Type: application/json');

// $name = $_POST['name'];
// $description = $_POST['description'] ?? '';
// $stock = $_POST['stock'] ?? 0;
// $price = $_POST['price'] ?? 0;

// try {
//     $stmt = $pdo->prepare("INSERT INTO products (name, description, current_stock, price) VALUES (?, ?, ?, ?)");
//     $stmt->execute([$name, $description, $stock, $price]);
//     echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
// } catch (PDOException $e) {
//     http_response_code(500);
//     echo json_encode(['error' => $e->getMessage()]);
// }
require_once '../config.php';

header('Content-Type: application/json');

try {
    // Get input data (works for both form data and JSON)
    $input = file_get_contents('php://input');
    $data = json_decode($input, true) ?: $_POST; // Try JSON first, fallback to form data
    
    // Validate required fields
    $name = isset($data['name']) ? trim($data['name']) : null;
    $description = $data['description'] ?? '';
    $stock = isset($data['stock']) ? (int)$data['stock'] : 0;
    $price = isset($data['price']) ? (float)$data['price'] : 0.0;
    $threshold = isset($data['low_stock_threshold']) ? (int)$data['low_stock_threshold'] : 200;

    // Validate product name
    if (empty($name)) {
        throw new Exception('Product name is required');
    }

    // Prepare and execute query
    $stmt = $pdo->prepare("INSERT INTO products 
                          (name, description, current_stock, price, low_stock_threshold) 
                          VALUES (:name, :description, :stock, :price, :threshold)");
    
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':stock' => $stock,
        ':price' => $price,
        ':threshold' => $threshold
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Product added successfully',
        'product_id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>