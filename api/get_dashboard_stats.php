<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    // Get total products count
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    // Get today's sales count
    $today = date('Y-m-d');
    $todaySales = $pdo->prepare("SELECT SUM(quantity) FROM sales WHERE sale_date = ?");
    $todaySales->execute([$today]);
    $todaySales = $todaySales->fetchColumn() ?: 0;
    
    // Get low stock items count (based on individual thresholds)
    $lowStockItems = $pdo->query("
        SELECT COUNT(*) 
        FROM products 
        WHERE current_stock < low_stock_threshold
    ")->fetchColumn();
    
    echo json_encode([
        'total_products' => $totalProducts,
        'today_sales' => $todaySales,
        'low_stock_items' => $lowStockItems
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>