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
        FROM stock_additions a
        JOIN products p ON a.product_id = p.id
        WHERE p.name LIKE ?
    ");
    $stmt->execute([$search]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($total / $limit);
    
    // Get additions for current page - remove quotes from LIMIT and OFFSET values
    $stmt = $pdo->prepare("
        SELECT a.id, a.addition_date, p.name as product_name, a.quantity 
        FROM stock_additions a
        JOIN products p ON a.product_id = p.id
        WHERE p.name LIKE ?
        ORDER BY a.addition_date DESC, a.recorded_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bindValue(1, $search);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $additions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'additions' => $additions,
        'totalPages' => $totalPages
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>