<?php
require_once '../config.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    // Get total count for pagination
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total 
        FROM returned_products r
        JOIN products p ON r.product_id = p.id
        WHERE p.name LIKE ? OR r.reason LIKE ?
    ");
    $stmt->execute([$search, $search]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($total / $limit);
    
    // Get returns for current page
    $stmt = $pdo->prepare("
        SELECT r.id, r.return_date, p.name as product_name, r.quantity, r.reason 
        FROM returned_products r
        JOIN products p ON r.product_id = p.id
        WHERE p.name LIKE ? OR r.reason LIKE ?
        ORDER BY r.return_date DESC, r.recorded_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$search, $search, $limit, $offset]);
    $returns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'returns' => $returns,
        'totalPages' => $totalPages
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>