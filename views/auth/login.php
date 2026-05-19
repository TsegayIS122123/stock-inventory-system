<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TK-Stock | Login</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <i class="fas fa-chart-line logo-icon"></i>
                <h1 style="color:#2c3e50; margin-top:10px;">TK-Stock</h1>
                <p style="color:#7f8c8d; font-size:13px;">Smart Inventory Management System</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-box" style="background:#f8d7da; border-left-color:#e74c3c; margin-bottom:20px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error'];
                                                                unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-box" style="background:#d4edda; border-left-color:#2ecc71; margin-bottom:20px;">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success'];
                                                        unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=do-login">
                <div class="form-group">
                    <label>Email or Username</label>
                    <input type="text" name="email" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div style="margin-top: 20px; text-align: center; font-size: 12px; color: #7f8c8d;">
                <p><strong>Demo Credentials:</strong></p>
                <p>admin@tkcstock.com / password123</p>
                <p>manager@tkcstock.com / password123</p>
                <p>cashier@tkcstock.com / password123</p>
            </div>

            <div style="margin-top: 15px; text-align: center;">
                <a href="index.php?action=register" style="color:#3498db; text-decoration:none;">Create an account</a>
            </div>
        </div>
    </div>

    <footer class="main-footer" style="margin-top: auto;">
        <p>&copy; 2025 TK-Stock. All rights reserved.</p>
        <p class="version">Advanced Internet Programming - INSY3082</p>
        <p class="developers">Developed by: Tsegay, Chekole & Kassa</p>
    </footer>
</body>

</html>