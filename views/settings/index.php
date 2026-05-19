<?php
$page_title = 'Settings';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
?>

<div class="page-header">
    <h2>System Settings</h2>
    <p>Configure your business preferences</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h3>Business Information</h3>
    </div>
    <div style="padding: 10px 0;">
        <p><strong>Business Name:</strong> TKC-Stock Enterprise</p>
        <p><strong>Version:</strong> 2.0.0</p>
        <p><strong>Developers:</strong> Tsegay, Chekole & Kassa</p>
        <p><strong>Course:</strong> Advanced Internet Programming - INSY3082</p>
        <p><strong>Instructor:</strong> Selamawit Desta</p>
    </div>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h3>System Information</h3>
    </div>
    <div style="padding: 10px 0;">
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>Database:</strong> MySQL</p>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>