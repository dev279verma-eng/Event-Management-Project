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

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                setUserSession($user);
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
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
    <title>Login - Event Management System</title>
    <meta name="description" content="Login to manage your events and create memorable celebrations">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">🎉</div>
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Login to manage your events</p>
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
                    <label for="username" class="form-label">Username or Email</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input with-icon" 
                        placeholder="Enter your username or email"
                        required
                        autocomplete="username"
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    >
                    <span class="form-icon">👤</span>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input with-icon" 
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <span class="form-icon">🔒</span>
                    <span class="password-toggle" onclick="togglePassword()">👁️</span>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">
                    Sign In
                </button>
            </form>
            
            <div class="divider">
                <span>OR</span>
            </div>
            
            <div class="form-footer">
                <p>Don't have an account? <a href="register.php">Create Account</a></p>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
        
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
