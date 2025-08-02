<?php
/**
 * CST8285 Assignment 2 - User Login Page
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This page allows users to login to the task management application
 */

// Start session
session_start();

// If user is already logged in, redirect to tasks page
if (isset($_SESSION['user_id'])) {
    header('Location: tasks.php');
    exit();
}

// Check for error messages from login process
$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_credentials':
            $error_message = 'Invalid email or password. Please try again.';
            break;
        case 'empty_fields':
            $error_message = 'Please fill in all required fields.';
            break;
        default:
            $error_message = 'An error occurred. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container">
        <div class="glass-card">
            <div class="header">
                <h1>🔐 Welcome Back</h1>
                <p>Login to access your task dashboard</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form id="login-form" action="../server/login_user.php" method="POST" onsubmit="return Validator.validateLogin()">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p style="color: rgba(255, 255, 255, 0.8);">
                        Don't have an account? 
                        <a href="register.php" style="color: white; text-decoration: underline;">Register here</a>
                    </p>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="../index.php" class="btn btn-secondary">← Back to Home</a>
        </div>
    </div>
    
    <script src="../scripts/validate.js"></script>
</body>
</html> 