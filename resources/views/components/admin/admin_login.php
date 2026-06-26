<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <!-- Login Form -->
        <div id="loginFormContainer" class="w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300">
            <div class="p-8">
                <div class="text-center mb-8">
                    <i class="fas fa-lock text-4xl text-blue-600 mb-4"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Admin Panel</h1>
                    <p class="text-gray-600 mt-2">Enter your credentials to access the dashboard</p>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="hidden bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p id="errorText" class="text-sm text-red-700"></p>
                        </div>
                    </div>
                </div>

                <form id="loginForm" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="username" name="username" required autocomplete="username"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="#" id="forgotPasswordLink" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Forgot Password Form (Hidden by default) -->
        <div id="forgotPasswordContainer" class="hidden w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300">
            <div class="p-8">
                <div class="text-center mb-8">
                    <i class="fas fa-key text-4xl text-blue-600 mb-4"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
                    <p class="text-gray-600 mt-2">Enter your email to receive a reset link</p>
                </div>

                <!-- Success Message -->
                <div id="successMessage" class="hidden bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p id="successText" class="text-sm text-green-700"></p>
                        </div>
                    </div>
                </div>

                <form id="forgotPasswordForm" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            You'll receive an email with a password reset link if this email is registered.
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <a href="#" id="backToLoginLink" class="font-medium text-blue-600 hover:text-blue-500">
                                <i class="fas fa-arrow-left mr-1"></i> Back to login
                            </a>
                        </div>

                        <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Send Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const loginFormContainer = document.getElementById('loginFormContainer');
        const forgotPasswordContainer = document.getElementById('forgotPasswordContainer');
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        const backToLoginLink = document.getElementById('backToLoginLink');
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const successMessage = document.getElementById('successMessage');
        const successText = document.getElementById('successText');
        const loginForm = document.getElementById('loginForm');
        const forgotPasswordForm = document.getElementById('forgotPasswordForm');

        // Toggle between login and forgot password forms
        forgotPasswordLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginFormContainer.classList.add('hidden');
            forgotPasswordContainer.classList.remove('hidden');
            hideMessages();
        });

        backToLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            forgotPasswordContainer.classList.add('hidden');
            loginFormContainer.classList.remove('hidden');
            hideMessages();
        });

        // Hide all messages
        function hideMessages() {
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');
        }

        // Show error message
        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        // Show success message
        function showSuccess(message) {
            successText.textContent = message;
            successMessage.classList.remove('hidden');
        }

        // Login form submission
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideMessages();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                showError('Please enter both username and password');
                return;
            }
            
            // In a real implementation, you would make an API call here
            // Simulating API call with timeout
            try {
                // This would be replaced with actual fetch/axios call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // For demo purposes, we'll simulate a failed login
                // In real app, you would check the response from your API
                showError('Invalid username or password');
                
                // On successful login, you would redirect:
                // window.location.href = '/admin/dashboard';
            } catch (error) {
                showError('An error occurred during login. Please try again.');
            }
        });

        // Forgot password form submission
        forgotPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideMessages();
            
            const email = document.getElementById('email').value;
            
            if (!email) {
                showError('Please enter your email address');
                return;
            }
            
            // Validate email format
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('Please enter a valid email address');
                return;
            }
            
            // In a real implementation, you would make an API call here
            try {
                // Simulating API call with timeout
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // For security, we always show success even if email doesn't exist
                showSuccess('If this email is registered, you will receive a password reset link shortly.');
                forgotPasswordForm.reset();
                
                // In a real app, you might want to:
                // 1. Generate a secure token
                // 2. Store it in your database with expiration time
                // 3. Send email with reset link containing the token
                // 4. Log this action for security auditing
            } catch (error) {
                showError('An error occurred. Please try again later.');
            }
        });

        // Check for URL parameters (like error messages from server)
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        if (error) {
            showError(decodeURIComponent(error));
        }
    </script>
</body>
</html>