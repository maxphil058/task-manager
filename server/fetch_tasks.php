<?php
/**
 * CST8285 Assignment 2 - Task Fetching Backend
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This script fetches tasks from the database and returns them as JSON
 * Supports filtering by keyword, priority, and date
 */

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Include database connection
require_once 'connect.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    $user_id = $_SESSION['user_id'];
    
    // Base query to get tasks for the current user
    $query = "SELECT id, title, description, priority, due_date, created_at FROM tasks WHERE user_id = ?";
    $params = [$user_id];
    $types = 'i';
    
    // Handle filtering
    $keyword = trim($_POST['keyword'] ?? $_GET['keyword'] ?? '');
    $priority = trim($_POST['priority'] ?? $_GET['priority'] ?? '');
    $date = trim($_POST['date'] ?? $_GET['date'] ?? '');
    
    // Add keyword filter
    if (!empty($keyword)) {
        $query .= " AND (title LIKE ? OR description LIKE ?)";
        $keyword_param = "%$keyword%";
        $params[] = $keyword_param;
        $params[] = $keyword_param;
        $types .= 'ss';
    }
    
    // Add priority filter
    if (!empty($priority) && in_array($priority, ['high', 'medium', 'low'])) {
        $query .= " AND priority = ?";
        $params[] = $priority;
        $types .= 's';
    }
    
    // Add date filter
    if (!empty($date)) {
        $query .= " AND DATE(due_date) = ?";
        $params[] = $date;
        $types .= 's';
    }
    
    // Order by due date (earliest first), then by priority (high first)
    $query .= " ORDER BY due_date ASC, 
                CASE priority 
                    WHEN 'high' THEN 1 
                    WHEN 'medium' THEN 2 
                    WHEN 'low' THEN 3 
                END ASC, 
                created_at DESC";
    
    // Prepare and execute the statement
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        throw new Exception('Database preparation failed');
    }
    
    // Bind parameters dynamically
    if (count($params) > 1) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    } else {
        mysqli_stmt_bind_param($stmt, $types, $user_id);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        throw new Exception('Failed to fetch tasks');
    }
    
    // Fetch all tasks
    $tasks = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Sanitize data for JSON output
        $tasks[] = [
            'id' => (int)$row['id'],
            'title' => htmlspecialchars($row['title']),
            'description' => htmlspecialchars($row['description']),
            'priority' => $row['priority'],
            'due_date' => $row['due_date'],
            'created_at' => $row['created_at']
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    // Return tasks as JSON
    echo json_encode($tasks);
    
} catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log('Task fetching error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch tasks']);
    
} finally {
    // Close database connection
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?> 