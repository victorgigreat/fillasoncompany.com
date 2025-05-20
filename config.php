<?php
// Basic error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$dbname = 'cylgtjtg_store';
$username = 'cylgtjtg_store';
$password = 'cylgtjtg_store';

// Basic PDO connection without advanced options
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Basic security headers (safe to use)
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");


// Email Configuration
define('MAIL_HOST', 'fillasoncompany.shop');
define('MAIL_USERNAME', 'admin@fillasoncompany.shop');
define('MAIL_PASSWORD', '@fillasoncompany.shop');
define('MAIL_PORT', 465);
define('MAIL_ENCRYPTION', 'SSL');
define('MAIL_FROM', 'admin@fillasoncompany.shop');
define('MAIL_FROM_NAME', 'Fillason Multibiz');
define('ENVIRONMENT', 'development'); // Change to 'production' when live
?>