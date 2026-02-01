<?php
// SMS Settings Debug Tool
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

?>
<!DOCTYPE html>
<html>
<head>
    <title>SMS Settings Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #11455B; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #11455B; color: white; }
        .value { background: #e8f5e9; padding: 5px; border-radius: 4px; }
        .empty { background: #ffebee; padding: 5px; border-radius: 4px; color: #c62828; }
        .warning { background: #fff3e0; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ff9800; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 SMS Settings Debug</h1>
        
        <div class="warning">
            <strong>⚠️ Security:</strong> Delete this file after debugging. It shows your configuration.
        </div>

        <h2>Kudi SMS Configuration</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Value</th>
                <th>Length</th>
                <th>Type</th>
                <th>Empty?</th>
            </tr>
            <?php
            $settings = [
                'sms_provider' => \App\Models\Setting::get('sms_provider'),
                'kudi_api_key' => \App\Models\Setting::get('kudi_api_key'),
                'kudi_username' => \App\Models\Setting::get('kudi_username'),
                'kudi_password' => \App\Models\Setting::get('kudi_password'),
                'kudi_sender_id' => \App\Models\Setting::get('kudi_sender_id'),
            ];

            foreach ($settings as $key => $value) {
                $displayValue = $value;
                if ($key === 'kudi_password' && !empty($value)) {
                    $displayValue = str_repeat('•', min(strlen($value), 12));
                } else if ($key === 'kudi_api_key' && !empty($value)) {
                    $displayValue = substr($value, 0, 10) . '...' . substr($value, -10);
                }
                
                $isEmpty = empty($value);
                $class = $isEmpty ? 'empty' : 'value';
                $emptyText = $isEmpty ? 'YES ❌' : 'NO ✅';
                
                echo "<tr>";
                echo "<td><strong>$key</strong></td>";
                echo "<td class='$class'>" . htmlspecialchars($displayValue ?: '(empty)') . "</td>";
                echo "<td>" . strlen($value ?? '') . " chars</td>";
                echo "<td>" . gettype($value) . "</td>";
                echo "<td>$emptyText</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <h2>Authentication Check</h2>
        <?php
        $apiKey = \App\Models\Setting::get('kudi_api_key');
        $username = \App\Models\Setting::get('kudi_username');
        $password = \App\Models\Setting::get('kudi_password');
        $senderId = \App\Models\Setting::get('kudi_sender_id');

        $hasApiKey = !empty($apiKey);
        $hasUsernamePassword = !empty($username) && !empty($password);

        echo "<table>";
        echo "<tr><th>Check</th><th>Result</th></tr>";
        echo "<tr><td>Has API Key?</td><td>" . ($hasApiKey ? '✅ YES' : '❌ NO') . "</td></tr>";
        echo "<tr><td>Has Username?</td><td>" . (!empty($username) ? '✅ YES' : '❌ NO') . "</td></tr>";
        echo "<tr><td>Has Password?</td><td>" . (!empty($password) ? '✅ YES' : '❌ NO') . "</td></tr>";
        echo "<tr><td>Has Username/Password?</td><td>" . ($hasUsernamePassword ? '✅ YES' : '❌ NO') . "</td></tr>";
        echo "<tr><td>Has Sender ID?</td><td>" . (!empty($senderId) ? '✅ YES (' . htmlspecialchars($senderId) . ')' : '❌ NO') . "</td></tr>";
        echo "<tr><td><strong>Auth Method</strong></td><td><strong>" . ($hasApiKey ? '🔑 API Key' : ($hasUsernamePassword ? '👤 Username/Password' : '❌ NONE')) . "</strong></td></tr>";
        echo "</table>";

        if (!$hasApiKey && !$hasUsernamePassword) {
            echo "<div class='warning'><strong>❌ ERROR:</strong> No authentication method configured!</div>";
        }

        if (!$senderId) {
            echo "<div class='warning'><strong>❌ ERROR:</strong> Sender ID not configured!</div>";
        }
        ?>

        <h2>Test URL (Username/Password)</h2>
        <?php
        if ($hasUsernamePassword && $senderId) {
            $testParams = [
                'username' => $username,
                'password' => '***HIDDEN***',
                'sender' => $senderId,
                'mobiles' => '2348012345678',
                'message' => 'Test'
            ];
            $testUrl = 'https://account.kudisms.net/api/?' . http_build_query($testParams);
            echo "<div class='value' style='padding: 15px; word-break: break-all;'>" . htmlspecialchars($testUrl) . "</div>";
        } else {
            echo "<div class='empty' style='padding: 15px;'>Cannot generate test URL - missing credentials</div>";
        }
        ?>

        <h2>Raw Setting Values (for debugging)</h2>
        <pre style="background: #263238; color: #00FF00; padding: 20px; border-radius: 6px; overflow-x: auto;">
<?php
print_r($settings);
?>
        </pre>
    </div>
</body>
</html>
