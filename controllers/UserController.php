<?php
// ======================================================
// User Controller - Handles authentication & user management
// ======================================================

require_once BASE_PATH . '/models/User.php';

class UserController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    // Show login page
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit();
        }
        require_once BASE_PATH . '/views/auth/login.php';
    }

    // Process login
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            return;
        }

        // Form validation (Chapter 2)
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if (empty($email)) {
            $errors[] = "Email is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            header('Location: index.php?action=login');
            return;
        }

        // Attempt login
        $this->userModel->email = $email;
        $this->userModel->password = $password;

        $user = $this->userModel->login();

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header('Location: index.php?action=dashboard');
        } else {
            $_SESSION['error'] = "Invalid email or password";
            header('Location: index.php?action=login');
        }
    }

    // Show register page
    public function showRegister()
    {
        require_once BASE_PATH . '/views/auth/register.php';
    }

    // Process registration
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=register');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');

        $errors = [];

        // Validation (Chapter 2)
        if (empty($username) || strlen($username) < 3) {
            $errors[] = "Username must be at least 3 characters";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }

        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            header('Location: index.php?action=register');
            return;
        }

        // Register user
        $this->userModel->username = $username;
        $this->userModel->email = $email;
        $this->userModel->password = $password;
        $this->userModel->full_name = $full_name;
        $this->userModel->role = 'cashier';

        if ($this->userModel->register()) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header('Location: index.php?action=login');
        } else {
            $_SESSION['error'] = "Registration failed. Email may already exist.";
            header('Location: index.php?action=register');
        }
    }

    // Logout
    public function logout()
    {
        session_unset();
        session_destroy();  // Chapter 6
        header('Location: index.php?action=login');
    }
}
