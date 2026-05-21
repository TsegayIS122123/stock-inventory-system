<?php
$page_title = 'Settings';
require_once BASE_PATH . '/views/layouts/header.php';
require_once BASE_PATH . '/views/layouts/sidebar.php';
// Get database connection for system info
$db = getDB();
?>

<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Settings</h1>
        <p class="text-gray-500 text-sm">Configure your business preferences and system information</p>
    </div>

    <!-- Settings Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <div class="flex flex-wrap gap-1">
            <button onclick="showSettingsTab('business')" id="tab-business" class="settings-tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 bg-blue-600 text-white">
                <i class="fas fa-building mr-2"></i> Business
            </button>
            <button onclick="showSettingsTab('system')" id="tab-system" class="settings-tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 text-gray-600 hover:text-blue-600 hover:bg-gray-100">
                <i class="fas fa-server mr-2"></i> System
            </button>
            <button onclick="showSettingsTab('about')" id="tab-about" class="settings-tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 text-gray-600 hover:text-blue-600 hover:bg-gray-100">
                <i class="fas fa-info-circle mr-2"></i> About
            </button>
            <button onclick="showSettingsTab('team')" id="tab-team" class="settings-tab-btn px-4 py-2 text-sm font-medium rounded-t-lg transition-all duration-200 text-gray-600 hover:text-blue-600 hover:bg-gray-100">
                <i class="fas fa-users mr-2"></i> Team
            </button>
        </div>
    </div>

    <!-- Tab Content: Business Information -->
    <div id="content-business" class="settings-tab-panel">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800"><i class="fas fa-building text-blue-500 mr-2"></i> Business Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                        <p class="text-gray-900 font-semibold">TKC-Stock Enterprise</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                        <p class="text-gray-900">Retail & Inventory Management</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <p class="text-gray-900">+251 900 000 000</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <p class="text-gray-900">contact@tkcstock.com</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <p class="text-gray-900">Addis Ababa, Ethiopia</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800"><i class="fas fa-receipt text-blue-500 mr-2"></i> Tax & Invoice Settings</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate</label>
                        <p class="text-gray-900">0% (Not applied)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Prefix</label>
                        <p class="text-gray-900">INV</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <p class="text-gray-900">Ethiopian Birr (ETB) - ብር</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Format</label>
                        <p class="text-gray-900">DD/MM/YYYY</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: System Information -->
    <div id="content-system" class="settings-tab-panel hidden">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800"><i class="fas fa-server text-blue-500 mr-2"></i> System Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PHP Version</label>
                        <p class="text-gray-900 font-mono"><?php echo phpversion(); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">MySQL Version</label>
                        <p class="text-gray-900 font-mono">
                            <?php
                            $result = $db->query("SELECT VERSION() as version");
                            $version = $result->fetch_assoc();
                            echo $version['version'];
                            ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Server Time</label>
                        <p class="text-gray-900 font-mono" id="serverTime"><?php echo date('Y-m-d H:i:s'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Zone</label>
                        <p class="text-gray-900">Africa/Addis Ababa (GMT+3)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Application Version</label>
                        <p class="text-gray-900"><?php echo APP_VERSION; ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                        <p class="text-gray-900">tkc_stock</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800"><i class="fas fa-database text-blue-500 mr-2"></i> Database Status</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    <?php
                    $tables = ['users', 'categories', 'products', 'sales', 'sale_items', 'stock_logs'];
                    foreach ($tables as $table):
                        $check = $db->query("SHOW TABLES LIKE '$table'");
                        $exists = $check && $check->num_rows > 0;
                    ?>
                        <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-table <?php echo $exists ? 'text-green-500' : 'text-red-500'; ?>"></i>
                            <span class="text-sm text-gray-700"><?php echo $table; ?></span>
                            <?php if ($exists): ?>
                                <span class="ml-auto text-xs text-green-500">✓</span>
                            <?php else: ?>
                                <span class="ml-auto text-xs text-red-500">✗</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: About System -->
    <div id="content-about" class="settings-tab-panel hidden">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800"><i class="fas fa-info-circle text-blue-500 mr-2"></i> About TKC-Stock</h2>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-white text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">TKC-Stock</h3>
                    <p class="text-gray-500 text-sm">Smart Inventory & Sales Management System</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2"><i class="fas fa-code-branch text-blue-500 mr-2"></i> Version Information</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Version:</span><span class="font-semibold"><?php echo APP_VERSION; ?></span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Release Date:</span><span>May 2026</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Build Number:</span><span>#INSY3082-FP</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">License:</span><span>Educational Use</span></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2"><i class="fas fa-laptop-code text-blue-500 mr-2"></i> Technology Stack</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">PHP 8.x</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">MySQL 8.0</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">Tailwind CSS</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">jQuery</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">Chart.js</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">MVC Architecture</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-5">
                    <h4 class="font-semibold text-gray-800 mb-2"><i class="fas fa-graduation-cap text-blue-500 mr-2"></i> Educational Purpose</h4>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        This project is developed as the final project for the <strong>Advanced Internet Programming (INSY3082)</strong> course
                        to demonstrate comprehensive web development skills including MVC architecture, database design, authentication,
                        CRUD operations, and responsive UI/UX design.
                    </p>
                </div>

                <div class="mt-6 bg-gray-50 rounded-lg p-5">
                    <h4 class="font-semibold text-gray-800 mb-3"><i class="fas fa-check-circle text-green-500 mr-2"></i> Features Included</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Product CRUD</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Stock Management</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> POS / Sales</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Invoice Printing</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Dashboard Charts</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Sales Reports</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Category Management</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Role-Based Access</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check text-green-500 text-xs"></i> Low Stock Alerts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Team -->
    <div id="content-team" class="settings-tab-panel hidden">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="font-semibold text-gray-800"><i class="fas fa-users text-blue-500 mr-2"></i> Development Team</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg hover:shadow-md transition">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white text-2xl font-bold">T</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Tsegay Assefa</h3>
                        <p class="text-sm text-blue-600">Full Stack Developer</p>
                        <p class="text-xs text-gray-500 mt-2">Lead Developer, Backend & Database</p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg hover:shadow-md transition">
                        <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white text-2xl font-bold">C</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Chekole Ngusalem</h3>
                        <p class="text-sm text-green-600">Backend Developer</p>
                        <p class="text-xs text-gray-500 mt-2">Database Design, Sales Module</p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg hover:shadow-md transition">
                        <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-white text-2xl font-bold">K</span>
                        </div>
                        <h3 class="font-semibold text-gray-800">Kassa Gebrekidan</h3>
                        <p class="text-sm text-purple-600">Frontend Developer</p>
                        <p class="text-xs text-gray-500 mt-2">UI/UX, Reports & Charts</p>
                    </div>
                </div>

                <div class="mt-8 text-center border-t pt-6">
                    <p class="text-gray-600"><i class="fas fa-graduation-cap mr-2 text-blue-500"></i> Advanced Internet Programming - INSY3082</p>
                    <p class="text-gray-500 text-sm mt-1">Instructor: Selamawit Desta</p>
                    <p class="text-gray-400 text-xs mt-3">© 2026 TKC-Stock. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSettingsTab(tabName) {
        // Hide all tab panels
        document.querySelectorAll('.settings-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });

        // Show selected panel
        const selectedPanel = document.getElementById('content-' + tabName);
        if (selectedPanel) {
            selectedPanel.classList.remove('hidden');
        }

        // Update button styles
        document.querySelectorAll('.settings-tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('text-gray-600', 'hover:text-blue-600', 'hover:bg-gray-100');
        });

        const activeBtn = document.getElementById('tab-' + tabName);
        if (activeBtn) {
            activeBtn.classList.remove('text-gray-600', 'hover:text-blue-600', 'hover:bg-gray-100');
            activeBtn.classList.add('bg-blue-600', 'text-white');
        }
    }

    // Update server time every second if on system tab
    function updateServerTime() {
        const timeElement = document.getElementById('serverTime');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toISOString().slice(0, 19).replace('T', ' ');
        }
    }
    setInterval(updateServerTime, 1000);

    // Also update when system tab becomes visible
    const observer = new MutationObserver(function() {
        if (!document.getElementById('content-system').classList.contains('hidden')) {
            updateServerTime();
        }
    });
    observer.observe(document.getElementById('content-system'), {
        attributes: true,
        attributeFilter: ['class']
    });
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>