<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;}
h1{color:#11455b;border-bottom:3px solid #2fcb6e;padding-bottom:10px;}
.success{background:#d4edda;border-left:4px solid #28a745;padding:15px;margin:10px 0;border-radius:4px;}
.error{background:#f8d7da;border-left:4px solid #dc3545;padding:15px;margin:10px 0;border-radius:4px;}
.info{background:#d1ecf1;border-left:4px solid #17a2b8;padding:15px;margin:10px 0;border-radius:4px;}
pre{background:#f4f4f4;padding:10px;border-radius:4px;overflow-x:auto;font-size:12px;}
.btn{display:inline-block;padding:10px 20px;background:#2fcb6e;color:white;text-decoration:none;border-radius:5px;margin:10px 5px;border:none;cursor:pointer;}
</style>";

echo "<div class='container'>";
echo "<h1>📧 Email Configuration Test</h1>";

// Check mail configuration
echo "<h2>1. Mail Configuration</h2>";
echo "<pre>";
echo "MAIL_MAILER: " . Config::get('mail.default') . "\n";
echo "MAIL_HOST: " . Config::get('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . Config::get('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . Config::get('mail.mailers.smtp.username') . "\n";
echo "MAIL_ENCRYPTION: " . Config::get('mail.mailers.smtp.encryption') . "\n";
echo "MAIL_FROM_ADDRESS: " . Config::get('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . Config::get('mail.from.name') . "\n";
echo "</pre>";

// Check if .env is loaded
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    echo "<div class='success'>✓ .env file exists</div>";
} else {
    echo "<div class='error'>✗ .env file not found!</div>";
}

// Test email sending
if (isset($_POST['send_test'])) {
    echo "<h2>2. Sending Test Email...</h2>";
    
    $testEmail = $_POST['test_email'] ?? 'test@example.com';
    
    try {
        Mail::raw('This is a test email from FarmVax. If you receive this, your email configuration is working correctly!', function ($message) use ($testEmail) {
            $message->to($testEmail)
                ->subject('FarmVax Email Test');
        });
        
        echo "<div class='success'><strong>✓ Email sent successfully!</strong><br>";
        echo "Check your inbox at: {$testEmail}<br>";
        echo "Don't forget to check spam/junk folder.</div>";
        
    } catch (\Exception $e) {
        echo "<div class='error'><strong>✗ Email failed to send!</strong><br>";
        echo "Error: " . $e->getMessage() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "</div>";
    }
    
} else {
    echo "<h2>2. Send Test Email</h2>";
    echo "<form method='POST'>";
    echo "<div style='margin:20px 0;'>";
    echo "<label style='display:block;margin-bottom:5px;font-weight:bold;'>Enter your email address:</label>";
    echo "<input type='email' name='test_email' required style='padding:10px;width:100%;max-width:400px;border:1px solid #ddd;border-radius:4px;' placeholder='your@email.com'>";
    echo "</div>";
    echo "<button type='submit' name='send_test' value='1' class='btn'>Send Test Email</button>";
    echo "</form>";
}

// Check logs
echo "<h2>3. Recent Laravel Logs</h2>";
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "<div class='info'>";
    echo "<strong>Last 30 lines of laravel.log:</strong>";
    echo "<pre>";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -30);
    echo htmlspecialchars(implode('', $lastLines));
    echo "</pre>";
    echo "</div>";
} else {
    echo "<div class='error'>Log file not found</div>";
}

// Check mail queue
echo "<h2>4. Mail Queue Status</h2>";
echo "<div class='info'>";
echo "<p>If emails are not being sent, check:</p>";
echo "<ul>";
echo "<li>Make sure <code>QUEUE_CONNECTION=sync</code> in .env (for immediate sending)</li>";
echo "<li>Check if your hosting provider blocks outgoing SMTP</li>";
echo "<li>Verify Hostinger email account is active</li>";
echo "<li>Check spam folder in recipient email</li>";
echo "</ul>";
echo "</div>";

// Provide solution
echo "<h2>5. Troubleshooting</h2>";
echo "<div class='info'>";
echo "<h3>Common Issues:</h3>";
echo "<ol>";
echo "<li><strong>Config cache:</strong> Run <code>php artisan config:clear</code> or use button below</li>";
echo "<li><strong>Wrong credentials:</strong> Verify email and password in .env</li>";
echo "<li><strong>Port 465 blocked:</strong> Try port 587 with TLS</li>";
echo "<li><strong>Hostinger restrictions:</strong> Contact support to enable SMTP</li>";
echo "</ol>";
echo "</div>";

// Clear config button
if (isset($_POST['clear_config'])) {
    try {
        \Artisan::call('config:clear');
        echo "<div class='success'>✓ Config cache cleared! Refresh this page.</div>";
    } catch (\Exception $e) {
        echo "<div class='error'>Failed to clear config: " . $e->getMessage() . "</div>";
    }
}

echo "<form method='POST'>";
echo "<button type='submit' name='clear_config' value='1' class='btn' style='background:#ffc107;color:#000;'>Clear Config Cache</button>";
echo "</form>";

echo "<br><p style='color:red;'><strong>DELETE THIS FILE after testing!</strong></p>";
echo "</div>";