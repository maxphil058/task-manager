/**
 * CST8285 Assignment 2 - Form Validation Script
 * Created by: Phil Maxwell-Mgbudem (lead developer), Favour Adebayo (helped review)
 * 
 * Client-side form validation using pure JavaScript (no HTML5 validation)
 * Shows custom error messages and applies error styling
 */

// Validation functions
const Validator = {
    /**
     * Validates registration form
     */
    validateRegistration: function() {
        let isValid = true;
        
        // Get form elements
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        
        // Clear previous errors
        this.clearErrors();
        
        // Validate name
        if (!name.value.trim()) {
            this.showError(name, 'Name is required');
            isValid = false;
        } else if (name.value.trim().length < 2) {
            this.showError(name, 'Name must be at least 2 characters long');
            isValid = false;
        }
        
        // Validate email
        if (!email.value.trim()) {
            this.showError(email, 'Email is required');
            isValid = false;
        } else if (!this.isValidEmail(email.value)) {
            this.showError(email, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate password
        if (!password.value) {
            this.showError(password, 'Password is required');
            isValid = false;
        } else if (password.value.length < 6) {
            this.showError(password, 'Password must be at least 6 characters long');
            isValid = false;
        }
        
        // Validate confirm password
        if (!confirmPassword.value) {
            this.showError(confirmPassword, 'Please confirm your password');
            isValid = false;
        } else if (password.value !== confirmPassword.value) {
            this.showError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }
        
        return isValid;
    },
    
    /**
     * Validates login form
     */
    validateLogin: function() {
        let isValid = true;
        
        // Get form elements
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        
        // Clear previous errors
        this.clearErrors();
        
        // Validate email
        if (!email.value.trim()) {
            this.showError(email, 'Email is required');
            isValid = false;
        } else if (!this.isValidEmail(email.value)) {
            this.showError(email, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate password
        if (!password.value) {
            this.showError(password, 'Password is required');
            isValid = false;
        }
        
        return isValid;
    },
    
    /**
     * Validates task form
     */
    validateTask: function() {
        let isValid = true;
        
        // Get form elements
        const title = document.getElementById('task_title');
        const description = document.getElementById('task_description');
        const priority = document.getElementById('task_priority');
        const dueDate = document.getElementById('task_due_date');
        
        // Clear previous errors
        this.clearErrors();
        
        // Validate title
        if (!title.value.trim()) {
            this.showError(title, 'Task title is required');
            isValid = false;
        } else if (title.value.trim().length < 3) {
            this.showError(title, 'Task title must be at least 3 characters long');
            isValid = false;
        }
        
        // Validate description
        if (!description.value.trim()) {
            this.showError(description, 'Task description is required');
            isValid = false;
        } else if (description.value.trim().length < 10) {
            this.showError(description, 'Task description must be at least 10 characters long');
            isValid = false;
        }
        
        // Validate priority
        if (!priority.value) {
            this.showError(priority, 'Please select a priority level');
            isValid = false;
        }
        
        // Validate due date
        if (!dueDate.value) {
            this.showError(dueDate, 'Due date is required');
            isValid = false;
        } else {
            const selectedDate = new Date(dueDate.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                this.showError(dueDate, 'Due date cannot be in the past');
                isValid = false;
            }
        }
        
        return isValid;
    },
    
    /**
     * Validates email format using regex
     */
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    /**
     * Shows error message for a field
     */
    showError: function(field, message) {
        // Add error class to field
        field.classList.add('error-field');
        
        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        // Insert error message after the field
        field.parentNode.appendChild(errorDiv);
    },
    
    /**
     * Clears all error messages and styling
     */
    clearErrors: function() {
        // Remove error styling from all fields
        const errorFields = document.querySelectorAll('.error-field');
        errorFields.forEach(field => {
            field.classList.remove('error-field');
        });
        
        // Remove all error messages
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(message => {
            message.remove();
        });
    },
    
    /**
     * Real-time validation for registration form
     */
    setupRegistrationValidation: function() {
        const form = document.getElementById('registration-form');
        if (!form) return;
        
        // Add event listeners for real-time validation
        const fields = ['name', 'email', 'password', 'confirm_password'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => {
                    this.validateSingleField(fieldId);
                });
            }
        });
        
        // Form submission
        form.addEventListener('submit', (e) => {
            if (!this.validateRegistration()) {
                e.preventDefault();
            }
        });
    },
    
    /**
     * Real-time validation for login form
     */
    setupLoginValidation: function() {
        const form = document.getElementById('login-form');
        if (!form) return;
        
        // Add event listeners for real-time validation
        const fields = ['email', 'password'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => {
                    this.validateSingleField(fieldId);
                });
            }
        });
        
        // Form submission
        form.addEventListener('submit', (e) => {
            if (!this.validateLogin()) {
                e.preventDefault();
            }
        });
    },
    
    /**
     * Real-time validation for task form
     */
    setupTaskValidation: function() {
        const form = document.getElementById('task-form');
        if (!form) return;
        
        // Add event listeners for real-time validation
        const fields = ['task_title', 'task_description', 'task_priority', 'task_due_date'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => {
                    this.validateSingleField(fieldId);
                });
            }
        });
        
        // Form submission
        form.addEventListener('submit', (e) => {
            if (!this.validateTask()) {
                e.preventDefault();
            }
        });
    },
    
    /**
     * Validates a single field
     */
    validateSingleField: function(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        // Remove existing error for this field
        this.clearFieldError(field);
        
        // Validate based on field type
        switch (fieldId) {
            case 'name':
                if (!field.value.trim()) {
                    this.showError(field, 'Name is required');
                } else if (field.value.trim().length < 2) {
                    this.showError(field, 'Name must be at least 2 characters long');
                }
                break;
                
            case 'email':
                if (!field.value.trim()) {
                    this.showError(field, 'Email is required');
                } else if (!this.isValidEmail(field.value)) {
                    this.showError(field, 'Please enter a valid email address');
                }
                break;
                
            case 'password':
                if (!field.value) {
                    this.showError(field, 'Password is required');
                } else if (field.value.length < 6) {
                    this.showError(field, 'Password must be at least 6 characters long');
                }
                break;
                
            case 'confirm_password':
                const password = document.getElementById('password');
                if (!field.value) {
                    this.showError(field, 'Please confirm your password');
                } else if (password && password.value !== field.value) {
                    this.showError(field, 'Passwords do not match');
                }
                break;
                
            case 'task_title':
                if (!field.value.trim()) {
                    this.showError(field, 'Task title is required');
                } else if (field.value.trim().length < 3) {
                    this.showError(field, 'Task title must be at least 3 characters long');
                }
                break;
                
            case 'task_description':
                if (!field.value.trim()) {
                    this.showError(field, 'Task description is required');
                } else if (field.value.trim().length < 10) {
                    this.showError(field, 'Task description must be at least 10 characters long');
                }
                break;
                
            case 'task_priority':
                if (!field.value) {
                    this.showError(field, 'Please select a priority level');
                }
                break;
                
            case 'task_due_date':
                if (!field.value) {
                    this.showError(field, 'Due date is required');
                } else {
                    const selectedDate = new Date(field.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (selectedDate < today) {
                        this.showError(field, 'Due date cannot be in the past');
                    }
                }
                break;
        }
    },
    
    /**
     * Clears error for a specific field
     */
    clearFieldError: function(field) {
        field.classList.remove('error-field');
        const errorMessage = field.parentNode.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }
};

// Initialize validation when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Setup validation based on current page
    const currentPage = window.location.pathname;
    
    if (currentPage.includes('register.php')) {
        Validator.setupRegistrationValidation();
    } else if (currentPage.includes('login.php')) {
        Validator.setupLoginValidation();
    } else if (currentPage.includes('tasks.php')) {
        Validator.setupTaskValidation();
    }
});

// Export for use in other scripts
window.Validator = Validator; 