<?php
/**
 * CST8285 Assignment 2 - Task Insertion Backend
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This script handles task creation and insertion into the database
 */

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

// Include database connection
require_once 'connect.php';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/tasks.php');
    exit();
}

// Get form data and sanitize inputs
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$priority = $_POST['priority'] ?? '';
$due_date = $_POST['due_date'] ?? '';

// Basic server-side validation
$errors = [];

if (empty($title)) {
    $errors[] = 'Task title is required';
} elseif (strlen($title) < 3) {
    $errors[] = 'Task title must be at least 3 characters long';
}

if (empty($description)) {
    $errors[] = 'Task description is required';
} elseif (strlen($description) < 10) {
    $errors[] = 'Task description must be at least 10 characters long';
}

if (empty($priority)) {
    $errors[] = 'Priority is required';
} elseif (!in_array($priority, ['high', 'medium', 'low'])) {
    $errors[] = 'Invalid priority level';
}

if (empty($due_date)) {
    $errors[] = 'Due date is required';
} else {
    // Validate date format and ensure it's not in the past
    $date_obj = DateTime::createFromFormat('Y-m-d', $due_date);
    if (!$date_obj || $date_obj->format('Y-m-d') !== $due_date) {
        $errors[] = 'Invalid date format';
    } else {
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        if ($date_obj < $today) {
            $errors[] = 'Due date cannot be in the past';
        }
    }
}

// If there are validation errors, redirect back with errors
if (!empty($errors)) {
    $error_message = implode(', ', $errors);
    header('Location: ../pages/tasks.php?error=' . urlencode($error_message));
    exit();
}

try {
    // Insert task into database using prepared statement
    $query = "INSERT INTO tasks (user_id, title, description, priority, due_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        throw new Exception('Database preparation failed');
    }
    
    $user_id = $_SESSION['user_id'];
    mysqli_stmt_bind_param($stmt, 'issss', $user_id, $title, $description, $priority, $due_date);
    
    if (mysqli_stmt_execute($stmt)) {
        // Task created successfully
        mysqli_stmt_close($stmt);
        
        // Redirect back to tasks page with success message
        header('Location: ../pages/tasks.php?success=Task created successfully');
        exit();
        
    } else {
        throw new Exception('Failed to insert task into database');
    }
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Task creation error: ' . $e->getMessage());
    
    // Redirect back with error message
    header('Location: ../pages/tasks.php?error=' . urlencode('Failed to create task. Please try again.'));
    exit();
    
} finally {
    // Close database connection
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?> 