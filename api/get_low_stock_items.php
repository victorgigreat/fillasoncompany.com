<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    // Get all products with stock below their individual threshold
    $stmt = $pdo->prepare("
        SELECT 
            p.id, 
            p.name, 
            p.current_stock, 
            p.price,
            p.low_stock_threshold
        FROM products p
        WHERE p.current_stock < p.low_stock_threshold
        ORDER BY (p.current_stock / p.low_stock_threshold) ASC
    ");
    
    $stmt->execute();
    $lowStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate percentage below threshold for each item
    $lowStockItems = array_map(function($item) {
        $item['percentage'] = round(($item['current_stock'] / $item['low_stock_threshold']) * 100, 2);
        $item['needed'] = max(0, $item['low_stock_threshold'] - $item['current_stock']);
        return $item;
    }, $lowStockItems);
    
    echo json_encode([
        'success' => true,
        'data' => $lowStockItems,
        'meta' => [
            'count' => count($lowStockItems),
            'generated_at' => date('Y-m-d H:i:s')
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage(),
        'database_error' => $e->errorInfo
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>