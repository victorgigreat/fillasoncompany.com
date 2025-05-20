<?php
header('Content-Type: application/json');
require_once '../config.php';

$days = 7; // Number of days to fetch
$endDate = date('Y-m-d');
$startDate = date('Y-m-d', strtotime("-$days days"));

try {
    // Fetch sales data (total revenue per day)
    $salesStmt = $pdo->prepare("
        SELECT DATE(sale_date) as date, SUM(quantity * p.price) as revenue
        FROM sales s
        JOIN products p ON s.product_id = p.id
        WHERE s.sale_date BETWEEN ? AND ?
        GROUP BY DATE(s.sale_date)
        ORDER BY DATE(s.sale_date)
    ");
    $salesStmt->execute([$startDate, $endDate]);
    $salesData = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch expenses data (total amount per day)
    $expensesStmt = $pdo->prepare("
        SELECT DATE(expense_date) as date, SUM(amount) as amount
        FROM expenses
        WHERE expense_date BETWEEN ? AND ?
        GROUP BY DATE(expense_date)
        ORDER BY DATE(expense_date)
    ");
    $expensesStmt->execute([$startDate, $endDate]);
    $expensesData = $expensesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch returns data (total quantity per day)
    $returnsStmt = $pdo->prepare("
        SELECT DATE(return_date) as date, SUM(quantity) as quantity
        FROM returns
        WHERE return_date BETWEEN ? AND ?
        GROUP BY DATE(return_date)
        ORDER BY DATE(return_date)
    ");
    $returnsStmt->execute([$startDate, $endDate]);
    $returnsData = $returnsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize date range
    $dates = [];
    $currentDate = strtotime($startDate);
    $endTimestamp = strtotime($endDate);
    while ($currentDate <= $endTimestamp) {
        $dates[date('Y-m-d', $currentDate)] = [
            'sales' => 0,
            'expenses' => 0,
            'returns' => 0
        ];
        $currentDate = strtotime('+1 day', $currentDate);
    }

    // Populate sales data
    foreach ($salesData as $sale) {
        $dates[$sale['date']]['sales'] = (float)$sale['revenue'];
    }

    // Populate expenses data
    foreach ($expensesData as $expense) {
        $dates[$expense['date']]['expenses'] = (float)$expense['amount'];
    }

    // Populate returns data
    foreach ($returnsData as $ret) {
        $dates[$ret['date']]['returns'] = (int)$ret['quantity'];
    }

    // Format response
    $response = [
        'success' => true,
        'trends' => []
    ];
    foreach ($dates as $date => $data) {
        $response['trends'][] = [
            'date' => $date,
            'sales' => $data['sales'],
            'expenses' => $data['expenses'],
            'returns' => $data['returns']
        ];
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>