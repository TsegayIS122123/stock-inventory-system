<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TK-Stock | Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-900 via-purple-800 to-indigo-900 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-6 text-center">
                <i class="fas fa-chart-line text-white text-4xl mb-2"></i>
                <h1 class="text-2xl font-bold text-white">Create Account</h1>
                <p class="text-blue-100 text-sm">Join TKC-Stock today</p>
            </div>

            <!-- Body -->
            <div class="px-6 py-6">
                <!-- Error/Success Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $_SESSION['error'];
                                                                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form method="POST" action="index.php?action=do-register" id="registerForm">
                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Full Name</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="full_name" id="full_name" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter your full name">
                        </div>
                        <p id="nameError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Username</label>
                        <div class="relative">
                            <i class="fas fa-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="username" id="username" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Choose a username">
                        </div>
                        <p id="usernameError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Email Address</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="email" name="email" id="email" required
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="you@example.com">
                        </div>
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="mb-3">
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Minimum 6 characters">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-1">Confirm Password</label>
                        <div class="relative">
                            <i class="fas fa-check-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="confirm_password" id="confirm_password" required
                                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Confirm your password">
                            <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p id="confirmError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition duration-300">
                        <i class="fas fa-user-plus mr-2"></i> Create Account
                    </button>
                </form>

                <!-- Sign In Link -->
                <div class="text-center mt-5">
                    <p class="text-gray-600 text-sm">
                        Already have an account?
                        <a href="index.php?action=login" class="text-blue-600 font-semibold hover:text-blue-800 hover:underline">Sign In</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-3 text-center border-t">
                <p class="text-xs text-gray-500">&copy; 2025 TK-Stock. All rights reserved.</p>
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

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const password = document.getElementById('confirm_password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Client-side validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let isValid = true;

            const fullName = document.getElementById('full_name').value.trim();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Full Name validation
            if (fullName === '') {
                document.getElementById('nameError').textContent = 'Full name is required';
                document.getElementById('nameError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('nameError').classList.add('hidden');
            }

            // Username validation
            if (username === '') {
                document.getElementById('usernameError').textContent = 'Username is required';
                document.getElementById('usernameError').classList.remove('hidden');
                isValid = false;
            } else if (username.length < 3) {
                document.getElementById('usernameError').textContent = 'Username must be at least 3 characters';
                document.getElementById('usernameError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('usernameError').classList.add('hidden');
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === '') {
                document.getElementById('emailError').textContent = 'Email is required';
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Enter a valid email address';
                document.getElementById('emailError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('emailError').classList.add('hidden');
            }

            // Password validation
            if (password === '') {
                document.getElementById('passwordError').textContent = 'Password is required';
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            } else if (password.length < 6) {
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters';
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('passwordError').classList.add('hidden');
            }

            // Confirm Password validation
            if (confirmPassword === '') {
                document.getElementById('confirmError').textContent = 'Please confirm your password';
                document.getElementById('confirmError').classList.remove('hidden');
                isValid = false;
            } else if (password !== confirmPassword) {
                document.getElementById('confirmError').textContent = 'Passwords do not match';
                document.getElementById('confirmError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('confirmError').classList.add('hidden');
            }

            if (!isValid) e.preventDefault();
        });
    </script>

</body>

</html>