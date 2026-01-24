<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .success-box {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">✅ Password Changed Successfully</h1>
        <p style="margin: 10px 0 0 0;">FarmVax</p>
    </div>

    <div class="content">
        <h2>Hello {{ $user->name }},</h2>
        
        <div class="success-box">
            <strong>✓ Your password has been changed successfully!</strong>
            <p style="margin: 10px 0 0 0;">You can now login to your FarmVax account with your new password.</p>
        </div>

        <p>This email confirms that your password was changed on:</p>
        <p style="background: white; padding: 10px; border-radius: 5px; font-weight: bold;">
            📅 {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
        </div>

        <div class="warning-box">
            <strong>⚠️ Didn't make this change?</strong>
            <p style="margin: 10px 0 0 0;">
                If you did not change your password, please contact our support team immediately at 
                <strong>support@farmvax.com</strong>
            </p>
        </div>

        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">

        <h3>Security Tips:</h3>
        <ul style="color: #6b7280; font-size: 14px;">
            <li>Never share your password with anyone</li>
            <li>Use a unique password for FarmVax</li>
            <li>Change your password regularly</li>
            <li>Enable two-factor authentication if available</li>
        </ul>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} FarmVax. All rights reserved.</p>
        <p>Need help? Contact us at support@farmvax.com</p>
    </div>
</body>
</html>