<?php
session_start();

// LoginController Class
class LoginController {
    private $user;
    private $error = null;
    private $success = false;

    public function __construct() {
        include_once 'classes/User.php';
        $this->user = new User();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->processLogin();
        }
    }

    private function processLogin() {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (!$this->validateInput($email, $password)) {
            return;
        }

        $loginUser = $this->user->login($email, $password);

        if ($loginUser) {
            $_SESSION['username'] = $loginUser['username'];
            $_SESSION['user_id'] = $loginUser['id'];
            $this->success = true;
            header("Location: index.php");
            exit;
        } else {
            $this->error = "Invalid email or password!";
        }
    }

    private function validateInput($email, $password) {
        if (empty($email)) {
            $this->error = "Email is required!";
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error = "Please enter a valid email address!";
            return false;
        }

        if (empty($password)) {
            $this->error = "Password is required!";
            return false;
        }

        if (strlen($password) < 6) {
            $this->error = "Password must be at least 6 characters!";
            return false;
        }

        return true;
    }

    public function getError() {
        return $this->error;
    }

    public function isSuccess() {
        return $this->success;
    }
}

// View Class
class LoginView {
    private $controller;

    public function __construct(LoginController $controller) {
        $this->controller = $controller;
    }

    public function render() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login - Cosmetic Shop</title>
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
                }

                .login-container {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
                    overflow: hidden;
                    width: 100%;
                    max-width: 900px;
                }

                .login-wrapper {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    align-items: stretch;
                    min-height: 500px;
                }

                .login-form-section {
                    padding: 50px 40px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                .login-image-section {
                    background: linear-gradient(135deg, #d4a5d4 0%, #b3789b 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                    overflow: hidden;
                }

                .login-image-section::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    right: -50%;
                    width: 300px;
                    height: 300px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                }

                .login-image-section::after {
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
                }

                .logo-section {
                    margin-bottom: 40px;
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
                    margin-bottom: 25px;
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

                .form-group input::placeholder {
                    color: #bbb;
                }

                .login-btn {
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

                .login-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 25px rgba(212, 165, 212, 0.4);
                }

                .login-btn:active {
                    transform: translateY(0);
                }

                .alert {
                    border: none;
                    border-left: 4px solid #dc3545;
                    border-radius: 6px;
                    margin-bottom: 25px;
                    padding: 14px 16px;
                    font-size: 14px;
                }

                .alert-danger {
                    background: #fff5f5;
                    color: #721c24;
                }

                .form-footer {
                    text-align: center;
                    margin-top: 25px;
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

                @media (max-width: 768px) {
                    .login-wrapper {
                        grid-template-columns: 1fr;
                    }

                    .login-image-section {
                        display: none;
                    }

                    .login-form-section {
                        padding: 40px 30px;
                        min-height: auto;
                    }

                    .logo-section h1 {
                        font-size: 28px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <div class="login-wrapper">
                    <!-- Form Section -->
                    <div class="login-form-section">
                        <div class="logo-section">
                            <h1>Cosmetic Shop</h1>
                            <p>Welcome Back</p>
                        </div>

                        <?php if($this->controller->getError()): ?>
                            <div class="alert alert-danger">
                                <strong>Error!</strong> <?php echo htmlspecialchars($this->controller->getError()); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-control" 
                                    placeholder="Enter your email"
                                    required
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                >
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="Enter your password"
                                    required
                                >
                            </div>

                            <button type="submit" class="login-btn">Login</button>
                        </form>

                        <div class="form-footer">
                            Don't have an account? <a href="register.php">Sign up here</a>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="login-image-section">
                        <div class="image-content">
                            <i class="fas fa-spa"></i>
                            <h3>Beauty & Care</h3>
                            <p>Discover our exclusive collection of premium cosmetic products</p>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
}

// Main execution
$controller = new LoginController();
$controller->handleRequest();
$view = new LoginView($controller);
$view->render();
?>