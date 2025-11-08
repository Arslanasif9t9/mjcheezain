<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #FF4B2B;
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your OTP Verification Code</h2>
        <p>Hello,</p>
        <p>Use the following OTP code to complete your registration:</p>
        
        <div class="otp-code">{{ $otp }}</div>
        
        <p>This OTP will expire in 10 minutes.</p>
        <p>If you didn't request this OTP, please ignore this email.</p>
        
        <div class="footer">
            <p>Thank you,<br>Your App Team</p>
        </div>
    </div>
</body>
</html>