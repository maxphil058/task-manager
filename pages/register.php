<?php
/**
 * CST8285 Assignment 2 - User Registration Page
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This page allows new users to register for the task management application
 */

// Start session
session_start();

// If user is already logged in, redirect to tasks page
if (isset($_SESSION['user_id'])) {
    header('Location: tasks.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Manager</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container">
        <div class="glass-card">
            <div class="header">
                <h1>📝 Create Account</h1>
                <p>Join us and start managing your tasks efficiently</p>
            </div>
            
            <div class="form-container">
                <form id="registration-form" action="../server/insert_user.php" method="POST" onsubmit="return Validator.validateRegistration()">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password (min 6 characters)">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p style="color: rgba(255, 255, 255, 0.8);">
                        Already have an account? 
                        <a href="login.php" style="color: white; text-decoration: underline;">Login here</a>
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