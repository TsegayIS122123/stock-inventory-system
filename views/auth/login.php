<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKC-Stock | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-white-900 via-purple-800 to-indigo-900 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <i class="fas fa-chart-line text-white text-5xl mb-3"></i>
                <h1 class="text-3xl font-bold text-white">TKC-Stock</h1>
                <p class="text-blue-100 text-sm mt-1">Smart Inventory Management System</p>
            </div>

            <!-- Body -->
            <div class="px-6 py-8">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Welcome to TKC-Stock</h2>

                <!-- Error/Success Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $_SESSION['error'];
                                                                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm rounded">
                        <i class="fas fa-check-circle mr-2"></i> <?php echo $_SESSION['success'];
                                                                    unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="index.php?action=do-login" id="loginForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Email or Username</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="email" id="email" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your email or username">
                        </div>
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your password">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Forgot Password?</a>
                    </div> -->

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Don't have an account?
                        <a href="index.php?action=register" class="text-blue-600 font-semibold hover:text-blue-800 hover:underline">Sign Up</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 text-center border-t">
                <p class="text-xs text-gray-500">&copy; 2025 TKC-Stock. All rights reserved.</p>
                <p class="text-xs text-gray-400 mt-1">Advanced Internet Programming - INSY3082</p>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Client-side validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let isValid = true;
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (email === '') {
                document.getElementById('emailError').textContent = 'Email or username is required';
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('emailError').classList.add('hidden');
            }

            if (password === '') {
                document.getElementById('passwordError').textContent = 'Password is required';
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('passwordError').classList.add('hidden');
            }

            if (!isValid) e.preventDefault();
        });
    </script>

</body>

</html>