<?php
require_once '../config.php';

header('Content-Type: application/json');

$searchTerm = $_GET['q'] ?? '';

try {
    $stmt = $pdo->prepare("
        SELECT id, name, current_stock, price 
        FROM products 
        WHERE name LIKE :search
        ORDER BY name
        LIMIT 20
    ");
    
    $stmt->bindValue(':search', '%' . $searchTerm . '%');
    $stmt->execute();
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>