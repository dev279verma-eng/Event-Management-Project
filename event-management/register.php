<?php
require_once 'config/database.php';
require_once 'config/session.php';

$error = '';
$success = '';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        $conn = getDBConnection();
        
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username or email already exists';
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! Redirecting to login...';
                header('refresh:2;url=login.php');
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Management System</title>
    <meta name="description" content="Create an account to start managing your events">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">✨</div>
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Start planning amazing events today</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" method="POST" action="">
                <div class="form-group">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        class="form-input with-icon" 
                        placeholder="Enter your full name"
                        required
                        autocomplete="name"
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                    >
                    <span class="form-icon">👤</span>
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input with-icon" 
                        placeholder="Choose a username"
                        required
                        autocomplete="username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    >
                    <span class="form-icon">🏷️</span>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input with-icon" 
                        placeholder="Enter your email"
                        required
                        autocomplete="email"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                    <span class="form-icon">📧</span>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input with-icon" 
                        placeholder="Create a password (min 6 characters)"
                        required
                        autocomplete="new-password"
                    >
                    <span class="form-icon">🔒</span>
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-input with-icon" 
                        placeholder="Confirm your password"
                        required
                        autocomplete="new-password"
                    >
                    <span class="form-icon">🔐</span>
                    <span class="password-toggle" onclick="togglePassword('confirm_password')" style="top: 3.2rem;">👁️</span>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">
                    Create Account
                </button>
            </form>
            
            <div class="divider">
                <span>OR</span>
            </div>
            
            <div class="form-footer">
                <p>Already have an account? <a href="login.php">Sign In</a></p>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcons = document.querySelectorAll('.password-toggle');
            const toggleIcon = passwordInput.parentElement.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
        
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', function() {
            const strength = this.value.length;
            if (strength < 6) {
                this.style.borderColor = 'var(--danger)';
            } else if (strength < 10) {
                this.style.borderColor = 'var(--warning)';
            } else {
                this.style.borderColor = 'var(--success)';
            }
        });
        
        // Confirm password matching
        const confirmPassword = document.getElementById('confirm_password');
        confirmPassword.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.style.borderColor = 'var(--danger)';
            } else {
                this.style.borderColor = 'var(--success)';
            }
        });
        
        // Add smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach((input, index) => {
                input.style.animation = `fadeInUp 0.6s ease-out ${0.1 * index}s both`;
            });
        });
    </script>
</body>
</html>
