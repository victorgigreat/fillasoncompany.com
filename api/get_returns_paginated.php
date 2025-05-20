<?php
require_once '../config.php';

header('Content-Type: application/json');

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$date = isset($_GET['date']) ? $_GET['date'] : null;
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    // Base query parts
    $countQuery = "SELECT COUNT(*) as total FROM returned_products r JOIN products p ON r.product_id = p.id";
    $dataQuery = "SELECT r.id, r.return_date, p.name as product_name, r.quantity, r.reason 
                 FROM returned_products r JOIN products p ON r.product_id = p.id";
    
    // Where conditions
    $conditions = [];
    $params = [];
    
    // Search condition
    $conditions[] = "(p.name LIKE ? OR r.reason LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    
    // Date condition
    if ($date) {
        $conditions[] = "r.return_date = ?";
        $params[] = $date;
    }
    
    // Combine conditions
    $whereClause = $conditions ? " WHERE " . implode(" AND ", $conditions) : "";
    
    // Get total count for pagination
    $stmt = $pdo->prepare($countQuery . $whereClause);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($total / $limit);
    
    // Get returns for current page - using string interpolation for LIMIT and OFFSET
    $query = $dataQuery . $whereClause . " ORDER BY r.return_date DESC, r.recorded_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($query);
    
    // Execute without LIMIT and OFFSET as they are now part of the query string
    $stmt->execute($params);
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