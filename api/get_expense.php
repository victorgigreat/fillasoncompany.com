<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('Invalid or missing expense ID');
    }

    $expense_id = (int)$_GET['id'];
    
    $query = "SELECT * 
              FROM expenses 
              WHERE id = ?"; // Corrected: added closing quote and removed semicolon inside string
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$expense_id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$expense) {
        throw new Exception('Expense not found or you do not have permission to view it');
    }

    echo json_encode([
        'success' => true,
        'expense' => [
            'id' => $expense['id'],
            'amount' => $expense['amount'],
            'description' => $expense['description'],
            'expense_date' => $expense['expense_date']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
