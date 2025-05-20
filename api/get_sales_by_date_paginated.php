<?php
require_once '../config.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? date('Y-m-d');
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    // Get total count for pagination
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.sale_date = ? AND p.name LIKE ?
    ");
    $stmt->execute([$date, $search]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($total / $limit);
    
    // Get sales for current page - remove quotes from LIMIT and OFFSET values
    $stmt = $pdo->prepare("
        SELECT s.id, p.name as product_name, s.quantity, s.recorded_at, p.price as product_price 
        FROM sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.sale_date = ? AND p.name LIKE ?
        ORDER BY s.recorded_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bindValue(1, $date);
    $stmt->bindValue(2, $search);
    $stmt->bindValue(3, $limit, PDO::PARAM_INT);
    $stmt->bindValue(4, $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sales' => $sales,
        'totalPages' => $totalPages
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>