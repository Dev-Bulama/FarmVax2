<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved - FarmVax</title>
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
        .success-icon {
            width: 80px;
            height: 80px;
            margin: 20px auto;
            background: #2FCB6E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            color: #11455B;
            margin-bottom: 20px;
        }
        .message {
            margin: 20px 0;
            line-height: 1.8;
        }
        .highlight-box {
            background: #e8f5e9;
            border-left: 4px solid #2FCB6E;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
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
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            .email-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Congratulations!</h1>
        </div>

        <!-- Success Icon -->
        <div class="success-icon">
            ✓
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Dear <strong>{{ $professional->user->name }}</strong>,</p>

            <div class="message">
                <p>Great news! Your application as an <strong>Animal Health Professional</strong> on FarmVax has been <strong style="color:#2FCB6E;">APPROVED</strong>!</p>
                
                <p>You now have full access to all professional features on our platform.</p>
            </div>

            <!-- Highlight Box -->
            <div class="highlight-box">
                <h3 style="margin-top:0;color:#11455B;">Your Professional Account is Active</h3>
                <p style="margin-bottom:0;">You can now start providing veterinary services, responding to farmer requests, and contributing to livestock health in your community.</p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="cta-button">Access Your Dashboard</a>
            </div>

            <!-- What You Can Do -->
            <h3 style="color: #11455B; margin-top: 30px;">What You Can Do Now:</h3>
            <ul class="features-list">
                <li>View and respond to service requests from farmers</li>
                <li>Submit farm health reports and assessments</li>
                <li>Publish advisory content for the farming community</li>
                <li>Manage your service area and specializations</li>
                <li>Access detailed livestock health records</li>
                <li>Receive notifications about outbreaks in your area</li>
            </ul>

            <!-- Next Steps -->
            <h3 style="color: #11455B;">Recommended Next Steps:</h3>
            <ol style="line-height: 1.8;">
                <li>Complete your professional profile with specializations</li>
                <li>Set your service areas and availability</li>
                <li>Review pending service requests in your area</li>
                <li>Introduce yourself to the farming community</li>
            </ol>

            <div class="message" style="margin-top:30px;padding-top:20px;border-top:1px solid #eee;">
                <p>We're excited to have you as part of the FarmVax professional network. Together, we're building healthier livestock communities across Nigeria and Liberia!</p>
                
                <p>If you have any questions, our support team is here to help.</p>
            </div>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>The FarmVax Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>FarmVax</strong> - Protecting Livestock, Securing Livelihoods</p>
            
            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                This is an automated notification. You're receiving this because your application was approved.<br>
                If you have questions, contact us at support@farmvax.com
            </p>

            <p style="font-size: 12px; color: #999; margin-top: 10px;">
                &copy; {{ date('Y') }} FarmVax. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>