<?php
session_start();

function checkAuth() {
    // Check if user is logged in via session
    if (!empty($_SESSION['user_id'])) {
        return true;
    }
    
    // Check for remember me cookie
    if (!empty($_COOKIE['remember_token'])) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, role FROM users WHERE remember_token = ?");
        $stmt->execute([$_COOKIE['remember_token']]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
    }
    
    return false;
}

// Redirect to login if not authenticated
if (!checkAuth()) {
    header("Location: login.php");
    exit();
}
?>