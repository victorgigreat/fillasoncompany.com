<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fillason Multibiz - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .auth-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .auth-card:hover {
            transform: translateY(-5px);
        }
        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            padding: 2rem;
            text-align: center;
            color: white;
            position: relative;
        }
        .company-logo {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #ddd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .password-meter {
            height: 5px;
            margin-top: 5px;
        }
        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card card">
                    <div class="auth-header">
                        <div class="company-logo">FILLASON MULTIBIZ</div>
                        <small>309 AJEBAMIDELE ADO EKITI, EKITI STATE</small>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Create Account</h4>
                        <form id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="full_name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-meter progress mt-2">
                                    <div id="passwordStrength" class="progress-bar" role="progressbar"></div>
                                </div>
                                <small class="text-muted">Must contain 8+ chars with uppercase, number & symbol</small>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <div class="invalid-feedback" id="passwordError">Passwords don't match</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                <i class="fas fa-user-plus me-2"></i> Register
                            </button>
                            <div class="text-center">
                                Already have an account? <a href="login.php">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="notification" class="floating-alert alert alert-dismissible fade show d-none">
        <span id="notificationMessage"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Password visibility toggle
            $('.toggle-password').click(function() {
                const icon = $(this).find('i');
                const input = $(this).siblings('input');
                input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
                icon.toggleClass('fa-eye fa-eye-slash');
            });

            // Password strength meter
            $('#password').on('input', function() {
                const pass = $(this).val();
                let strength = 0;
                
                // Length check
                if (pass.length >= 8) strength += 25;
                if (pass.length >= 12) strength += 25;
                
                // Complexity
                if (/[A-Z]/.test(pass)) strength += 15;
                if (/[0-9]/.test(pass)) strength += 15;
                if (/[^A-Za-z0-9]/.test(pass)) strength += 20;
                
                // Update UI
                const $bar = $('#passwordStrength');
                $bar.css('width', strength + '%')
                   .removeClass('bg-danger bg-warning bg-success');
                
                if (strength < 50) {
                    $bar.addClass('bg-danger');
                } else if (strength < 75) {
                    $bar.addClass('bg-warning');
                } else {
                    $bar.addClass('bg-success');
                }
            });

            // Form submission
            $('#registerForm').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                
                // Validate password match
                if ($('input[name="password"]').val() !== $('input[name="confirm_password"]').val()) {
                    $('#passwordError').show();
                    return;
                }
                
                // Submit via AJAX
                $.ajax({
                    url: 'auth.php?action=register',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin me-2"></i> Registering...');
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message, 'success');
                            setTimeout(() => window.location.href = 'login.php', 2000);
                        } else {
                            showNotification(response.error, 'danger');
                            $('button[type="submit"]').prop('disabled', false).html('Register');
                        }
                    },
                    error: function() {
                        showNotification('Registration failed. Please try again.', 'danger');
                        $('button[type="submit"]').prop('disabled', false).html('Register');
                    }
                });
            });
        });

        function showNotification(message, type) {
            const $alert = $('#notification');
            $alert.removeClass('alert-success alert-danger alert-warning alert-info')
                 .addClass('alert-' + type)
                 .removeClass('d-none');
            $('#notificationMessage').text(message);
            setTimeout(() => $alert.addClass('d-none'), 5000);
        }
    </script>
</body>
</html>