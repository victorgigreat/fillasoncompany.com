<?php
require_once 'check_auth.php'; // Ensures only logged-in users can access
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Fillason Multibiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --light: #f8f9fa;
            --dark: #1a252f;
        }
        
        body {
            background-color: #f5f7fa;
            padding-top: 70px;
            padding-bottom: 70px;
        }
        
        .profile-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .profile-pic {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.2);
            border-radius: 50%;
        }
        
        .nav-pills .nav-link {
            color: var(--primary);
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary);
            color: white;
        }
        
        .nav-pills .nav-link:hover:not(.active) {
            background-color: rgba(44, 62, 80, 0.1);
        }
        
        .form-control:read-only {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        
        .back-btn {
            position: absolute;
            left: 15px;
            top: 15px;
            color: white;
            font-size: 1.2rem;
        }
        
        .developer-section {
            border-top: 1px solid #e9ecef;
            padding-top: 1rem;
            margin-top: 1rem;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .developer-section a {
            color: var(--secondary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .developer-section a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header with back button -->
    <header class="company-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2">
                    <a href="index.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-8 text-center">
                    <h4 class="company-name mb-1">MY PROFILE</h4>
                </div>
                <div class="col-2 text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Profile Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card card mb-4">
                    <div class="profile-header">
                        <img src="assets/default-avatar.jpg" class="profile-pic mb-3">
                        <h4 id="userFullName">Loading...</h4>
                        <p id="userEmail" class="text-light opacity-75 mb-0">user@example.com</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="nav flex-column nav-pills">
                                    <button class="nav-link active" data-section="profile-info">
                                        <i class="fas fa-user-circle me-2"></i> Profile Info
                                    </button>
                                    <button class="nav-link" data-section="change-password">
                                        <i class="fas fa-lock me-2"></i> Change Password
                                    </button>
                                    <a href="logout.php" class="nav-link text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <!-- Profile Info Section -->
                                <div id="profile-info" class="active">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="displayName" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="displayEmail" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <input type="text" class="form-control" id="displayRole" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last Login</label>
                                        <input type="text" class="form-control" id="displayLastLogin" readonly>
                                    </div>
                                </div>

                                <!-- Change Password Section -->
                                <div id="change-password" style="display: none;">
                                    <form id="passwordForm">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                                            <div class="form-text">Must be at least 8 characters</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="confirm_password" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-save me-2"></i> Update Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Developer Section -->
                        <div class="developer-section" id="developer-section">
                            <p>
                                Built by a skilled developer specializing in modern web applications. 
                                <a href="https://tobestdev.github.io/OTM_CV/" target="_blank" rel="noopener">Learn more</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="navbar fixed-bottom navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="nav-link mx-auto text-center">
                <i class="fas fa-home fs-5"></i>
                <div class="small">Home</div>
            </a>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load user data
            $.ajax({
                url: 'auth.php?action=get_user',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#userFullName').text(response.data.full_name);
                        $('#userEmail').text(response.data.email);
                        $('#displayName').val(response.data.full_name);
                        $('#displayEmail').val(response.data.email);
                        $('#displayRole').val(response.data.role.charAt(0).toUpperCase() + response.data.role.slice(1));
                        $('#displayLastLogin').val(response.data.last_login || 'Never logged in');
                        // Show developer section only for admin users (optional)
                        if (response.data.role !== 'admin') {
                            $('#developer-section').show();
                        }
                    }
                },
                error: function(xhr) {
                    alert('Error loading profile data: ' + xhr.responseText);
                }
            });

            // Section navigation
            $('.nav-link').click(function(e) {
                e.preventDefault();
                
                if ($(this).hasClass('active')) return;
                
                // Handle logout link
                if ($(this).attr('href')) {
                    window.location = $(this).attr('href');
                    return;
                }
                
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                
                // Show/hide sections
                const sectionId = $(this).data('section');
                $('#' + sectionId).show().siblings('#profile-info, #change-password').hide();
            });

            // Password form submission
            $('#passwordForm').submit(function(e) {
                e.preventDefault();
                
                if ($('#newPassword').val() !== $('input[name="confirm_password"]').val()) {
                    alert('Passwords do not match!');
                    return;
                }
                
                $.ajax({
                    url: 'auth.php?action=change_password',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        alert(response.success ? 'Password changed successfully!' : response.error);
                        if (response.success) {
                            $('#passwordForm')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        alert('Error changing password: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>