<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Models\AnimalHealthProfessional;
use App\Models\User;

echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.container{max-width:900px;margin:0 auto;background:white;padding:30px;border-radius:8px;}
h1{color:#11455b;border-bottom:3px solid #2fcb6e;padding-bottom:10px;}
.success{background:#d4edda;border-left:4px solid #28a745;padding:15px;margin:10px 0;border-radius:4px;}
.error{background:#f8d7da;border-left:4px solid #dc3545;padding:15px;margin:10px 0;border-radius:4px;}
.info{background:#d1ecf1;border-left:4px solid #17a2b8;padding:15px;margin:10px 0;border-radius:4px;}
.warning{background:#fff3cd;border-left:4px solid #ffc107;padding:15px;margin:10px 0;border-radius:4px;}
pre{background:#f4f4f4;padding:10px;border-radius:4px;overflow-x:auto;font-size:12px;}
.btn{display:inline-block;padding:10px 20px;background:#2fcb6e;color:white;text-decoration:none;border-radius:5px;margin:10px 5px;border:none;cursor:pointer;}
table{width:100%;border-collapse:collapse;margin:10px 0;}
table th,table td{border:1px solid #ddd;padding:8px;text-align:left;}
table th{background:#f4f4f4;}
</style>";

echo "<div class='container'>";
echo "<h1>🧪 Professional Approval Email Test</h1>";

// Step 1: Check if email template exists
echo "<h2>1. Check Email Template</h2>";
$templatePath = __DIR__ . '/../resources/views/emails/professional-approved.blade.php';
if (file_exists($templatePath)) {
    echo "<div class='success'>✓ Email template exists: professional-approved.blade.php</div>";
} else {
    echo "<div class='error'>✗ Email template NOT found at: {$templatePath}</div>";
    echo "<p>Please create the email template first!</p>";
    exit;
}

// Step 2: Find professionals
echo "<h2>2. Available Professionals</h2>";
$professionals = AnimalHealthProfessional::with('user')->get();

if ($professionals->count() === 0) {
    echo "<div class='error'>✗ No professionals found in database!</div>";
    exit;
}

echo "<div class='info'>Found {$professionals->count()} professional(s)</div>";

echo "<table>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr>";
foreach ($professionals as $prof) {
    $statusColor = $prof->approval_status === 'pending' ? 'orange' : ($prof->approval_status === 'approved' ? 'green' : 'red');
    echo "<tr>";
    echo "<td>{$prof->id}</td>";
    echo "<td>{$prof->user->name}</td>";
    echo "<td>{$prof->user->email}</td>";
    echo "<td><span style='color:{$statusColor};font-weight:bold;'>{$prof->approval_status}</span></td>";
    echo "<td><a href='?test_id={$prof->id}' class='btn' style='padding:5px 10px;margin:0;'>Test Email</a></td>";
    echo "</tr>";
}
echo "</table>";

// Step 3: Test sending email
if (isset($_GET['test_id'])) {
    $testId = $_GET['test_id'];
    
    echo "<h2>3. Testing Email for Professional ID: {$testId}</h2>";
    
    try {
        $professional = AnimalHealthProfessional::with('user')->findOrFail($testId);
        
        echo "<div class='info'>";
        echo "<strong>Professional Details:</strong><br>";
        echo "Name: {$professional->user->name}<br>";
        echo "Email: {$professional->user->email}<br>";
        echo "Status: {$professional->approval_status}<br>";
        echo "</div>";
        
        // Check if user has valid email
        if (empty($professional->user->email) || !filter_var($professional->user->email, FILTER_VALIDATE_EMAIL)) {
            echo "<div class='error'>✗ Invalid email address: {$professional->user->email}</div>";
            exit;
        }
        
        // Try to render template first
        echo "<h3>Testing Template Rendering...</h3>";
        try {
            $view = view('emails.professional-approved', ['professional' => $professional])->render();
            echo "<div class='success'>✓ Template renders successfully!</div>";
            
            // Show preview
            echo "<details style='margin:20px 0;'>";
            echo "<summary style='cursor:pointer;font-weight:bold;padding:10px;background:#f4f4f4;'>📧 Email Preview (Click to expand)</summary>";
            echo "<div style='border:1px solid #ddd;padding:20px;margin-top:10px;max-height:400px;overflow-y:auto;'>";
            echo $view;
            echo "</div>";
            echo "</details>";
            
        } catch (\Exception $e) {
            echo "<div class='error'>✗ Template rendering failed!<br>";
            echo "Error: " . $e->getMessage() . "</div>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
            exit;
        }
        
        // Try sending email
        echo "<h3>Sending Email...</h3>";
        
        Mail::send('emails.professional-approved', [
            'professional' => $professional,
        ], function ($message) use ($professional) {
            $message->to($professional->user->email, $professional->user->name)
                ->subject('🎉 Your FarmVax Professional Account is Approved!');
        });
        
        echo "<div class='success'>";
        echo "<strong>✓ Email sent successfully!</strong><br>";
        echo "To: {$professional->user->email}<br>";
        echo "Subject: 🎉 Your FarmVax Professional Account is Approved!<br>";
        echo "<br>";
        echo "Check the inbox (and spam folder) of: <strong>{$professional->user->email}</strong>";
        echo "</div>";
        
        // Check Laravel logs
        echo "<h3>Recent Logs</h3>";
        $logFile = __DIR__ . '/../storage/logs/laravel.log';
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $lastLines = array_slice($lines, -20);
            echo "<pre style='max-height:300px;overflow-y:auto;'>";
            echo htmlspecialchars(implode('', $lastLines));
            echo "</pre>";
        }
        
    } catch (\Exception $e) {
        echo "<div class='error'>";
        echo "<strong>✗ Failed to send email!</strong><br>";
        echo "Error: " . $e->getMessage() . "<br>";
        echo "</div>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

// Step 4: Test the controller method directly
echo "<h2>4. Check Controller Configuration</h2>";
$controllerPath = __DIR__ . '/../app/Http/Controllers/Admin/ProfessionalApprovalController.php';
if (file_exists($controllerPath)) {
    echo "<div class='success'>✓ ProfessionalApprovalController exists</div>";
    
    // Check if it has the sendApprovalEmail method
    $content = file_get_contents($controllerPath);
    if (strpos($content, 'sendApprovalEmail') !== false) {
        echo "<div class='success'>✓ sendApprovalEmail method found in controller</div>";
    } else {
        echo "<div class='error'>✗ sendApprovalEmail method NOT found in controller</div>";
    }
    
    if (strpos($content, 'Mail::send') !== false || strpos($content, 'Mail::to') !== false) {
        echo "<div class='success'>✓ Mail sending code found in controller</div>";
    } else {
        echo "<div class='error'>✗ Mail sending code NOT found in controller</div>";
    }
} else {
    echo "<div class='error'>✗ ProfessionalApprovalController NOT found</div>";
}

// Step 5: Check mail config
echo "<h2>5. Mail Configuration</h2>";
echo "<pre>";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n";
echo "</pre>";

echo "<br><p style='color:red;'><strong>DELETE THIS FILE after testing!</strong></p>";
echo "</div>";