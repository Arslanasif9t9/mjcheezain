<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container bg-white rounded-lg shadow-xl overflow-hidden relative w-full max-w-4xl min-h-[600px]">
        <!-- Sign Up Form -->
        <div class="form-container sign-up-container absolute top-0 h-full w-1/2 opacity-0 z-10 transition-all duration-600 ease-in-out left-0 translate-x-full">
            <form id="signupForm" class="bg-white flex items-center justify-center flex-col px-12 h-full text-center">
                <h1 class="font-bold text-2xl mb-4">Create Account</h1>
                <input id="userTypeSign" type="hidden" name="type" value="">
                <input type="text" name="name" placeholder="Name" class="bg-gray-100 border-none py-3 px-4 my-2 w-full rounded-lg" required/>
                
                <div class="relative w-full my-2">
                    <input type="email" name="email" id="email" placeholder="Email" class="bg-gray-100 border-none py-3 px-4 w-full rounded-lg pr-24" required/>
                    <button type="button" id="getOtpBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white border-none py-2 px-4 rounded-lg text-xs cursor-pointer">Get OTP</button>
                </div>
                
                <div id="otpTimer" class="otp-timer text-sm text-red-500 my-2"></div>
                <input type="number" name="otp" placeholder="4 digit OTP" class="bg-gray-100 border-none py-3 px-4 my-2 w-full rounded-lg" required min="1000" max="9999"/>
                
                <div class="relative w-full my-2">
                    <input type="password" name="password" id="signupPassword" placeholder="Password" class="bg-gray-100 border-none py-3 px-4 w-full rounded-lg pr-10" required minlength="6"/>
                    <button type="button" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 bg-transparent border-none cursor-pointer">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                
                <div class="relative w-full my-2">
                    <input type="password" name="password_confirmation" id="signupConfirmPassword" placeholder="Re-Password" class="bg-gray-100 border-none py-3 px-4 w-full rounded-lg pr-10" required minlength="6"/>
                    <button type="button" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 bg-transparent border-none cursor-pointer">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                
                <div id="signupMessage" class="message my-2 text-sm w-full"></div>
                <button type="submit" id="signupBtn" class="bg-blue-500 text-white border-none rounded-lg py-3 px-12 font-semibold text-sm tracking-wider uppercase transition-transform duration-80 ease-in mt-4">Sign Up</button>
                
                <p class="text-sm text-gray-600 mt-6 md:hidden">
                    Already have an account? 
                    <button type="button" id="mobileSignIn" class="text-blue-500 font-semibold hover:underline focus:outline-none ml-1">Sign In</button>
                </p>
            </form>
        </div>

        <!-- Sign In Form -->
        <div class="form-container sign-in-container absolute top-0 h-full w-1/2 z-20 transition-all duration-600 ease-in-out left-0">
            <form id="loginForm" class="bg-white flex items-center justify-center flex-col px-12 h-full text-center">
                <h1 class="font-bold text-2xl mb-4">Sign in</h1>
                <input id="userTypeLog" type="hidden" name="type" value="">
                <input type="email" name="id" placeholder="Email" class="bg-gray-100 border-none py-3 px-4 my-2 w-full rounded-lg" required/>
                
                <div class="relative w-full my-2">
                    <input type="password" name="password" id="loginPassword" placeholder="Password" class="bg-gray-100 border-none py-3 px-4 w-full rounded-lg pr-10" required/>
                    <button type="button" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 bg-transparent border-none cursor-pointer">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                
                <a id="forgot" href="#" class="text-gray-500 text-sm my-2 no-underline">Forgot your password?</a>
                <div id="loginMessage" class="message my-2 text-sm w-full"></div>
                <button type="submit" id="loginBtn" class="bg-blue-500 text-white border-none rounded-lg py-3 px-12 font-semibold text-sm tracking-wider uppercase transition-transform duration-80 ease-in mt-4">Sign In</button>
                
                <p class="text-sm text-gray-600 mt-6 md:hidden">
                    Don't have an account? 
                    <button type="button" id="mobileSignUp" class="text-blue-500 font-semibold hover:underline focus:outline-none ml-1">Sign Up</button>
                </p>
            </form>
        </div>

        <!-- Overlay Container -->
        <div class="overlay-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-transform duration-600 ease-in-out z-30">
            <div class="overlay relative -left-full h-full w-[200%] bg-gradient-to-r from-blue-400 to-purple-500 transition-transform duration-600 ease-in-out text-white">
                <div class="overlay-panel overlay-left absolute top-0 h-full w-1/2 flex items-center justify-center flex-col px-12 text-center transition-transform duration-600 ease-in-out -translate-x-20">
                    <h1 class="text-3xl font-bold mb-4">Welcome Back!</h1>
                    <p class="text-sm mb-4">To keep connected with us please login with your personal info</p>
                    <button class="ghost bg-transparent border border-white text-white py-3 px-12 font-semibold text-sm tracking-wider uppercase rounded-lg transition-transform duration-80 ease-in mt-4" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right absolute top-0 right-0 h-full w-1/2 flex items-center justify-center flex-col px-12 text-center transition-transform duration-600 ease-in-out translate-x-0">
                    <h1 class="text-3xl font-bold mb-4">Hello, Friend!</h1>
                    <p class="text-sm mb-4">Enter your personal details and start journey with us</p>
                    <button class="ghost bg-transparent border border-white text-white py-3 px-12 font-semibold text-sm tracking-wider uppercase rounded-lg transition-transform duration-80 ease-in mt-4" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to handle tab & question opening based on URL
        function handleURLTabs() {
            const params = new URLSearchParams(window.location.search);
            const type = params.get('type');

            switch(type) {
                case "customer-signup": 
                    document.getElementById('userTypeSign').value = "customer";
                    document.getElementById('userTypeLog').value = "customer";
                    document.getElementById('forgot').href = "/customer-forgot-password";
                    break;
                case "vendor-signup": 
                    document.getElementById('userTypeSign').value = "vendor";
                    document.getElementById('userTypeLog').value = "vendor";
                    document.getElementById('forgot').href = "/vendor-forgot-password";
                    break;
                case "customer-login": 
                    document.getElementById('userTypeSign').value = "customer";
                    document.getElementById('userTypeLog').value = "customer";
                    document.getElementById('forgot').href = "/customer-forgot-password";
                    break;
                case "vendor-login": 
                    document.getElementById('userTypeSign').value = "vendor";
                    document.getElementById('userTypeLog').value = "vendor";
                    document.getElementById('forgot').href = "/vendor-forgot-password";
                    break;
            }        
        }

        // Initialize on page load
        window.onload = function() {
            handleURLTabs();
        };

        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.querySelector('.container');
        const signupForm = document.getElementById('signupForm');
        const loginForm = document.getElementById('loginForm');
        const signupBtn = document.getElementById('signupBtn');
        const loginBtn = document.getElementById('loginBtn');
        const signupMessage = document.getElementById('signupMessage');
        const loginMessage = document.getElementById('loginMessage');

        // OTP functionality
        const getOtpBtn = document.getElementById('getOtpBtn');
        const emailInput = document.getElementById('email');
        const otpTimer = document.getElementById('otpTimer');

        let otpCountdown = 0;
        let countdownInterval;

        // Add these functions
        function startOtpTimer() {
            otpCountdown = 60; // 60 seconds
            getOtpBtn.disabled = true;
            updateOtpTimer();
            
            countdownInterval = setInterval(() => {
                otpCountdown--;
                updateOtpTimer();
                
                if (otpCountdown <= 0) {
                    clearInterval(countdownInterval);
                    getOtpBtn.disabled = false;
                    getOtpBtn.textContent = 'Get OTP';
                    otpTimer.textContent = '';
                }
            }, 1000);
        }

        function updateOtpTimer() {
            otpTimer.textContent = `Resend OTP in ${otpCountdown} seconds`;
        }
        function getQueryParam(param) {
            return new URLSearchParams(window.location.search).get(param);
        }
        const page = getQueryParam('page');

        // Add OTP button event listener
        getOtpBtn.addEventListener('click', async () => {
            const email = emailInput.value.trim();
            
            if (!email) {
                showMessage(signupMessage, "Please enter your email first", "error");
                return;
            }
            
            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage(signupMessage, "Please enter a valid email address", "error");
                return;
            }
            
            // Show loading state
            getOtpBtn.innerHTML = '<span class="loading"></span> Sending...';
            getOtpBtn.disabled = true;
            
            try {
                // Send request to Laravel backend to generate and send OTP
                const response = await fetch('/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(signupMessage, "OTP sent to your email successfully!", "success");
                    getOtpBtn.innerHTML = '<span class=""></span> Resend';
                    startOtpTimer();
                } else {
                    const errorMessage = result.message || 'Failed to send OTP. Please try again.';
                    showMessage(signupMessage, errorMessage, "error");
                    getOtpBtn.disabled = false;
                    getOtpBtn.textContent = 'Get OTP';
                }
            } catch (error) {
                console.error('OTP request error:', error);
                showMessage(signupMessage, "Network error. Please try again.", "error");
                getOtpBtn.disabled = false;
                getOtpBtn.textContent = 'Get OTP';
            }
        });

        // Toggle between sign in and sign up forms
        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
            clearMessages();
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
            clearMessages();
        });

        // Mobile toggle buttons
        const mobileSignUp = document.getElementById('mobileSignUp');
        const mobileSignIn = document.getElementById('mobileSignIn');
        
        if (mobileSignUp) {
            mobileSignUp.addEventListener('click', () => {
                container.classList.add("right-panel-active");
                clearMessages();
            });
        }
        
        if (mobileSignIn) {
            mobileSignIn.addEventListener('click', () => {
                container.classList.remove("right-panel-active");
                clearMessages();
            });
        }

        // Clear all messages
        function clearMessages() {
            signupMessage.style.display = 'none';
            loginMessage.style.display = 'none';
        }

        // Show message function
        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = `message my-2 text-sm w-full ${type === 'error' ? 'text-red-500' : 'text-green-500'}`;
            element.style.display = 'block';
        }

        // Password visibility toggle
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Handle signup form submission
        signupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(signupForm);
            const data = Object.fromEntries(formData);
            
            // Validate passwords match
            if (data.password !== data.password_confirmation) {
                showMessage(signupMessage, "Passwords do not match", "error");
                return;
            }
            // Validate OTP is 4 digits
            if (!data.otp || data.otp.length !== 4) {
                showMessage(signupMessage, "Please enter a valid 4-digit OTP", "error");
                return;
            }
            
            // Show loading state
            signupBtn.disabled = true;
            signupBtn.innerHTML = '<span class="loading"></span> Signing Up...';
            
            try {
                // Replace with your actual signup API endpoint
                const response = await fetch('/signup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(signupMessage, "Account created successfully! Redirecting...", "success");
                    // Redirect to dashboard after successful signup
                    setTimeout(() => {
                        window.location.href = `/${result.type}/dashboard`;
                    }, 500);
                } else {
                    // Handle errors from API safely
                    let errorMessage = 'Signup failed. Please try again.';
                    if (result) {
                        if (result.message) {
                            errorMessage = result.message;
                        } else if (result.errors && typeof result.errors === 'object') {
                            errorMessage = Object.values(result.errors).flat().join(', ');
                        }
                    }
                    showMessage(signupMessage, errorMessage, "error");
                }
            } catch (error) {
                console.error('Signup error:', error);
                showMessage(signupMessage, "Network error. Please try again.", "error");
            } finally {
                // Reset button state
                signupBtn.disabled = false;
                signupBtn.innerHTML = 'Sign Up';
            }
        });

        
        // Handle login form submission
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData);
            
            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<span class="loading"></span> Signing In...';
            
            try {
                // Replace with your actual login API endpoint
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(loginMessage, "Login successful! Redirecting...", "success");
                    // Store token if provided
                    if (result.token) {
                        localStorage.setItem('authToken', result.token);
                    }
                    // Redirect to dashboard after successful login
                    setTimeout(() => {
                        if (page === '/' || page === '%2F') {
                            window.location.href = `/${result.type}/dashboard`;
                        } else {
                            window.location.href = decodeURIComponent(page);
                        }
                    }, 500);
                } else {
                    // Handle errors from API
                    const errorMessage = result.message || 'Login failed. Please check your information.';
                    showMessage(loginMessage, errorMessage, "error");
                }
            } catch (error) {
                console.error('Login error:', error);
                showMessage(loginMessage, "Network error. Please try again.", "error");
            } finally {
                // Reset button state
                loginBtn.disabled = false;
                loginBtn.innerHTML = 'Sign In';
            }
        });
    </script>

    <style>
        /* Desktop styles (width >= 768px) */
        @media (min-width: 768px) {
            .right-panel-active .sign-in-container {
                transform: translateX(100%);
            }

            .right-panel-active .sign-up-container {
                transform: translateX(100%);
                opacity: 1;
                z-index: 25;
            }

            .right-panel-active .overlay-container {
                transform: translateX(-100%);
            }

            .right-panel-active .overlay {
                transform: translateX(50%);
            }

            .right-panel-active .overlay-left {
                transform: translateX(0);
            }

            .right-panel-active .overlay-right {
                transform: translateX(20%);
            }
        }

        /* Mobile styles (width < 768px) */
        @media (max-width: 767px) {
            .container {
                min-height: auto !important;
                height: auto !important;
                padding: 1.5rem 0 !important;
                margin: 1.5rem !important;
                max-width: 100% !important;
                width: 92% !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
            }

            .form-container {
                position: relative !important;
                width: 100% !important;
                height: auto !important;
                opacity: 1 !important;
                z-index: 10 !important;
                left: 0 !important;
                transform: none !important;
                transition: none !important;
            }

            /* Hide inactive form on mobile */
            .sign-up-container {
                display: none !important;
            }

            .right-panel-active .sign-in-container {
                display: none !important;
            }

            .right-panel-active .sign-up-container {
                display: block !important;
                opacity: 1 !important;
                z-index: 20 !important;
            }

            .overlay-container {
                display: none !important;
            }

            form {
                padding: 1.5rem 2rem !important;
                height: auto !important;
            }
        }

        /* Global style helpers */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</body>
</html>