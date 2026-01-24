<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Update - FarmVax</title>
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
            background: #11455B;
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
        .greeting {
            font-size: 18px;
            color: #11455B;
            margin-bottom: 20px;
        }
        .message {
            margin: 20px 0;
            line-height: 1.8;
        }
        .reason-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .cta-button {
            display: inline-block;
            background: #11455B;
            color: #ffffff;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button:hover {
            background: #0d3345;
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
            <h1>Application Update</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Dear <strong>{{ $professional->user->name }}</strong>,</p>

            <div class="message">
                <p>Thank you for your interest in joining FarmVax as an Animal Health Professional.</p>
                
                <p>After careful review, we regret to inform you that we are unable to approve your professional application at this time.</p>
            </div>

            <!-- Reason Box -->
            <div class="reason-box">
                <h3 style="margin-top:0;color:#856404;">Reason for Decision:</h3>
                <p style="margin-bottom:0;">{{ $reason }}</p>
            </div>

            <!-- What's Next -->
            <div class="info-box">
                <h3 style="margin-top:0;color:#0c5460;">What You Can Do:</h3>
                <ul style="margin-bottom:0;padding-left:20px;">
                    <li>Review the reason provided above</li>
                    <li>Address any issues mentioned</li>
                    <li>You may reapply after 30 days</li>
                    <li>Contact our support team if you have questions</li>
                </ul>
            </div>

            <div class="message">
                <p>We appreciate your interest in contributing to livestock health in your community. If you believe this decision was made in error or if you have additional information to share, please don't hesitate to contact us.</p>
                
                <p><strong>Support Email:</strong> support@farmvax.com</p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="mailto:support@farmvax.com" class="cta-button">Contact Support</a>
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
                This is an automated notification regarding your professional application.<br>
                For questions, contact us at support@farmvax.com
            </p>

            <p style="font-size: 12px; color: #999; margin-top: 10px;">
                &copy; {{ date('Y') }} FarmVax. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>