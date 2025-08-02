-- CST8285 Assignment 2 - Task Management Database Schema
-- Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS task_manager;
USE task_manager;

-- USERS TABLE
-- Stores user registration information with password hashing
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TASKS TABLE
-- Stores user tasks with foreign key relationship to users table
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority VARCHAR(20) NOT NULL,
    due_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample data for testing (optional)
-- INSERT INTO users (name, email, password) VALUES 
-- ('Test User', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); 