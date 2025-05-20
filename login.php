<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fillason Multibiz - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            overflow-x: hidden;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease;
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            animation: pulse 8s infinite linear;
        }
        
        .company-logo {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: inline-block;
            background: linear-gradient(to right, #fff, #ddd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .company-slogan {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .login-body {
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
        
        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--dark-color));
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .divider {
            position: relative;
            text-align: center;
            margin: 20px 0;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        
        .divider span {
            position: relative;
            display: inline-block;
            padding: 0 15px;
            background-color: white;
            z-index: 2;
            color: #7f8c8d;
            font-size: 0.8rem;
        }
        
        .social-login .btn {
            border-radius: 8px;
            padding: 10px;
            font-size: 0.9rem;
        }
        
        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideInRight 0.5s ease, fadeOut 1s ease 4s forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }
        
        /* Mobile optimizations */
        @media (max-width: 576px) {
            .login-container {
                padding: 0 15px;
            }
            
            .login-header {
                padding: 25px 15px;
            }
            
            .company-logo {
                font-size: 1.8rem;
            }
            
            .login-body {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="company-logo">FILLASON MULTIBIZ</div>
                <div class="company-slogan">309 AJEBAMIDELE ADO EKITI, EKITI STATE</div>
            </div>
            <div class="login-body">
                <form id="loginForm" action="auth.php" method="POST">
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="forgot-password.php" class="text-decoration-none">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-login btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                    
                   
                    <!-- Disable Registration
                    
                    <div class="divider">
                        <span>OR</span>
                    </div>
                    <div class="social-login d-flex gap-2 mb-4">
                        <a href="#" class="btn btn-outline-primary flex-grow-1">
                        <i class="fab fa-google me-2"></i> Google
                        </a>
                      <a href="#" class="btn btn-outline-dark flex-grow-1">
                       <i class="fab fa-microsoft me-2"></i> Microsoft
                       </a>
                    </div>
                <div class="text-center">
                    Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a>
                    </div>
                    
                -->
                
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Alert -->
    <div id="notificationAlert" class="floating-alert alert alert-dismissible fade show d-none" role="alert">
        <span id="notificationMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password').click(function() {
                const icon = $(this).find('i');
                const passwordInput = $('#password');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Handle form submission
           // Handle form submission
$('#loginForm').submit(function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...');
    
    // Submit via AJAX
    $.ajax({
        url: 'auth.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Redirect to index.php
                window.location.href = response.redirect;
            } else {
                showNotification(response.error, 'danger');
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-sign-in-alt me-2"></i> Login');
            }
        },
        error: function(xhr, status, error) {
            showNotification('An error occurred: ' + error, 'danger');
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fas fa-sign-in-alt me-2"></i> Login');
        }
    });
});
            
            // Check for URL parameters to show messages
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                showNotification(urlParams.get('error'), 'danger');
            }
            if (urlParams.has('success')) {
                showNotification(urlParams.get('success'), 'success');
            }
        });
        
        function showNotification(message, type) {
            const alert = $('#notificationAlert');
            alert.removeClass('alert-success alert-danger alert-warning alert-info')
                 .addClass(`alert-${type}`)
                 .removeClass('d-none');
            
            $('#notificationMessage').text(message);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alert.addClass('d-none');
            }, 5000);
        }
    </script>
</body>
</html>