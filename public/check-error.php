<?php
/**
 * FarmVax - Detailed Error Diagnostic
 * Access via: https://farmvax.com/check-error.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>FarmVax - Error Diagnostic Tool</h1>";
echo "<hr>";

echo "<h3>1. PHP Environment Check</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>Check</th><th>Status</th><th>Details</th></tr>";

// PHP Version
$phpVersion = phpversion();
$phpOk = version_compare($phpVersion, '8.1.0', '>=');
echo "<tr>";
echo "<td>PHP Version</td>";
echo "<td style='color: " . ($phpOk ? 'green' : 'red') . "'>" . ($phpOk ? '✅ OK' : '❌ FAIL') . "</td>";
echo "<td>$phpVersion " . ($phpOk ? '' : '(Requires 8.1+)') . "</td>";
echo "</tr>";

// Required Extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'curl', 'openssl', 'zip', 'xml'];
foreach ($requiredExtensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<tr>";
    echo "<td>Extension: $ext</td>";
    echo "<td style='color: " . ($loaded ? 'green' : 'red') . "'>" . ($loaded ? '✅ OK' : '❌ MISSING') . "</td>";
    echo "<td>" . ($loaded ? 'Loaded' : 'Not loaded') . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>2. File System Check</h3>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>Path</th><th>Exists</th><th>Writable</th></tr>";

$paths = [
    '../vendor/autoload.php' => 'file',
    '../bootstrap/app.php' => 'file',
    '../storage' => 'dir',
    '../storage/logs' => 'dir',
    '../storage/framework' => 'dir',
    '../storage/framework/cache' => 'dir',
    '../storage/framework/sessions' => 'dir',
    '../storage/framework/views' => 'dir',
    '../bootstrap/cache' => 'dir',
    '../.env' => 'file',
];

foreach ($paths as $path => $type) {
    $fullPath = __DIR__ . '/' . $path;
    $exists = file_exists($fullPath);
    $writable = is_writable($fullPath);

    echo "<tr>";
    echo "<td><code>$path</code></td>";
    echo "<td style='color: " . ($exists ? 'green' : 'red') . "'>" . ($exists ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td style='color: " . ($writable ? 'green' : 'orange') . "'>" . ($writable ? '✅ Yes' : '⚠️ No') . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>3. Laravel Bootstrap Test</h3>";
try {
    require __DIR__.'/../vendor/autoload.php';
    echo "<p style='color: green;'>✅ Composer autoload successful</p>";

    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "<p style='color: green;'>✅ Laravel app bootstrap successful</p>";

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "<p style='color: green;'>✅ HTTP Kernel loaded</p>";

    $kernel->bootstrap();
    echo "<p style='color: green;'>✅ Application bootstrapped successfully</p>";

    // Test database connection
    echo "<h4>Database Connection Test:</h4>";
    try {
        $pdo = DB::connection()->getPdo();
        echo "<p style='color: green;'>✅ Database connected: " . DB::connection()->getDatabaseName() . "</p>";

        // Test a simple query
        $result = DB::select('SELECT COUNT(*) as count FROM users');
        echo "<p style='color: green;'>✅ Database query successful: Found {$result[0]->count} users</p>";

    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
    }

    echo "<h4>Application Information:</h4>";
    echo "<pre>";
    echo "App Name: " . config('app.name') . "\n";
    echo "Environment: " . app()->environment() . "\n";
    echo "Debug Mode: " . (config('app.debug') ? 'Enabled' : 'Disabled') . "\n";
    echo "URL: " . config('app.url') . "\n";
    echo "Timezone: " . config('app.timezone') . "\n";
    echo "</pre>";

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Laravel Application is Working!</h2>";
    echo "<p>The 500 error might be specific to certain routes. Try these links:</p>";
    echo "<ul>";
    echo "<li><a href='/' target='_blank'>Homepage</a></li>";
    echo "<li><a href='/login' target='_blank'>Login</a></li>";
    echo "<li><a href='/admin/dashboard' target='_blank'>Admin Dashboard</a></li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Laravel Bootstrap Failed</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>" . $e->getTraceAsString() . "</pre>";

    echo "<h3>Common Causes:</h3>";
    echo "<ol>";
    echo "<li><strong>Syntax Error:</strong> Check recent file changes for PHP syntax errors</li>";
    echo "<li><strong>Class Not Found:</strong> Run: <code>composer dump-autoload</code></li>";
    echo "<li><strong>Cache Issue:</strong> Clear all caches using fix-500-error.php</li>";
    echo "<li><strong>File Permissions:</strong> Ensure storage/ and bootstrap/cache/ are writable</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<h3>4. Recent Laravel Log (Last 100 lines)</h3>";
$logFile = __DIR__.'/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $log = file_get_contents($logFile);
    $lines = explode("\n", $log);
    $recentLines = array_slice($lines, -100);
    echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto; max-height: 500px; font-size: 11px;'>";
    echo htmlspecialchars(implode("\n", $recentLines));
    echo "</pre>";
} else {
    echo "<p>No log file found at: storage/logs/laravel.log</p>";
}

echo "<hr>";
echo "<h3>5. Server Information</h3>";
echo "<pre>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PHP SAPI: " . php_sapi_name() . "\n";
echo "Operating System: " . PHP_OS . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "</pre>";

echo "<hr>";
echo "<p><strong>Action Steps:</strong></p>";
echo "<ol>";
echo "<li>First, try: <a href='fix-500-error.php' target='_blank'>fix-500-error.php</a> to clear all caches</li>";
echo "<li>If error persists, review the Laravel log above for specific error details</li>";
echo "<li>Check file permissions in cPanel File Manager (storage/ should be 755)</li>";
echo "<li>Verify .env file has correct database credentials</li>";
echo "<li><strong>Delete this file after fixing:</strong> public/check-error.php</li>";
echo "</ol>";

echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
