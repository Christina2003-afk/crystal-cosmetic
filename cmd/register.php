<?php
// RegisterController Class
class RegisterController {
    private $user;
    private $errors = [];
    private $success = false;
    private $successMessage = '';

    public function __construct() {
        include_once 'classes/User.php';
        $this->user = new User();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->processRegistration();
        }
    }

    private function processRegistration() {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (!$this->validateInput($username, $email, $password, $confirmPassword)) {
            return;
        }

        if ($this->user->register($username, $email, $password)) {
            $this->success = true;
            $this->successMessage = "Registration successful! Redirecting to login...";
            header("refresh:2;url=login.php");
        } else {
            $this->errors[] = "Error during registration. Email may already exist!";
        }
    }

    private function validateInput($username, $email, $password, $confirmPassword) {
        // Username validation
        if (empty($username)) {
            $this->errors[] = "Username is required!";
        } elseif (strlen($username) < 3) {
            $this->errors[] = "Username must be at least 3 characters long!";
        } elseif (strlen($username) > 20) {
            $this->errors[] = "Username must not exceed 20 characters!";
        } elseif (!preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
            $this->errors[] = "Username can only contain letters, numbers, and underscores!";
        }

        // Email validation
        if (empty($email)) {
            $this->errors[] = "Email is required!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Please enter a valid email address!";
        }

        // Password validation
        if (empty($password)) {
            $this->errors[] = "Password is required!";
        } elseif (strlen($password) < 8) {
            $this->errors[] = "Password must be at least 8 characters long!";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $this->errors[] = "Password must contain at least one uppercase letter!";
        } elseif (!preg_match('/[a-z]/', $password)) {
            $this->errors[] = "Password must contain at least one lowercase letter!";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $this->errors[] = "Password must contain at least one number!";
        } elseif (!preg_match('/[!@#$%^&*]/', $password)) {
            $this->errors[] = "Password must contain at least one special character (!@#$%^&*)!";
        }

        // Confirm password validation
        if (empty($confirmPassword)) {
            $this->errors[] = "Please confirm your password!";
        } elseif ($password !== $confirmPassword) {
            $this->errors[] = "Passwords do not match!";
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function isSuccess() {
        return $this->success;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    public function hasFieldError($field) {
        foreach($this->errors as $error) {
            if (strpos(strtolower($error), strtolower($field)) !== false) {
                return true;
            }
        }
        return false;
    }
}

// RegisterView Class
class RegisterView {
    private $controller;

    public function __construct(RegisterController $controller) {
        $this->controller = $controller;
    }

    public function render() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Register - Cosmetic Shop</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    background: linear-gradient(135deg, #f5e6e8 0%, #ece2f0 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    padding: 20px;
                }

                .register-container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
                    overflow: hidden;
                    width: 100%;
                    max-width: 900px;
                }

                .register-wrapper {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    align-items: stretch;
                }

                .register-form-section {
                    padding: 50px 40px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                .register-image-section {
                    background: linear-gradient(135deg, rgba(212, 165, 212, 0.6) 0%, rgba(179, 120, 155, 0.6) 100%),
                                url('./images/cosmetic-bg.jpg') center/cover no-repeat;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                    overflow: hidden;
                    min-height: 600px;
                }

                .register-image-section::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    right: -50%;
                    width: 300px;
                    height: 300px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                }

                .register-image-section::after {
                    content: '';
                    position: absolute;
                    bottom: -30%;
                    left: -30%;
                    width: 250px;
                    height: 250px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                }

                .image-content {
                    text-align: center;
                    color: white;
                    z-index: 1;
                    position: relative;
                }

                .image-content i {
                    font-size: 80px;
                    margin-bottom: 20px;
                    opacity: 0.9;
                }

                .image-content h3 {
                    font-size: 28px;
                    font-weight: 600;
                    margin-bottom: 15px;
                }

                .image-content p {
                    font-size: 14px;
                    opacity: 0.85;
                    line-height: 1.6;
                    max-width: 250px;
                }

                .logo-section {
                    margin-bottom: 30px;
                    text-align: center;
                }

                .logo-section h1 {
                    font-size: 32px;
                    color: #333;
                    font-weight: 700;
                    letter-spacing: -0.5px;
                    margin-bottom: 5px;
                }

                .logo-section p {
                    color: #999;
                    font-size: 13px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }

                .form-group {
                    margin-bottom: 20px;
                }

                .form-group label {
                    display: block;
                    font-size: 13px;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 8px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .form-group input {
                    width: 100%;
                    padding: 12px 16px;
                    border: 2px solid #e0e0e0;
                    border-radius: 8px;
                    font-size: 14px;
                    transition: all 0.3s ease;
                    background: #f9f9f9;
                }

                .form-group input:focus {
                    outline: none;
                    border-color: #d4a5d4;
                    background: white;
                    box-shadow: 0 0 0 3px rgba(212, 165, 212, 0.1);
                }

                .form-group input.error-input {
                    border-color: #dc3545;
                    background: #fff5f5;
                }

                .form-group input.error-input:focus {
                    border-color: #dc3545;
                    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
                }

                .error-icon {
                    position: relative;
                }

                .error-icon::after {
                    content: '✕';
                    position: absolute;
                    right: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #dc3545;
                    font-size: 18px;
                    font-weight: bold;
                    pointer-events: none;
                }

                .form-group input::placeholder {
                    color: #bbb;
                }

                .password-requirements {
                    background: #f0f0f0;
                    border-left: 4px solid #d4a5d4;
                    padding: 12px 14px;
                    border-radius: 6px;
                    margin-top: 10px;
                    font-size: 12px;
                    color: #555;
                }

                .password-requirements h6 {
                    font-size: 12px;
                    font-weight: 600;
                    margin-bottom: 8px;
                    color: #333;
                }

                .requirement {
                    display: flex;
                    align-items: center;
                    margin-bottom: 4px;
                }

                .requirement i {
                    margin-right: 6px;
                    width: 16px;
                    color: #d4a5d4;
                }

                .register-btn {
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
                    color: white;
                    border: none;
                    border-radius: 8px;
                    font-size: 15px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    margin-top: 10px;
                }

                .register-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 25px rgba(212, 165, 212, 0.4);
                }

                .register-btn:active {
                    transform: translateY(0);
                }

                .alert {
                    border: none;
                    border-left: 4px solid;
                    border-radius: 6px;
                    margin-bottom: 20px;
                    padding: 14px 16px;
                    font-size: 14px;
                }

                .alert-danger {
                    background: #fff5f5;
                    color: #721c24;
                    border-color: #dc3545;
                }

                .alert-success {
                    background: #f0fdf4;
                    color: #166534;
                    border-color: #22c55e;
                }

                .alert ul {
                    margin: 0;
                    padding-left: 20px;
                }

                .alert li {
                    margin-bottom: 6px;
                }

                .alert strong {
                    display: block;
                    margin-bottom: 10px;
                }

                .form-footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 13px;
                    color: #666;
                }

                .form-footer a {
                    color: #d4a5d4;
                    text-decoration: none;
                    font-weight: 600;
                    transition: color 0.3s ease;
                }

                .form-footer a:hover {
                    color: #b3789b;
                    text-decoration: underline;
                }

                .form-divider {
                    text-align: center;
                    margin: 20px 0;
                    color: #999;
                    font-size: 13px;
                }

                @media (max-width: 768px) {
                    .register-wrapper {
                        grid-template-columns: 1fr;
                    }

                    .register-image-section {
                        display: none;
                    }

                    .register-form-section {
                        padding: 40px 30px;
                    }

                    .logo-section h1 {
                        font-size: 28px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="register-container">
                <div class="register-wrapper">
                    <!-- Form Section -->
                    <div class="register-form-section">
                        <div class="logo-section">
                            <h1>Cosmetic Shop</h1>
                            <p>Create Account</p>
                        </div>

                        <?php if($this->controller->isSuccess()): ?>
                            <div class="alert alert-success">
                                <strong><i class="fas fa-check-circle"></i> Success!</strong>
                                <?php echo htmlspecialchars($this->controller->getSuccessMessage()); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($this->controller->getErrors())): ?>
                            <div class="alert alert-danger">
                                <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                                <ul>
                                    <?php foreach($this->controller->getErrors() as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <div class="error-icon">
                                    <input 
                                        type="text" 
                                        id="username" 
                                        name="username" 
                                        class="form-control <?php echo $this->controller->hasFieldError('username') ? 'error-input' : ''; ?>" 
                                        placeholder="Choose a username (3-20 characters)"
                                        required
                                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                    >
                                </div>
                                <small style="color: #999; margin-top: 4px; display: block;">
                                    Letters, numbers, and underscores only
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <div class="error-icon">
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-control <?php echo $this->controller->hasFieldError('email') ? 'error-input' : ''; ?>" 
                                        placeholder="Enter your email"
                                        required
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="Create a strong password"
                                    required
                                >
                                <div class="password-requirements">
                                    <h6>Password must contain:</h6>
                                    <div class="requirement">
                                        <i class="fas fa-check"></i>
                                        At least 8 characters
                                    </div>
                                    <div class="requirement">
                                        <i class="fas fa-check"></i>
                                        One uppercase letter (A-Z)
                                    </div>
                                    <div class="requirement">
                                        <i class="fas fa-check"></i>
                                        One lowercase letter (a-z)
                                    </div>
                                    <div class="requirement">
                                        <i class="fas fa-check"></i>
                                        One number (0-9)
                                    </div>
                                    <div class="requirement">
                                        <i class="fas fa-check"></i>
                                        One special character (!@#$%^&*)
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input 
                                    type="password" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    class="form-control" 
                                    placeholder="Re-enter your password"
                                    required
                                >
                            </div>

                            <button type="submit" class="register-btn">Create Account</button>
                        </form>

                        <div class="form-footer">
                            Already have an account? <a href="login.php">Login here</a>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="register-image-section">
                        <div class="image-content">
                            <i class="fas fa-star"></i>
                            <h3>Join Our Community</h3>
                            <p>Get access to exclusive beauty products and special offers for our members</p>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                const usernameInput = document.getElementById('username');
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('confirm_password');

                // Username validation in real-time
                usernameInput.addEventListener('input', function() {
                    const username = this.value.trim();
                    
                    if (username.length === 0) {
                        this.classList.remove('error-input');
                    } else if (username.length < 3 || username.length > 20 || !/^[a-zA-Z0-9_]*$/.test(username)) {
                        this.classList.add('error-input');
                    } else {
                        this.classList.remove('error-input');
                    }
                });

                // Email validation in real-time
                emailInput.addEventListener('input', function() {
                    const email = this.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (email.length === 0) {
                        this.classList.remove('error-input');
                    } else if (!emailRegex.test(email)) {
                        this.classList.add('error-input');
                    } else {
                        this.classList.remove('error-input');
                    }
                });

                // Password validation in real-time
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const hasLength = password.length >= 8;
                    const hasUppercase = /[A-Z]/.test(password);
                    const hasLowercase = /[a-z]/.test(password);
                    const hasNumber = /[0-9]/.test(password);
                    const hasSpecial = /[!@#$%^&*]/.test(password);

                    const isValid = hasLength && hasUppercase && hasLowercase && hasNumber && hasSpecial;

                    if (password.length === 0) {
                        this.classList.remove('error-input');
                    } else if (!isValid) {
                        this.classList.add('error-input');
                    } else {
                        this.classList.remove('error-input');
                    }

                    // Update password requirements visual feedback
                    updatePasswordRequirements(hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial);
                });

                // Confirm password validation in real-time
                confirmPasswordInput.addEventListener('input', function() {
                    if (this.value.length === 0) {
                        this.classList.remove('error-input');
                    } else if (this.value !== passwordInput.value) {
                        this.classList.add('error-input');
                    } else {
                        this.classList.remove('error-input');
                    }
                });

                // Also check confirm password when password changes
                passwordInput.addEventListener('input', function() {
                    if (confirmPasswordInput.value.length > 0) {
                        if (confirmPasswordInput.value !== this.value) {
                            confirmPasswordInput.classList.add('error-input');
                        } else {
                            confirmPasswordInput.classList.remove('error-input');
                        }
                    }
                });

                function updatePasswordRequirements(hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial) {
                    const requirements = document.querySelectorAll('.requirement');
                    requirements[0].style.opacity = hasLength ? '1' : '0.5';
                    requirements[0].style.color = hasLength ? '#22c55e' : '#555';
                    requirements[1].style.opacity = hasUppercase ? '1' : '0.5';
                    requirements[1].style.color = hasUppercase ? '#22c55e' : '#555';
                    requirements[2].style.opacity = hasLowercase ? '1' : '0.5';
                    requirements[2].style.color = hasLowercase ? '#22c55e' : '#555';
                    requirements[3].style.opacity = hasNumber ? '1' : '0.5';
                    requirements[3].style.color = hasNumber ? '#22c55e' : '#555';
                    requirements[4].style.opacity = hasSpecial ? '1' : '0.5';
                    requirements[4].style.color = hasSpecial ? '#22c55e' : '#555';
                }
            </script>
        </body>
        </html>
        <?php
    }
}

// Main execution
$controller = new RegisterController();
$controller->handleRequest();
$view = new RegisterView($controller);
$view->render();
?>