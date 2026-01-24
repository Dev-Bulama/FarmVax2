<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to FarmVax</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #11455B 0%, #2FCB6E 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .welcome-message {
            font-size: 18px;
            color: #11455B;
            margin-bottom: 20px;
        }
        .credentials-box {
            background: #f8f9fa;
            border-left: 4px solid #2FCB6E;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credential-item {
            margin: 10px 0;
        }
        .credential-label {
            font-weight: bold;
            color: #11455B;
            display: inline-block;
            width: 100px;
        }
        .credential-value {
            color: #333;
            font-family: 'Courier New', monospace;
            background: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        .password-value {
            font-size: 16px;
            font-weight: bold;
            color: #e74c3c;
        }
        .cta-button {
            display: inline-block;
            background: #2FCB6E;
            color: #ffffff;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background: #25a356;
        }
        .security-notice {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-notice strong {
            color: #856404;
        }
        .features-list {
            margin: 20px 0;
        }
        .features-list li {
            margin: 10px 0;
            padding-left: 25px;
            position: relative;
        }
        .features-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #2FCB6E;
            font-weight: bold;
            font-size: 18px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            color: #11455B;
            text-decoration: none;
            margin: 0 10px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            .email-header h1 {
                font-size: 24px;
            }
            .credential-label {
                display: block;
                margin-bottom: 5px;
            }
            .credential-value {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🌾 Welcome to FarmVax!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="welcome-message">Hello <strong>{{ $user->name }}</strong>,</p>

            <p>Welcome to <strong>FarmVax</strong> - your comprehensive livestock health and outbreak alert system!</p>

            <p>Your account has been successfully created. Below are your login credentials:</p>

            <!-- Credentials Box -->
            <div class="credentials-box">
                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value password-value">{{ $password }}</span>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <strong>🔒 Important Security Notice:</strong>
                <p style="margin: 10px 0 0 0;">Please change your password immediately after your first login for security purposes. Keep your password confidential and do not share it with anyone.</p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="cta-button">Login to Your Account</a>
            </div>

            <!-- What You Can Do -->
            <h3 style="color: #11455B; margin-top: 30px;">What You Can Do with FarmVax:</h3>
            <ul class="features-list">
                <li>Track your livestock health and vaccination records</li>
                <li>Receive real-time outbreak alerts in your area</li>
                <li>Connect with veterinary professionals</li>
                <li>Access disease prevention resources and guides</li>
                <li>Monitor your farm's health statistics</li>
                <li>Get vaccination reminders for your animals</li>
            </ul>

            <!-- Getting Started -->
            <h3 style="color: #11455B;">Getting Started:</h3>
            <p>After logging in, we recommend you:</p>
            <ol>
                <li>Complete your profile information</li>
                <li>Add your location details for targeted alerts</li>
                <li>Register your livestock</li>
                <li>Set up your notification preferences</li>
            </ol>

            <!-- Support -->
            <p style="margin-top: 30px;">If you have any questions or need assistance, our support team is here to help!</p>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>The FarmVax Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>FarmVax</strong> - Protecting Livestock, Securing Livelihoods</p>
            
            <div class="social-links">
                <a href="https://www.youtube.com/c/FarmAlert">YouTube</a> |
                <a href="mailto:support@farmvax.com">Contact Support</a>
            </div>

            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                This is an automated email. Please do not reply to this message.<br>
                If you did not expect this email, please contact our support team immediately.
            </p>

            <p style="font-size: 12px; color: #999; margin-top: 10px;">
                &copy; {{ date('Y') }} FarmVax. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>