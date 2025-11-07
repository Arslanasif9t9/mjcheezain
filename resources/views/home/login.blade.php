<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

        * {
            box-sizing: border-box;
        }

        body {
            background: #f6f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            margin: -20px 0 50px;
        }

        h1 {
            font-weight: bold;
            margin: 0;
        }

        h2 {
            text-align: center;
        }

        p {
            font-size: 14px;
            font-weight: 100;
            line-height: 20px;
            letter-spacing: 0.5px;
            margin: 20px 0 30px;
        }

        span {
            font-size: 12px;
        }

        a {
            color: #333;
            font-size: 14px;
            text-decoration: none;
            margin: 15px 0;
        }

        button {
            border-radius: 20px;
            border: 1px solid #FF4B2B;
            background-color: #FF4B2B;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button.ghost {
            background-color: transparent;
            border-color: #FFFFFF;
        }

        button:disabled {
            background-color: #cccccc;
            border-color: #cccccc;
            cursor: not-allowed;
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        input, select {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 
                    0 10px 10px rgba(0,0,0,0.22);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container{
            transform: translateX(-100%);
        }

        .overlay {
            background: #FF416C;
            background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
            background: linear-gradient(to right, #FF4B2B, #FF416C);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .social-container {
            margin: 20px 0;
        }

        .social-container a {
            border: 1px solid #DDDDDD;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
        }

        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            display: none;
        }

        .error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        footer {
            background-color: #222;
            color: #fff;
            font-size: 14px;
            bottom: 0;
            position: fixed;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 999;
        }

        footer p {
            margin: 10px 0;
        }

        footer i {
            color: red;
        }

        footer a {
            color: #3c97bf;
            text-decoration: none;
        }
        .input-group {
            display: flex;
            width: 100%;
            gap: 10px;
        }

        .input-group input {
            flex: 1;
        }

        .input-group button {
            padding: 12px 20px;
            white-space: nowrap;
        }

        .otp-timer {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-align: left;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form id="signupForm">
                <h1>Create Account</h1>
                <div class="social-container">
                    <!-- Social buttons can be added here if needed -->
                </div>
                <select name="type" id="userTypeSign" required>
                    <option value="customer">Customer</option>
                    <option value="vendor">Vendor</option>
                </select>
                <input type="text" name="name" placeholder="Name" required/>
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email" required/>
                    <button type="button" id="getOtpBtn">Get OTP</button>
                </div>
                <div id="otpTimer" class="otp-timer"></div>
                <input type="number" name="otp" placeholder="4 digit OTP" required min="1000" max="9999"/>
                <input type="password" name="password" placeholder="Password" required minlength="6"/>
                <input type="password" name="password_confirmation" placeholder="Re-Password" required minlength="6"/>
                <div id="signupMessage" class="message"></div>
                <button type="submit" id="signupBtn">Sign Up</button>
            </form>
        </div>
                <div class="form-container sign-in-container">
            <form id="loginForm">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <input type="email" name="email" placeholder="Email" required/>
                <input type="password" name="password" placeholder="Password" required/>
                <a href="#">Forgot your password?</a>
                <div id="loginMessage" class="message"></div>
                <button type="submit" id="loginBtn">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');
        const signupForm = document.getElementById('signupForm');
        const loginForm = document.getElementById('loginForm');
        const signupBtn = document.getElementById('signupBtn');
        const loginBtn = document.getElementById('loginBtn');
        const signupMessage = document.getElementById('signupMessage');
        const loginMessage = document.getElementById('loginMessage');

        // Add these variables at the top with other declarations
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
                const response = await fetch('/api/send-otp', {
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

        // Clear all messages
        function clearMessages() {
            signupMessage.style.display = 'none';
            loginMessage.style.display = 'none';
        }

        // Show message function
        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = `message ${type}`;
            element.style.display = 'block';
        }

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
                // const result = response;
                
                if (response.ok) {
                    showMessage(signupMessage, "Account created successfully! Redirecting...", "success");
                    // Redirect to dashboard after successful signup
                    // console.log(result.type);
                    setTimeout(() => {
                        window.location.href = `/${result.type}/dashboard`;
                    //     window.location.href = '/dashboard';
                    }, 500);
                } else {
                    // Handle errors from API
                    const errorMessage = result.message || result.errors ? 
                        Object.values(result.errors).flat().join(', ') : 
                        'Signup failed. Please try again.';
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
                const response = await fetch('/api/login', {
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
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    // Handle errors from API
                    const errorMessage = result.message || 'Login failed. Please check your credentials.';
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
</body>
</html>