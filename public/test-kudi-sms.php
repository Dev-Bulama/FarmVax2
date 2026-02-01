<?php
// Kudi SMS Diagnostic Test Tool
// This helps debug SMS sending issues

if (isset($_POST['test_sms'])) {
    header('Content-Type: application/json');

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $sender = $_POST['sender'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $message = $_POST['message'] ?? 'Test message from FarmVax';

    // Format phone number
    $formattedPhone = $mobile;
    if (substr($mobile, 0, 1) === '0') {
        $formattedPhone = '234' . substr($mobile, 1);
    }
    $formattedPhone = str_replace(['+', ' ', '-'], '', $formattedPhone);

    $params = [
        'username' => $username,
        'password' => $password,
        'sender' => $sender,
        'mobiles' => $formattedPhone,
        'message' => $message
    ];

    $url = 'https://account.kudisms.net/api/?' . http_build_query($params);
    $maskedUrl = preg_replace('/password=[^&]+/', 'password=***', $url);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    $result = [
        'url' => $maskedUrl,
        'formatted_phone' => $formattedPhone,
        'http_code' => $httpCode,
        'response' => $response,
        'response_json' => json_decode($response, true),
        'curl_error' => $curlError ?: null
    ];

    echo json_encode($result, JSON_PRETTY_PRINT);
    exit;
}

// Also test balance
if (isset($_POST['test_balance'])) {
    header('Content-Type: application/json');

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $url = 'https://account.kudisms.net/api/?' . http_build_query([
        'username' => $username,
        'password' => $password,
        'action' => 'balance'
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo json_encode([
        'http_code' => $httpCode,
        'response' => $response,
        'response_json' => json_decode($response, true)
    ], JSON_PRETTY_PRINT);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kudi SMS Diagnostic Tool</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #11455B 0%, #0d3345 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 24px; margin-bottom: 10px; }
        .content { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: inherit; }
        textarea { min-height: 80px; resize: vertical; }
        button { background: #11455B; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; margin-right: 10px; margin-bottom: 10px; }
        button:hover { background: #0d3345; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        .result { background: #263238; color: #00FF00; padding: 20px; border-radius: 6px; font-family: 'Courier New', monospace; font-size: 13px; margin-top: 20px; overflow-x: auto; white-space: pre-wrap; word-break: break-all; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 6px; border-left: 4px solid #4caf50; margin-top: 20px; }
        .error { background: #ffebee; color: #c62828; padding: 15px; border-radius: 6px; border-left: 4px solid #f44336; margin-top: 20px; }
        .warning { background: #fff3e0; color: #e65100; padding: 15px; border-radius: 6px; border-left: 4px solid #ff9800; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Kudi SMS Diagnostic Tool</h1>
            <p>Test your Kudi SMS credentials and sender ID</p>
        </div>

        <div class="content">
            <div class="warning">
                <strong>⚠️ Security Notice:</strong> This is a diagnostic tool. Delete this file after testing.
            </div>

            <form id="testForm">
                <div class="form-group">
                    <label>Kudi SMS Username:</label>
                    <input type="text" name="username" id="username" required placeholder="your-username">
                </div>

                <div class="form-group">
                    <label>Kudi SMS Password:</label>
                    <input type="password" name="password" id="password" required placeholder="your-password">
                </div>

                <div class="form-group">
                    <label>Sender ID:</label>
                    <input type="text" name="sender" id="sender" required placeholder="FarmVax" maxlength="11">
                    <small style="color: #666;">Max 11 characters. Must be approved in your Kudi SMS account.</small>
                </div>

                <div class="form-group">
                    <label>Test Phone Number:</label>
                    <input type="tel" name="mobile" id="mobile" required placeholder="08012345678 or 2348012345678">
                    <small style="color: #666;">Nigerian mobile number</small>
                </div>

                <div class="form-group">
                    <label>Test Message:</label>
                    <textarea name="message" id="message">Hello! This is a test SMS from FarmVax.</textarea>
                </div>

                <button type="button" onclick="testBalance()" id="balanceBtn">1. Test Balance (Check Credentials)</button>
                <button type="button" onclick="testSMS()" id="smsBtn">2. Test Send SMS</button>
            </form>

            <div id="output"></div>
        </div>
    </div>

    <script>
        async function testBalance() {
            const btn = document.getElementById('balanceBtn');
            const output = document.getElementById('output');

            btn.disabled = true;
            btn.textContent = 'Testing...';
            output.innerHTML = '<div class="warning">Checking credentials...</div>';

            const formData = new FormData();
            formData.append('test_balance', '1');
            formData.append('username', document.getElementById('username').value);
            formData.append('password', document.getElementById('password').value);

            try {
                const response = await fetch('', { method: 'POST', body: formData });
                const data = await response.json();

                let html = '<div class="result">' + JSON.stringify(data, null, 2) + '</div>';

                if (data.response_json && data.response_json.balance !== undefined) {
                    html = '<div class="success"><strong>✅ Credentials Valid!</strong><br>Balance: ₦' + data.response_json.balance + '</div>' + html;
                } else {
                    html = '<div class="error"><strong>❌ Credentials Invalid</strong><br>Check your username and password</div>' + html;
                }

                output.innerHTML = html;
            } catch (error) {
                output.innerHTML = '<div class="error"><strong>❌ Error:</strong> ' + error.message + '</div>';
            }

            btn.disabled = false;
            btn.textContent = '1. Test Balance (Check Credentials)';
        }

        async function testSMS() {
            const btn = document.getElementById('smsBtn');
            const output = document.getElementById('output');

            btn.disabled = true;
            btn.textContent = 'Sending SMS...';
            output.innerHTML = '<div class="warning">Sending test SMS...</div>';

            const formData = new FormData(document.getElementById('testForm'));
            formData.append('test_sms', '1');

            try {
                const response = await fetch('', { method: 'POST', body: formData });
                const data = await response.json();

                let html = '<div class="result">' + JSON.stringify(data, null, 2) + '</div>';

                // Check if successful
                const responseText = data.response || '';
                const responseJson = data.response_json || {};

                if (responseJson.status === 'success' || responseJson.status === 'ok' ||
                    responseText.includes('OK') || responseText.includes('success') || responseText.includes('sent')) {
                    html = '<div class="success"><strong>✅ SMS Sent Successfully!</strong><br>Check the phone for delivery.</div>' + html;
                } else if (responseJson.errno === '110' || responseJson.error) {
                    let errorMsg = responseJson.error || 'Unknown error';
                    let tips = '<br><br><strong>Troubleshooting Tips:</strong><ul style="margin-top: 10px; margin-left: 20px;">';

                    if (errorMsg.includes('Login request failed') || responseJson.errno === '110') {
                        tips += '<li>Verify your Sender ID "' + document.getElementById('sender').value + '" is approved in your Kudi SMS account</li>';
                        tips += '<li>Check that the Sender ID has no extra spaces or special characters</li>';
                        tips += '<li>Confirm your account is active and has sufficient balance</li>';
                        tips += '<li>Try using a different sender ID that is approved</li>';
                    }
                    tips += '</ul>';

                    html = '<div class="error"><strong>❌ SMS Failed</strong><br>Error: ' + errorMsg + tips + '</div>' + html;
                } else {
                    html = '<div class="warning"><strong>⚠️ Unexpected Response</strong><br>Check the JSON below</div>' + html;
                }

                output.innerHTML = html;
            } catch (error) {
                output.innerHTML = '<div class="error"><strong>❌ Error:</strong> ' + error.message + '</div>';
            }

            btn.disabled = false;
            btn.textContent = '2. Test Send SMS';
        }
    </script>
</body>
</html>
