<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
$host = 'localhost';
$username = 'bluegqvy_bot';
$password = 'bluegqvy_bot';
$database = 'bluegqvy_bot';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error', 
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Sanitize input function
function sanitizeInput($input) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($input))));
}

// Handle different actions based on POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? sanitizeInput($_POST['action']) : '';

    switch ($action) {
        // Add New Product
        case 'add_product':
            $product_name = sanitizeInput($_POST['product_name']);
            $initial_stock = floatval($_POST['initial_stock']);
            $min_stock = floatval($_POST['min_stock']);
            $unit_price = floatval($_POST['unit_price']);
            $unit = sanitizeInput($_POST['unit']);

            $stmt = $conn->prepare("INSERT INTO products 
                (product_name, current_stock, minimum_stock_level, unit_price, unit) 
                VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sddds", $product_name, $initial_stock, $min_stock, $unit_price, $unit);
            
            if ($stmt->execute()) {
                // Log initial stock addition
                $product_id = $stmt->insert_id;
                $log_stmt = $conn->prepare("INSERT INTO stock_transactions 
                    (product_id, transaction_type, quantity, notes) 
                    VALUES (?, 'ADD', ?, 'Initial stock')");
                $log_stmt->bind_param("id", $product_id, $initial_stock);
                $log_stmt->execute();

                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Product added successfully'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Failed to add product'
                ]);
            }
            break;

        // Add Stock to Existing Product
        case 'add_stock':
            $product_id = intval($_POST['product_id']);
            $quantity = floatval($_POST['quantity']);
            $notes = sanitizeInput($_POST['notes'] ?? '');

            // Begin transaction
            $conn->begin_transaction();

            try {
                // Update product stock
                $update_stmt = $conn->prepare("UPDATE products 
                    SET current_stock = current_stock + ? 
                    WHERE id = ?");
                $update_stmt->bind_param("di", $quantity, $product_id);
                $update_stmt->execute();

                // Log stock transaction
                $log_stmt = $conn->prepare("INSERT INTO stock_transactions 
                    (product_id, transaction_type, quantity, notes) 
                    VALUES (?, 'ADD', ?, ?)");
                $log_stmt->bind_param("ids", $product_id, $quantity, $notes);
                $log_stmt->execute();

                // Commit transaction
                $conn->commit();

                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Stock added successfully'
                ]);
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Failed to add stock: ' . $e->getMessage()
                ]);
            }
            break;

        // Record Daily Sales
        case 'record_daily_sales':
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Prepare to track total sales
                $sales_data = [];

                // Process each sales line item
                $product_ids = $_POST['product_id'] ?? [];
                $quantities = $_POST['quantity'] ?? [];

                foreach ($product_ids as $index => $product_id) {
                    $product_id = intval($product_id);
                    $quantity = floatval($quantities[$index]);

                    // Validate input
                    if ($quantity <= 0) continue;

                    // Reduce stock
                    $update_stmt = $conn->prepare("UPDATE products 
                        SET current_stock = current_stock - ? 
                        WHERE id = ?");
                    $update_stmt->bind_param("di", $quantity, $product_id);
                    $update_stmt->execute();

                    // Log sales transaction
                    $log_stmt = $conn->prepare("INSERT INTO stock_transactions 
                        (product_id, transaction_type, quantity, notes) 
                        VALUES (?, 'SALE', ?, 'Daily sales')");
                    $log_stmt->bind_param("id", $product_id, $quantity);
                    $log_stmt->execute();

                    // Store sales data for response
                    $sales_data[] = [
                        'product_id' => $product_id,
                        'quantity' => $quantity
                    ];
                }

                // Commit transaction
                $conn->commit();

                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Daily sales recorded successfully',
                    'sales_data' => $sales_data
                ]);
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Failed to record sales: ' . $e->getMessage()
                ]);
            }
            break;

        // Get Stock Levels
        case 'get_stock_levels':
            $query = "SELECT 
                id, 
                product_name, 
                current_stock, 
                minimum_stock_level,
                unit,
                (current_stock <= minimum_stock_level) as needs_restock
            FROM products
            ORDER BY needs_restock DESC, product_name";

            $result = $conn->query($query);
            $stock_levels = [];

            while ($row = $result->fetch_assoc()) {
                $stock_levels[] = $row;
            }

            echo json_encode([
                'status' => 'success',
                'stock_levels' => $stock_levels
            ]);
            break;

        // Get Products for Dropdown
        case 'get_products':
            $query = "SELECT id, product_name FROM products ORDER BY product_name";
            $result = $conn->query($query);
            $products = [];

            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            echo json_encode([
                'status' => 'success',
                'products' => $products
            ]);
            break;

        default:
            echo json_encode([
                'status' => 'error', 
                'message' => 'Invalid action'
            ]);
    }
}

// Close database connection
$conn->close();
?>