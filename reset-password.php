<?php 
require_once 'config.php';
$token = $_GET['token'] ?? '';
if (empty($token)) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Reset Password</h4>
                <form id="resetForm">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resetForm').submit(function(e) {
                e.preventDefault();
                
                if ($('#new_password').val() !== $('#confirm_password').val()) {
                    alert('Passwords do not match');
                    return;
                }
                
                if ($('#new_password').val().length < 8) {
                    alert('Password must be at least 8 characters');
                    return;
                }
                
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('Processing...');
                
                $.ajax({
                    url: 'auth.php?action=reset_password',
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = 'login.php';
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function(xhr) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            alert(response.error);
                        } catch {
                            alert('An error occurred');
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html('Reset Password');
                    }
                });
            });
        });
    </script>
</body>
</html>