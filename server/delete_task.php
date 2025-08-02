<?php
/**
 * CST8285 Assignment 2 - Task Deletion Backend
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This script handles task deletion with proper security checks
 */

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Include database connection
require_once 'connect.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get task ID
$task_id = $_POST['task_id'] ?? '';

// Validate task ID
if (empty($task_id) || !is_numeric($task_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid task ID']);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // First, verify that the task belongs to the current user
    $check_query = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    
    if (!$check_stmt) {
        throw new Exception('Database preparation failed');
    }
    
    mysqli_stmt_bind_param($check_stmt, 'ii', $task_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) === 0) {
        mysqli_stmt_close($check_stmt);
        echo json_encode(['success' => false, 'message' => 'Task not found or access denied']);
        exit();
    }
    
    mysqli_stmt_close($check_stmt);
    
    // Delete the task using prepared statement
    $delete_query = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    
    if (!$delete_stmt) {
        throw new Exception('Database preparation failed');
    }
    
    mysqli_stmt_bind_param($delete_stmt, 'ii', $task_id, $user_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        // Check if any rows were affected
        if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
            mysqli_stmt_close($delete_stmt);
            echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
        } else {
            mysqli_stmt_close($delete_stmt);
            echo json_encode(['success' => false, 'message' => 'Task not found']);
        }
    } else {
        throw new Exception('Failed to delete task');
    }
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Task deletion error: ' . $e->getMessage());
    
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Failed to delete task. Please try again.']);
    
} finally {
    // Close database connection
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?> 