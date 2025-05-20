<?php
require_once 'config.php';

function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStockHistory($limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT sm.*, p.name as product_name 
                          FROM stock_movements sm
                          JOIN products p ON sm.product_id = p.id
                          ORDER BY sm.created_at DESC
                          LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>