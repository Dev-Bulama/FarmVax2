<?php
/**
 * Enable Laravel Logging
 * Access via: https://farmvax.com/enable-logging.php
 */

echo "<h1>Enable Laravel Logging</h1>";
echo "<hr>";

$logDir = __DIR__ . '/../storage/logs';
$logFile = $logDir . '/laravel.log';

echo "<h3>Step 1: Check logs directory...</h3>";
if (!is_dir($logDir)) {
    echo "<p style='color: orange;'>⚠️ Logs directory does not exist. Creating...</p>";
    if (mkdir($logDir, 0755, true)) {
        echo "<p style='color: green;'>✅ Created: $logDir</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create logs directory</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Logs directory exists</p>";
}

echo "<h3>Step 2: Check log file...</h3>";
if (!file_exists($logFile)) {
    echo "<p style='color: orange;'>⚠️ Log file does not exist. Creating...</p>";
    if (touch($logFile)) {
        chmod($logFile, 0644);
        echo "<p style='color: green;'>✅ Created: $logFile</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create log file</p>";
    }
} else {
    echo "<p style='color: green;'>✅ Log file exists</p>";
}

echo "<h3>Step 3: Check permissions...</h3>";
$dirPerms = substr(sprintf('%o', fileperms($logDir)), -4);
$filePerms = file_exists($logFile) ? substr(sprintf('%o', fileperms($logFile)), -4) : 'N/A';

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Path</th><th>Permissions</th><th>Writable</th></tr>";
echo "<tr>";
echo "<td>$logDir</td>";
echo "<td>$dirPerms</td>";
echo "<td style='color: " . (is_writable($logDir) ? 'green' : 'red') . "'>" . (is_writable($logDir) ? '✅ Yes' : '❌ No') . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>$logFile</td>";
echo "<td>$filePerms</td>";
echo "<td style='color: " . (is_writable($logFile) ? 'green' : 'red') . "'>" . (is_writable($logFile) ? '✅ Yes' : '❌ No') . "</td>";
echo "</tr>";
echo "</table>";

echo "<h3>Step 4: Test logging...</h3>";
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();

    \Log::info('Test log entry from enable-logging.php');
    echo "<p style='color: green;'>✅ Test log written successfully</p>";

    // Show recent logs
    if (file_exists($logFile) && filesize($logFile) > 0) {
        echo "<h4>Recent log contents:</h4>";
        $contents = file_get_contents($logFile);
        $lines = explode("\n", $contents);
        $recent = array_slice($lines, -20);
        echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow: auto;'>";
        echo htmlspecialchars(implode("\n", $recent));
        echo "</pre>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>✅ Logging Setup Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>Logs will now be written to: <code>storage/logs/laravel.log</code></li>";
echo "<li>Try accessing your site again to generate error logs</li>";
echo "<li>Run <a href='check-error.php'>check-error.php</a> to see the logs</li>";
echo "<li>Delete this file after: <code>public/enable-logging.php</code></li>";
echo "</ul>";

echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
