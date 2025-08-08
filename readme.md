CST8285 Assignment 2 - Task Management Web Application
=====================================================

Team Information:
-----------------
Lead Developer: Phil Maxwell-Mgbudem
Review & Testing: Favour Adebayo

Course: CST8285 Web Programming
Institution: Algonquin College


Project Overview:
-----------------
This is a complete task management web application built using only HTML, CSS, JavaScript, PHP (procedural with mysqli), and MySQL. The application demonstrates proficiency in web development fundamentals without using any frameworks or external libraries.

Key Features:
-------------
✅ User Registration and Login System
✅ Secure Password Hashing
✅ Task Creation and Management
✅ Task Filtering and Search
✅ Responsive Design with Glass Morphism
✅ Client-side Form Validation
✅ Session Management
✅ SQL Injection Prevention

Technology Stack:
-----------------
- Frontend: HTML5, CSS3, Vanilla JavaScript
- Backend: PHP (Procedural)
- Database: MySQL with mysqli
- Server: XAMPP (Apache + MySQL)
- No frameworks, libraries, or external dependencies

File Structure:
---------------
task-manager/
├── index.php                    # Landing page
├── pages/
│   ├── register.php            # User registration
│   ├── login.php               # User login
│   └── tasks.php               # Main dashboard
├── scripts/
│   └── validate.js             # Form validation
├── server/
│   ├── connect.php             # Database connection
│   ├── insert_user.php         # User registration backend
│   ├── login_user.php          # User login backend
│   ├── insert_task.php         # Task creation backend
│   ├── fetch_tasks.php         # Task retrieval backend
│   ├── delete_task.php         # Task deletion backend
│   └── logout.php              # Logout functionality
├── styles/
│   └── style.css               # Main stylesheet
├── database/
│   └── create_tables.sql       # Database schema
└── documentation/
    ├── readme.txt              # This file
    ├── summary.txt             # How the application works
    └── flow.txt                # Step-by-step flow

Installation Instructions:
-------------------------
1. Install XAMPP on your system
2. Start Apache and MySQL services
3. Place this folder in htdocs directory
4. Import database/create_tables.sql into phpMyAdmin
5. Access the application at http://localhost/task-manager

Security Features:
------------------
- Password hashing using PHP's password_hash()
- Prepared statements to prevent SQL injection
- Session-based authentication
- Input validation and sanitization
- CSRF protection through session validation

Development Notes:
------------------
- All code is thoroughly commented for educational purposes
- Follows procedural PHP patterns (no classes)
- Uses only vanilla JavaScript (no jQuery or frameworks)
- Responsive design using CSS Grid and Flexbox
- Modern UI with glass morphism effects and gradients

Testing:
---------
- Tested on XAMPP with PHP 8.x and MySQL 8.x
- Cross-browser compatibility verified
- Mobile responsiveness tested
- All CRUD operations tested and working

This project demonstrates a solid understanding of web development fundamentals and serves as a foundation for more complex applications. 