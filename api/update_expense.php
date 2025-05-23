<?php
header('Content-Type: application/json');
require_once '../config.php';

try {
    // Parse JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Validate input
    if (!isset($input['id']) || !is_numeric($input['id'])) {
        throw new Exception('Invalid or missing expense ID');
    }
    
    if (!isset($input['amount']) || !is_numeric($input['amount']) || $input['amount'] <= 0) {
        throw new Exception('Invalid amount');
    }
    
    if (empty($input['expense_date'])) {
        throw new Exception('Expense date is required');
    }

    $expense_id = (int)$input['id'];
    $user_id = (int)$input['user_id'];
    $amount = (float)$input['amount'];
    $description = isset($input['description']) ? trim($input['description']) : '';
    $expense_date = $input['expense_date'];

    // Verify user permission and expense existence
    $query = "SELECT id FROM expenses WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$expense_id, $user_id]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Expense not found or you do not have permission to update it');
    }

    // Update expense
    $query = "UPDATE expenses 
              SET amount = ?, description = ?, expense_date = ? 
              WHERE id = ? AND user_id = ?";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$amount, $description, $expense_date, $expense_id, $user_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Expense updated successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>