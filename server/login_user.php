<?php
/**
 * CST8285 Assignment 2 - User Login Backend
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This script handles user authentication and session management
 */

// Start session
session_start();

// Include database connection
require_once 'connect.php';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit();
}

// Get form data and sanitize inputs
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic server-side validation
$errors = [];

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($password)) {
    $errors[] = 'Password is required';
}

// If there are validation errors, redirect back with errors
if (!empty($errors)) {
    $error_message = implode(', ', $errors);
    header('Location: ../pages/login.php?error=empty_fields');
    exit();
}

try {
    // Query to get user by email using prepared statement
    $query = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        throw new Exception('Database preparation failed');
    }
    
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) === 0) {
        // User not found
        mysqli_stmt_close($stmt);
        header('Location: ../pages/login.php?error=invalid_credentials');
        exit();
    }
    
    // Bind result variables
    mysqli_stmt_bind_result($stmt, $user_id, $user_name, $user_email, $hashed_password);
    mysqli_stmt_fetch($stmt);
    
    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Password is correct - create session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_email'] = $user_email;
        
        // Set session timeout (optional - 2 hours)
        $_SESSION['last_activity'] = time();
        
        mysqli_stmt_close($stmt);
        
        // Redirect to tasks page
        header('Location: ../pages/tasks.php');
        exit();
        
    } else {
        // Password is incorrect
        mysqli_stmt_close($stmt);
        header('Location: ../pages/login.php?error=invalid_credentials');
        exit();
    }
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Login error: ' . $e->getMessage());
    
    // Redirect back with error message
    header('Location: ../pages/login.php?error=invalid_credentials');
    exit();
    
} finally {
    // Close database connection
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?> 