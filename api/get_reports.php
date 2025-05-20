<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

// Get JSON input
$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input: ' . json_last_error_msg()]);
    exit;
}

// Validate inputs
$report_type = isset($input['report_type']) ? trim($input['report_type']) : '';
$start_date = isset($input['start_date']) ? trim($input['start_date']) : '';
$end_date = isset($input['end_date']) ? trim($input['end_date']) : '';

if (empty($report_type) || empty($start_date)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Report type and start date are required']);
    exit;
}

// Validate date format (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || 
    ($end_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date))) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid date format. Use YYYY-MM-DD']);
    exit;
}

// Determine date range based on report type
try {
    if ($report_type === 'daily') {
        $end_date = $start_date;
    } elseif ($report_type === 'weekly') {
        $date = new DateTime($start_date);
        $end_date = $date->modify('+6 days')->format('Y-m-d');
    } elseif ($report_type === 'monthly') {
        $date = new DateTime($start_date);
        $end_date = $date->modify('last day of this month')->format('Y-m-d');
    } elseif ($report_type === 'custom') {
        if (empty($end_date)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'End date is required for custom reports']);
            exit;
        }
        // Ensure end_date is not before start_date
        if (strtotime($end_date) < strtotime($start_date)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'End date cannot be before start date']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid report type. Use daily, weekly, monthly, or custom']);
        exit;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid date: ' . $e->getMessage()]);
    exit;
}

// Database connection
$conn = get_db_connection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to connect to database']);
    exit;
}

try {
    // Fetch sales data
    $sales = [];
    $query = "SELECT DATE(s.sale_date) as date, s.quantity, p.name as product_name, 
                     (s.quantity * COALESCE(p.price, 0)) as revenue,
                     (s.quantity * (COALESCE(p.price, 0) - COALESCE(p.cost_price, 0))) as profit,
                     COALESCE(u.full_name, 'Unknown') as salesperson
              FROM sales s 
              LEFT JOIN products p ON s.product_id = p.id 
              LEFT JOIN users u ON s.user_id = u.id
              WHERE DATE(s.sale_date) BETWEEN ? AND ? 
              ORDER BY s.sale_date";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $sales[] = [
            'date' => $row['date'],
            'product_name' => $row['product_name'] ?? 'Unknown Product',
            'quantity' => intval($row['quantity']),
            'revenue' => floatval($row['revenue']),
            'profit' => floatval($row['profit']),
            'salesperson' => $row['salesperson']
        ];
    }
    $stmt->close();

    // Fetch expenses data
    $expenses = [];
    $query = "SELECT DATE(expense_date) as date, amount, description 
              FROM expenses 
              WHERE DATE(expense_date) BETWEEN ? AND ? 
              ORDER BY expense_date";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $expenses[] = [
            'date' => $row['date'],
            'amount' => floatval($row['amount']),
            'description' => $row['description'] ?: 'N/A'
        ];
    }
    $stmt->close();

    // Fetch returns data
    $returns = [];
    $query = "SELECT DATE(return_date) as date, p.name as product_name, 
                     r.quantity, r.reason 
              FROM returned_products r 
              LEFT JOIN products p ON r.product_id = p.id 
              WHERE DATE(return_date) BETWEEN ? AND ? 
              ORDER BY return_date";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $returns[] = [
            'date' => $row['date'],
            'product_name' => $row['product_name'] ?? 'Unknown Product',
            'quantity' => intval($row['quantity']),
            'reason' => $row['reason'] ?: 'N/A'
        ];
    }
    $stmt->close();

    // Calculate summary
    $total_sales = array_sum(array_column($sales, 'revenue'));
    $total_profit = array_sum(array_column($sales, 'profit'));
    $total_expenses = array_sum(array_column($expenses, 'amount'));
    $total_returns = array_sum(array_column($returns, 'quantity'));

    // Prepare response
    $response = [
        'success' => true,
        'sales' => $sales,
        'expenses' => $expenses,
        'returns' => $returns,
        'summary' => [
            'total_sales' => round($total_sales, 2),
            'total_profit' => round($total_profit, 2),
            'total_expenses' => round($total_expenses, 2),
            'total_returns' => intval($total_returns)
        ],
        'start_date' => $start_date,
        'end_date' => $end_date,
        'report_type' => $report_type
    ];

    http_response_code(200);
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>