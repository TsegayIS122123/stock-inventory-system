<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | <?php echo $page_title ?? 'Dashboard'; ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="main-header">
        <div class="logo-container">
            <i class="fas fa-chart-line logo-icon"></i>
            <h1 class="brand-name">TKC-Stock</h1>
        </div>

        <div class="header-controls">
            <div class="user-profile">
                <i class="fas fa-user-circle user-icon"></i>
                <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User'); ?></span>
            </div>
            <span class="role-badge"><?php echo ucfirst($_SESSION['role'] ?? 'User'); ?></span>
        </div>
    </header>

    <div class="dashboard-container">