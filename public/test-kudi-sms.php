<?php
/**
 * Test Kudi SMS API Parameters
 * Access via: https://farmvax.com/test-kudi-sms.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

echo "<h1>Kudi SMS API Test</h1>";
echo "<hr>";

// Get Kudi SMS settings
$username = Setting::get('kudi_username');
$password = Setting::get('kudi_password');
$senderId = Setting::get('kudi_sender_id');

echo "<h3>Step 1: Current Configuration</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Setting</th><th>Value</th><th>Status</th></tr>";
echo "<tr><td>kudi_username</td><td>" . ($username ?: '<em>Not set</em>') . "</td><td style='color: " . ($username ? 'green' : 'red') . "'>" . ($username ? '✅' : '❌') . "</td></tr>";
echo "<tr><td>kudi_password</td><td>" . ($password ? '***' : '<em>Not set</em>') . "</td><td style='color: " . ($password ? 'green' : 'red') . "'>" . ($password ? '✅' : '❌') . "</td></tr>";
echo "<tr><td>kudi_sender_id</td><td>" . ($senderId ?: '<em>Not set</em>') . "</td><td style='color: " . ($senderId ? 'green' : 'red') . "'>" . ($senderId ? '✅' : '❌') . "</td></tr>";
echo "</table>";

if (!$username || !$password || !$senderId) {
    echo "<p style='color: red;'><strong>❌ Configuration incomplete!</strong> Please set Kudi SMS credentials in Admin → Settings → SMS.</p>";
    echo "<p>Required settings:</p>";
    echo "<ul>";
    echo "<li><strong>kudi_username</strong> - Your Kudi SMS username</li>";
    echo "<li><strong>kudi_password</strong> - Your Kudi SMS password</li>";
    echo "<li><strong>kudi_sender_id</strong> - Your sender ID (e.g., FarmVax, YourCompany)</li>";
    echo "</ul>";
    exit;
}

echo "<hr>";
echo "<h3>Step 2: Test API Endpoints</h3>";

$testNumber = '2348012345678'; // Example number
$testMessage = 'Test message from FarmVax';

// Test different API endpoint variations
$endpoints = [
    'https://account.kudisms.net/api/',
    'https://account.kudisms.net/api',
    'http://account.kudisms.net/api/',
];

foreach ($endpoints as $endpoint) {
    echo "<h4>Testing: $endpoint</h4>";

    // Test with different parameter names
    $paramVariations = [
        [
            'username' => $username,
            'password' => $password,
            'sender' => $senderId,
            'recipient' => $testNumber,
            'message' => $testMessage
        ],
        [
            'username' => $username,
            'password' => $password,
            'sender' => $senderId,
            'mobiles' => $testNumber,
            'sms' => $testMessage
        ],
        [
            'username' => $username,
            'password' => $password,
            'from' => $senderId,
            'to' => $testNumber,
            'message' => $testMessage,
            'type' => 'plain'
        ],
    ];

    foreach ($paramVariations as $index => $params) {
        echo "<p><strong>Variation " . ($index + 1) . ":</strong> ";
        echo "<code>" . json_encode($params) . "</code></p>";

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px 0; border-left: 4px solid ";
        echo ($httpCode == 200 ? 'green' : 'orange');
        echo ";'>";
        echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
        if ($curlError) {
            echo "<p style='color: red;'><strong>cURL Error:</strong> $curlError</p>";
        }
        echo "<p><strong>Response:</strong></p>";
        echo "<pre style='background: white; padding: 10px; overflow-x: auto;'>";
        echo htmlspecialchars($response ?: 'No response');
        echo "</pre>";

        // Check if successful
        if (stripos($response, 'success') !== false || stripos($response, 'OK') !== false) {
            echo "<p style='color: green;'><strong>✅ This variation might be correct!</strong></p>";
        } elseif (stripos($response, 'Incomplete input parameters') !== false) {
            echo "<p style='color: orange;'><strong>⚠️ Still missing parameters</strong></p>";
        } elseif (stripos($response, 'Invalid credentials') !== false || stripos($response, 'authentication') !== false) {
            echo "<p style='color: red;'><strong>❌ Credentials issue</strong></p>";
        }

        echo "</div>";
    }

    echo "<hr>";
}

echo "<h3>Recommendations:</h3>";
echo "<ul>";
echo "<li>Look for the response marked with ✅ - that's the correct parameter combination</li>";
echo "<li>If all fail with 'Incomplete input parameters', you may need to contact Kudi SMS support</li>";
echo "<li>If you see 'Invalid credentials', check your username/password</li>";
echo "<li>Once you find the working combination, update the SmsService.php accordingly</li>";
echo "<li><strong>Delete this file after testing:</strong> public/test-kudi-sms.php</li>";
echo "</ul>";

echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
