<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password | MJCheezain</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            margin: 0;
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
            width: 100%;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        button:disabled {
            background-color: #cccccc;
            border-color: #cccccc;
            cursor: not-allowed;
        }

        .back-btn {
            background-color: transparent;
            border-color: #FF4B2B;
            color: #FF4B2B;
            margin-top: 15px;
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 40px 50px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
            width: 100%;
            max-width: 500px;
        }

        input {
            background-color: #eee;
            border: none;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
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
            width: auto;
        }

        .otp-timer {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-align: left;
            width: 100%;
        }

        .step {
            display: none;
        }

        .step.active {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            margin-bottom: 30px;
            text-align: center;
        }

        .logo h1 {
            color: #FF4B2B;
            font-size: 28px;
        }
    </style>
</head>
<body>
    {{-- <div class="logo">
        <h1>MJCheezain</h1>
    </div> --}}

    <form id="forgotPasswordForm">
        <!-- Step 1: Enter Email -->
        <div class="step active" id="step1">
            <h1>Reset Your Password</h1>
            <p>Enter your email address and we'll send you an OTP to reset your password.</p>
            
            <input type="email" name="email" id="email" placeholder="Enter your email" required/>
            
            <div id="step1Message" class="message"></div>
            
            <button type="button" id="sendOtpBtn">Send OTP</button>
            <button type="button" class="back-btn" onclick="window.location.href='/'">Back to Login</button>
        </div>

        <!-- Step 2: Enter OTP -->
        <div class="step" id="step2">
            <h1>Verify OTP</h1>
            <p>We've sent a 4-digit OTP to your email.</p>
            
            <div class="input-group">
                <input type="number" name="otp" id="otp" placeholder="Enter 4-digit OTP" required min="1000" max="9999"/>
                <button type="button" id="resendOtpBtn">Resend</button>
            </div>
            <div id="otpTimer" class="otp-timer"></div>
            
            <div id="step2Message" class="message"></div>
            
            <button type="button" id="verifyOtpBtn">Verify OTP</button>
            <button type="button" class="back-btn" id="backToStep1">Back</button>
        </div>

        <!-- Step 3: Reset Password -->
        <div class="step" id="step3">
            <h1>Create New Password</h1>
            <p>Enter your new password below.</p>
            
            <input type="password" name="password" id="password" placeholder="New Password" required minlength="6"/>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm New Password" required minlength="6"/>
            
            <div id="step3Message" class="message"></div>
            
            <button type="button" id="resetPasswordBtn">Reset Password</button>
            <button type="button" class="back-btn" id="backToStep2">Back</button>
        </div>

        <!-- Step 4: Success -->
        <div class="step" id="step4">
            <h1>Password Reset Successfully!</h1>
            <p>Your password has been reset successfully. You can now login with your new password.</p>
            
            <div id="step4Message" class="message success">
                Password reset successfully!
            </div>
            
            <button type="button" onclick="window.location.href='/'">Login Now</button>
        </div>
    </form>

    <script>
        // DOM Elements
        const steps = document.querySelectorAll('.step');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const step4 = document.getElementById('step4');
        
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const resendOtpBtn = document.getElementById('resendOtpBtn');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const resetPasswordBtn = document.getElementById('resetPasswordBtn');
        
        const emailInput = document.getElementById('email');
        const otpInput = document.getElementById('otp');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        
        const step1Message = document.getElementById('step1Message');
        const step2Message = document.getElementById('step2Message');
        const step3Message = document.getElementById('step3Message');
        
        const otpTimer = document.getElementById('otpTimer');
        const backToStep1Btn = document.getElementById('backToStep1');
        const backToStep2Btn = document.getElementById('backToStep2');

        let otpCountdown = 0;
        let countdownInterval;
        let userEmail = '';

        // Show message function
        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = `message ${type}`;
            element.style.display = 'block';
        }

        // Clear all messages
        function clearMessages() {
            step1Message.style.display = 'none';
            step2Message.style.display = 'none';
            step3Message.style.display = 'none';
        }

        // Navigate between steps
        function goToStep(stepNumber) {
            steps.forEach(step => step.classList.remove('active'));
            document.getElementById(`step${stepNumber}`).classList.add('active');
            clearMessages();
        }

        // Start OTP countdown timer
        function startOtpTimer() {
            otpCountdown = 60;
            resendOtpBtn.disabled = true;
            updateOtpTimer();
            
            countdownInterval = setInterval(() => {
                otpCountdown--;
                updateOtpTimer();
                
                if (otpCountdown <= 0) {
                    clearInterval(countdownInterval);
                    resendOtpBtn.disabled = false;
                    resendOtpBtn.textContent = 'Resend OTP';
                    otpTimer.textContent = '';
                }
            }, 1000);
        }

        // Update OTP timer display
        function updateOtpTimer() {
            otpTimer.textContent = `Resend OTP in ${otpCountdown} seconds`;
        }

        // Send OTP
        sendOtpBtn.addEventListener('click', async () => {
            const email = emailInput.value.trim();
            
            if (!email) {
                showMessage(step1Message, "Please enter your email address", "error");
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage(step1Message, "Please enter a valid email address", "error");
                return;
            }
            
            // Show loading state
            sendOtpBtn.disabled = true;
            sendOtpBtn.innerHTML = '<span class="loading"></span> Sending...';
            
            try {
                const response = await fetch('/send-password-reset-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    userEmail = email;
                    showMessage(step1Message, "OTP sent to your email successfully!", "success");
                    setTimeout(() => {
                        goToStep(2);
                        startOtpTimer();
                    }, 1000);
                } else {
                    const errorMessage = result.message || 'Failed to send OTP. Please try again.';
                    showMessage(step1Message, errorMessage, "error");
                }
            } catch (error) {
                console.error('OTP request error:', error);
                showMessage(step1Message, "Network error. Please try again.", "error");
            } finally {
                sendOtpBtn.disabled = false;
                sendOtpBtn.innerHTML = 'Send OTP';
            }
        });

        // Resend OTP
        resendOtpBtn.addEventListener('click', async () => {
            if (!userEmail) {
                showMessage(step2Message, "Email not found. Please go back and enter your email again.", "error");
                return;
            }
            
            // Show loading state
            resendOtpBtn.disabled = true;
            resendOtpBtn.innerHTML = '<span class="loading"></span> Sending...';
            
            try {
                const response = await fetch('/send-password-reset-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email: userEmail })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(step2Message, "OTP resent successfully!", "success");
                    startOtpTimer();
                } else {
                    const errorMessage = result.message || 'Failed to resend OTP. Please try again.';
                    showMessage(step2Message, errorMessage, "error");
                    resendOtpBtn.disabled = false;
                    resendOtpBtn.textContent = 'Resend OTP';
                }
            } catch (error) {
                console.error('Resend OTP error:', error);
                showMessage(step2Message, "Network error. Please try again.", "error");
                resendOtpBtn.disabled = false;
                resendOtpBtn.textContent = 'Resend OTP';
            }
        });

        // Verify OTP
        verifyOtpBtn.addEventListener('click', async () => {
            const otp = otpInput.value.trim();
            
            if (!otp || otp.length !== 4) {
                showMessage(step2Message, "Please enter a valid 4-digit OTP", "error");
                return;
            }
            
            // Show loading state
            verifyOtpBtn.disabled = true;
            verifyOtpBtn.innerHTML = '<span class="loading"></span> Verifying...';
            
            try {
                const response = await fetch('/verify-password-reset-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        email: userEmail,
                        otp: otp 
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(step2Message, "OTP verified successfully!", "success");
                    setTimeout(() => {
                        goToStep(3);
                    }, 1000);
                } else {
                    const errorMessage = result.message || 'Invalid OTP. Please try again.';
                    showMessage(step2Message, errorMessage, "error");
                }
            } catch (error) {
                console.error('Verify OTP error:', error);
                showMessage(step2Message, "Network error. Please try again.", "error");
            } finally {
                verifyOtpBtn.disabled = false;
                verifyOtpBtn.innerHTML = 'Verify OTP';
            }
        });

        // Reset Password\
        const url = window.location.href;
        let userType = '';
        if (url.includes('vendor')) {
            userType = 'vendor';
        } else if (url.includes('customer')) {
            userType = 'customer';
        }
        resetPasswordBtn.addEventListener('click', async () => {
            const password = passwordInput.value;
            const passwordConfirm = passwordConfirmInput.value;
            
            if (!password || password.length < 6) {
                showMessage(step3Message, "Password must be at least 6 characters long", "error");
                return;
            }
            
            if (password !== passwordConfirm) {
                showMessage(step3Message, "Passwords do not match", "error");
                return;
            }
            
            // Show loading state
            resetPasswordBtn.disabled = true;
            resetPasswordBtn.innerHTML = '<span class="loading"></span> Resetting...';
            
            try {
                const response = await fetch('/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        type: userType,
                        email: userEmail,
                        password: password,
                        password_confirmation: passwordConfirm
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    console.log(result);
                    showMessage(step3Message, "Password reset successfully!", "success");
                    setTimeout(() => {
                        goToStep(4);
                    }, 1000);
                } else {
                    const errorMessage = result.message || 'Failed to reset password. Please try again.';
                    showMessage(step3Message, errorMessage, "error");
                }
            } catch (error) {
                console.error('Reset password error:', error);
                showMessage(step3Message, "Network error. Please try again.", "error");
            } finally {
                resetPasswordBtn.disabled = false;
                resetPasswordBtn.innerHTML = 'Reset Password';
            }
        });

        // Back buttons
        backToStep1Btn.addEventListener('click', () => {
            goToStep(1);
        });

        backToStep2Btn.addEventListener('click', () => {
            goToStep(2);
        });

        // Allow Enter key to submit forms
        emailInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendOtpBtn.click();
            }
        });

        otpInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                verifyOtpBtn.click();
            }
        });

        passwordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                resetPasswordBtn.click();
            }
        });

        passwordConfirmInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                resetPasswordBtn.click();
            }
        });
    </script>
</body>
</html>