<?php
/**
 * CST8285 Assignment 2 - Database Connection Script
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This script establishes a secure connection to the MySQL database
 * using procedural PHP with mysqli
 */

// Database configuration
$host = 'localhost';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)
$database = 'task_manager';

// Create connection using mysqli (procedural style)
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8 for proper character encoding
mysqli_set_charset($conn, "utf8");

// Optional: Set timezone for consistent date handling
date_default_timezone_set('America/Toronto');
?> 