<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
session_start();

// If user is already logged in, redirect to index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check for success/error messages from redirects
$alertType = '';
$alertMessage = '';

if (isset($_GET['error'])) {
    $alertType = 'danger';
    $alertMessage = htmlspecialchars($_GET['error']);
} elseif (isset($_GET['success'])) {
    $alertType = 'success';
    $alertMessage = htmlspecialchars($_GET['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Fillason Multibiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .forgot-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease;
        }
        
        .forgot-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .forgot-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .company-logo {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: inline-block;
            background: linear-gradient(to right, #fff, #ddd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .forgot-body {
            padding: 30px;
            background-color: white;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-reset {
            background: linear-gradient(to right, var(--primary-color), var(--dark-color));
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
            color: white;
            transition: all 0.3s;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            animation: slideInRight 0.5s ease, fadeOut 1s ease 4s forwards;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeOut {
            to { opacity: 0; }
        }
        
        /* Mobile optimizations */
        @media (max-width: 576px) {
            .forgot-container {
                padding: 0 15px;
            }
            
            .forgot-header {
                padding: 25px 15px;
            }
            
            .company-logo {
                font-size: 1.8rem;
            }
            
            .forgot-body {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="company-logo">FILLASON MULTIBIZ</div>
                <div>Password Recovery</div>
            </div>
            <div class="forgot-body">
                <form id="forgotForm">
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-text">We'll send a password reset link to this email</div>
                    </div>
                    <button type="submit" class="btn btn-reset w-100 mb-3">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                    <div class="text-center">
                        Remember your password? <a href="login.php" class="text-decoration-none">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Alert -->
    <div id="notification" class="notification alert alert-dismissible fade show d-none" role="alert">
        <span id="notificationMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show initial alert if present
            <?php if (!empty($alertMessage)): ?>
                showNotification(`<?php echo $alertMessage; ?>`, `<?php echo $alertType; ?>`);
            <?php endif; ?>

            // Handle form submission
            $('#forgotForm').submit(function(e) {
                e.preventDefault();
                
                const email = $('#email').val().trim();
                if (!email) {
                    showNotification('Please enter your email address', 'danger');
                    return;
                }
                
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Sending...');
                
                $.ajax({
                    url: 'auth.php?action=forgot_password',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message, 'success');
                            // Clear form on success
                            $('#forgotForm')[0].reset();
                            
                            // In development, show reset link in console
                            if (response.reset_link) {
                                console.log('Reset link (dev only):', response.reset_link);
                            }
                        } else {
                            showNotification(response.error, 'danger');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred while processing your request';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) errorMsg = response.error;
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                        showNotification(errorMsg, 'danger');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.html('<i class="fas fa-paper-plane me-2"></i> Send Reset Link');
                    }
                });
            });
        });
        
        function showNotification(message, type) {
            const notification = $('#notification');
            notification.removeClass('alert-success alert-danger alert-warning alert-info')
                       .addClass(`alert-${type}`)
                       .removeClass('d-none');
            
            $('#notificationMessage').text(message);
            
            // Reset animation by briefly removing and adding the classes
            notification.removeClass('fade show');
            setTimeout(() => {
                notification.addClass('fade show');
            }, 10);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                notification.addClass('d-none');
            }, 5000);
        }
    </script>
</body>
</html>