<?php
/**
 * CST8285 Assignment 2 - User Registration Backend
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This script handles user registration with password hashing and database insertion
 */

// Start session
session_start();

// Include database connection
require_once 'connect.php';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/register.php');
    exit();
}

// Get form data and sanitize inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic server-side validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($password)) {
    $errors[] = 'Password is required';
} elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters long';
}

// If there are validation errors, redirect back with errors
if (!empty($errors)) {
    $error_message = implode(', ', $errors);
    header('Location: ../pages/register.php?error=' . urlencode($error_message));
    exit();
}

try {
    // Check if email already exists
    $check_email_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_email_query);
    
    if (!$check_stmt) {
        throw new Exception('Database preparation failed');
    }
    
    mysqli_stmt_bind_param($check_stmt, 's', $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        mysqli_stmt_close($check_stmt);
        header('Location: ../pages/register.php?error=' . urlencode('Email address already exists'));
        exit();
    }
    
    mysqli_stmt_close($check_stmt);
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user into database using prepared statement
    $insert_query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    
    if (!$insert_stmt) {
        throw new Exception('Database preparation failed');
    }
    
    mysqli_stmt_bind_param($insert_stmt, 'sss', $name, $email, $hashed_password);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        // Registration successful
        $user_id = mysqli_insert_id($conn);
        
        // Start session and store user data
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        mysqli_stmt_close($insert_stmt);
        
        // Redirect to tasks page
        header('Location: ../pages/tasks.php');
        exit();
        
    } else {
        throw new Exception('Failed to insert user into database');
    }
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Registration error: ' . $e->getMessage());
    
    // Redirect back with error message
    header('Location: ../pages/register.php?error=' . urlencode('Registration failed. Please try again.'));
    exit();
    
} finally {
    // Close database connection
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?> 