<?php
/**
 * CST8285 Assignment 2 - Task Dashboard Page
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * This is the main dashboard where users can view, add, and manage their tasks
 */

// Start session and check if user is logged in
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user information
$user_name = $_SESSION['user_name'] ?? 'User';
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard - Task Manager</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="container">
        <!-- Dashboard Header -->
        <div class="glass-card">
            <div class="dashboard-header">
                <div class="welcome-message">
                    Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋
                </div>
                <div>
                    <a href="../server/logout.php" class="btn btn-secondary">🚪 Logout</a>
                </div>
            </div>
        </div>

        <!-- Add New Task Form -->
        <div class="glass-card">
            <h2 style="color: white; margin-bottom: 20px;">➕ Add New Task</h2>
            <form id="task-form" action="../server/insert_task.php" method="POST" onsubmit="return Validator.validateTask()">
                <div class="form-group">
                    <label for="task_title">Task Title</label>
                    <input type="text" id="task_title" name="title" class="form-control" placeholder="Enter task title">
                </div>
                
                <div class="form-group">
                    <label for="task_description">Description</label>
                    <textarea id="task_description" name="description" class="form-control" rows="3" placeholder="Describe your task"></textarea>
                </div>
                
                <div class="task-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="task_priority">Priority</label>
                            <select id="task_priority" name="priority" class="form-control">
                                <option value="">Select priority</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="task_due_date">Due Date</label>
                            <input type="date" id="task_due_date" name="due_date" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>

        <!-- Filter Section -->
        <div class="glass-card">
            <h2 style="color: white; margin-bottom: 20px;">🔍 Filter Tasks</h2>
            <div class="filter-section">
                <div class="filter-row">
                    <div class="form-group">
                        <label for="search_keyword">Search</label>
                        <input type="text" id="search_keyword" class="form-control" placeholder="Search tasks...">
                    </div>
                    
                    <div class="form-group">
                        <label for="filter_priority">Priority</label>
                        <select id="filter_priority" class="form-control">
                            <option value="">All priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="filter_date">Due Date</label>
                        <input type="date" id="filter_date" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <button type="button" onclick="filterTasks()" class="btn btn-secondary">Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="glass-card">
            <h2 style="color: white; margin-bottom: 20px;">📋 Your Tasks</h2>
            <div id="tasks-container">
                <!-- Tasks will be loaded here via JavaScript -->
                <div style="text-align: center; color: rgba(255, 255, 255, 0.7);">
                    Loading tasks...
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/validate.js"></script>
    <script>
        // Load tasks when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
        });

        // Function to load tasks from server
        function loadTasks() {
            fetch('../server/fetch_tasks.php')
                .then(response => response.json())
                .then(data => {
                    displayTasks(data);
                })
                .catch(error => {
                    console.error('Error loading tasks:', error);
                    document.getElementById('tasks-container').innerHTML = 
                        '<div style="text-align: center; color: #ff6b6b;">Error loading tasks. Please try again.</div>';
                });
        }

        // Function to display tasks
        function displayTasks(tasks) {
            const container = document.getElementById('tasks-container');
            
            if (tasks.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: rgba(255, 255, 255, 0.7);">No tasks found. Create your first task above!</div>';
                return;
            }

            let html = '<div class="task-list">';
            
            tasks.forEach(task => {
                const priorityClass = `priority-${task.priority}`;
                const dueDate = new Date(task.due_date).toLocaleDateString();
                
                html += `
                    <div class="task-item">
                        <div class="task-header">
                            <div>
                                <div class="task-title">${escapeHtml(task.title)}</div>
                                <span class="task-priority ${priorityClass}">${task.priority.toUpperCase()}</span>
                            </div>
                            <button onclick="deleteTask(${task.id})" class="delete-btn">🗑️ Delete</button>
                        </div>
                        <div class="task-description">${escapeHtml(task.description)}</div>
                        <div class="task-meta">
                            <span>Due: ${dueDate}</span>
                            <span>Created: ${new Date(task.created_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        }

        // Function to delete a task
        function deleteTask(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                const formData = new FormData();
                formData.append('task_id', taskId);
                
                fetch('../server/delete_task.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadTasks(); // Reload tasks after deletion
                    } else {
                        alert('Error deleting task: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting task:', error);
                    alert('Error deleting task. Please try again.');
                });
            }
        }

        // Function to filter tasks
        function filterTasks() {
            const keyword = document.getElementById('search_keyword').value;
            const priority = document.getElementById('filter_priority').value;
            const date = document.getElementById('filter_date').value;
            
            const formData = new FormData();
            formData.append('keyword', keyword);
            formData.append('priority', priority);
            formData.append('date', date);
            
            fetch('../server/fetch_tasks.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                displayTasks(data);
            })
            .catch(error => {
                console.error('Error filtering tasks:', error);
                alert('Error filtering tasks. Please try again.');
            });
        }

        // Function to escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Add event listeners for real-time filtering
        document.getElementById('search_keyword').addEventListener('input', function() {
            // Debounce the search
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(filterTasks, 500);
        });

        document.getElementById('filter_priority').addEventListener('change', filterTasks);
        document.getElementById('filter_date').addEventListener('change', filterTasks);
    </script>
</body>
</html> 