<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

// Load PHPMailer if it exists
$phpmailerEnabled = file_exists('vendor/autoload.php');
if ($phpmailerEnabled) {
    require 'vendor/autoload.php';
}

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'register':
            handleRegistration();
            break;
            
        case 'login':
            handleLogin();
            break;
            
        case 'get_user':
            getCurrentUser();
            break;
            
        case 'change_name':
            changeUserName();
            break;
            
        case 'change_password':
            changePassword();
            break;
            
        case 'forgot_password':
            handleForgotPassword();
            break;
            
        case 'reset_password':
            handlePasswordReset();
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function handleRegistration() {
    global $pdo;
    
    $required = ['full_name', 'email', 'password', 'confirm_password'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("All fields are required");
        }
    }
    
    if ($_POST['password'] !== $_POST['confirm_password']) {
        throw new Exception("Passwords do not match");
    }
    
    if (strlen($_POST['password']) < 8) {
        throw new Exception("Password must be at least 8 characters");
    }
    
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    if ($stmt->fetch()) {
        throw new Exception("Email already registered");
    }
    
    // Create user
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([
        trim($_POST['full_name']),
        $_POST['email'],
        $hashedPassword
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! You can now login.'
    ]);
}

function handleLogin() {
    global $pdo;
    
    if (empty($_POST['email']) || empty($_POST['password'])) {
        throw new Exception("Email and password are required");
    }
    
    // Check login attempts
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $pdo->prepare("SELECT attempts FROM login_attempts WHERE ip_address = ?");
    $stmt->execute([$ip]);
    $attempt = $stmt->fetch();
    
    if ($attempt && $attempt['attempts'] >= 5) {
        throw new Exception("Too many login attempts. Try again later.");
    }
    
    // Find user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($_POST['password'], $user['password'])) {
        // Record failed attempt
        $stmt = $pdo->prepare(
            "INSERT INTO login_attempts (ip_address, attempts) VALUES (?, 1)
             ON DUPLICATE KEY UPDATE attempts = attempts + 1"
        );
        $stmt->execute([$ip]);
        
        throw new Exception("Invalid email or password");
    }
    
    // Clear login attempts
    $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?")->execute([$ip]);
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    
    // Update last login
    $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
    
    // Set remember token if needed
    if (!empty($_POST['remember'])) {
        $token = bin2hex(random_bytes(32));
        setcookie('remember_token', $token, time() + 86400 * 30, '/', '', true, true);
        $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?")->execute([$token, $user['id']]);
    }
    
    // Check if request is AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        // Return JSON response for AJAX requests
        echo json_encode(['success' => true, 'redirect' => 'index.php']);
    } else {
        // Redirect for normal form submissions
        header("Location: index.php");
    }
    exit();
}

function getCurrentUser() {
    global $pdo;
    
    if (empty($_SESSION['user_id'])) {
        throw new Exception("Not authenticated");
    }
    
    $stmt = $pdo->prepare("SELECT id, full_name, email, role, last_login FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception("User not found");
    }
    
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
}

function changeUserName() {
    global $pdo;
    
    if (empty($_SESSION['user_id'])) {
        throw new Exception("Not authenticated");
    }
    
    if (empty($_POST['new_name']) || empty($_POST['password'])) {
        throw new Exception("All fields are required");
    }
    
    // Verify password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($_POST['password'], $user['password'])) {
        throw new Exception("Invalid password");
    }
    
    // Update name
    $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
    $stmt->execute([trim($_POST['new_name']), $_SESSION['user_id']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Name updated successfully'
    ]);
}

function changePassword() {
    global $pdo;
    
    if (empty($_SESSION['user_id'])) {
        throw new Exception("Not authenticated");
    }
    
    $required = ['current_password', 'new_password', 'confirm_password'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("All fields are required");
        }
    }
    
    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        throw new Exception("New passwords do not match");
    }
    
    if (strlen($_POST['new_password']) < 8) {
        throw new Exception("New password must be at least 8 characters");
    }
    
    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($_POST['current_password'], $user['password'])) {
        throw new Exception("Current password is incorrect");
    }
    
    // Update password
    $newHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$newHash, $_SESSION['user_id']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Password changed successfully'
    ]);
}

function handleForgotPassword() {
    global $pdo, $phpmailerEnabled;
    
    if (empty($_POST['email'])) {
        throw new Exception("Email is required");
    }
    
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id, email, full_name FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    
    // Always return success to prevent email enumeration
    $response = [
        'success' => true,
        'message' => 'If this email exists in our system, you will receive a password reset link.'
    ];
    
    if (!$user) {
        echo json_encode($response);
        return;
    }
    
    // Generate reset token (valid for 1 hour)
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);
    
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
    $stmt->execute([$token, $expires, $user['id']]);
    
    $resetLink = "https://yourdomain.com/reset-password.php?token=$token";
    
    if ($phpmailerEnabled) {
        // Send email using PHPMailer
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host       = defined('MAIL_HOST') ? MAIL_HOST : 'smtp.example.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = defined('MAIL_USERNAME') ? MAIL_USERNAME : 'your@email.com';
            $mail->Password   = defined('MAIL_PASSWORD') ? MAIL_PASSWORD : 'yourpassword';
            $mail->SMTPSecure = defined('MAIL_ENCRYPTION') ? MAIL_ENCRYPTION : 'tls';
            $mail->Port       = defined('MAIL_PORT') ? MAIL_PORT : 587;
            
            // Recipients
            $mail->setFrom(defined('MAIL_FROM') ? MAIL_FROM : 'no-reply@yourdomain.com', 
                          defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'Your System');
            $mail->addAddress($user['email'], $user['full_name']);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <h2>Password Reset Request</h2>
                <p>Hello {$user['full_name']},</p>
                <p>We received a request to reset your password. Click the link below to proceed:</p>
                <p><a href='$resetLink'>Reset Password</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
            ";
            
            $mail->send();
            $response['message'] = 'Password reset link has been sent to your email.';
        } catch (Exception $e) {
            error_log("Mailer Error: " . $e->getMessage());
            throw new Exception("Failed to send email. Please try again later.");
        }
    } else {
        // Fallback to PHP mail() function
        $to = $user['email'];
        $subject = 'Password Reset Request';
        $message = "
            <html>
            <body>
                <h2>Password Reset Request</h2>
                <p>Hello {$user['full_name']},</p>
                <p>We received a request to reset your password. Click the link below to proceed:</p>
                <p><a href='$resetLink'>Reset Password</a></p>
                <p>This link will expire in 1 hour.</p>
            </body>
            </html>
        ";
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@yourdomain.com\r\n";
        
        if (!mail($to, $subject, $message, $headers)) {
            error_log("Failed to send password reset email to $to");
            throw new Exception("Failed to send email. Please try again later.");
        }
        
        $response['message'] = 'Password reset link has been sent to your email.';
    }
    
    // For development/testing only - remove in production
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        $response['reset_link'] = $resetLink;
    }
    
    echo json_encode($response);
}

function handlePasswordReset() {
    global $pdo;
    
    $required = ['token', 'new_password', 'confirm_password'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("All fields are required");
        }
    }
    
    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        throw new Exception("Passwords do not match");
    }
    
    if (strlen($_POST['new_password']) < 8) {
        throw new Exception("Password must be at least 8 characters");
    }
    
    // Find user with valid token
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$_POST['token']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception("Invalid or expired token. Please request a new password reset.");
    }
    
    // Update password and clear token
    $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
    $stmt->execute([$hashedPassword, $user['id']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Password reset successfully. You can now login with your new password.'
    ]);
}