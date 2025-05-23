<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../config/database.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$offset = ($page - 1) * $limit;

$conn = get_db_connection();
$where = "DATE(expense_date) = ?";
$params = [$date];
$types = "s";

if ($search) {
    $where .= " AND description LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM expenses WHERE $where");
$stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);
$stmt->close();

$stmt = $conn->prepare("SELECT id, expense_date, amount, description 
                        FROM expenses 
                        WHERE $where 
                        ORDER BY expense_date DESC 
                        LIMIT ? OFFSET ?");
$params[] = $limit;
$params[] = $offset;
$types .= "ii";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$expenses = [];

while ($row = $result->fetch_assoc()) {
    $expenses[] = [
        'id' => intval($row['id']),
        'expense_date' => $row['expense_date'],
        'amount' => floatval($row['amount']),
        'description' => $row['description']
    ];
}

echo json_encode([
    'expenses' => $expenses,
    'totalPages' => $totalPages
]);
$stmt->close();
$conn->close();
?>