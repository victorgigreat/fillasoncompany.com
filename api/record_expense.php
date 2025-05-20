<?php
header('Content-Type: application/json');
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'] ?? 0;
    $amount = $data['amount'] ?? 0;
    $description = $data['description'] ?? '';
    $expense_date = $data['expense_date'] ?? '';

    if (!$user_id || !$amount || !$expense_date) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO expenses (user_id, amount, description, expense_date) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$user_id, $amount, $description, $expense_date])) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to record expense']);
    }
}
?>