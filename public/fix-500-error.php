<?php
/**
 * FarmVax - Clear All Caches and Fix 500 Errors
 * Access via: https://farmvax.com/fix-500-error.php
 */

echo "<h1>FarmVax - Emergency Fix for 500 Error</h1>";
echo "<hr>";
echo "<p>This script will clear all caches and regenerate autoloader...</p>";

try {
    // Include Laravel bootstrap
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();

    echo "<h3>Step 1: Clearing Application Cache...</h3>";
    Artisan::call('cache:clear');
    echo "<p style='color: green;'>✅ Application cache cleared</p>";
    echo "<pre>" . Artisan::output() . "</pre>";

    echo "<h3>Step 2: Clearing Config Cache...</h3>";
    Artisan::call('config:clear');
    echo "<p style='color: green;'>✅ Config cache cleared</p>";
    echo "<pre>" . Artisan::output() . "</pre>";

    echo "<h3>Step 3: Clearing Route Cache...</h3>";
    Artisan::call('route:clear');
    echo "<p style='color: green;'>✅ Route cache cleared</p>";
    echo "<pre>" . Artisan::output() . "</pre>";

    echo "<h3>Step 4: Clearing View Cache...</h3>";
    Artisan::call('view:clear');
    echo "<p style='color: green;'>✅ View cache cleared</p>";
    echo "<pre>" . Artisan::output() . "</pre>";

    echo "<h3>Step 5: Optimizing Composer Autoloader...</h3>";
    echo "<p style='color: orange;'>⚠️ Note: This requires shell access. If this fails, run manually via SSH:</p>";
    echo "<pre>composer dump-autoload</pre>";

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ All Caches Cleared Successfully!</h2>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>1. Try accessing your site now: <a href='/' target='_blank'>Homepage</a> | <a href='/admin/dashboard' target='_blank'>Admin Dashboard</a></li>";
    echo "<li>2. If still showing 500 error, check the error log below</li>";
    echo "<li>3. Delete this file for security: <code>public/fix-500-error.php</code></li>";
    echo "</ul>";

    echo "<hr>";
    echo "<h3>Recent Laravel Error Log:</h3>";
    $logFile = __DIR__.'/../storage/logs/laravel.log';
    if (file_exists($logFile)) {
        $log = file_get_contents($logFile);
        $lines = explode("\n", $log);
        $recentLines = array_slice($lines, -50); // Last 50 lines
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto; max-height: 400px;'>";
        echo htmlspecialchars(implode("\n", $recentLines));
        echo "</pre>";
    } else {
        echo "<p>No log file found.</p>";
    }

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Error Occurred</h2>";
    echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";

    echo "<hr>";
    echo "<h3>Manual Fix Steps:</h3>";
    echo "<ol>";
    echo "<li><strong>Via SSH (if you have access):</strong><br>";
    echo "<pre>cd /path/to/farmvax\n";
    echo "php artisan cache:clear\n";
    echo "php artisan config:clear\n";
    echo "php artisan route:clear\n";
    echo "php artisan view:clear\n";
    echo "composer dump-autoload</pre></li>";

    echo "<li><strong>Via cPanel File Manager:</strong><br>";
    echo "Delete these directories if they exist:<br>";
    echo "<pre>bootstrap/cache/*.php (except .gitignore)\n";
    echo "storage/framework/cache/*\n";
    echo "storage/framework/views/*</pre></li>";

    echo "<li><strong>Check PHP Version:</strong><br>";
    echo "FarmVax requires PHP 8.1 or higher. Current PHP version: <strong>" . PHP_VERSION . "</strong></li>";

    echo "<li><strong>Check File Permissions:</strong><br>";
    echo "Ensure these directories are writable (755 or 775):<br>";
    echo "<pre>storage/\n";
    echo "storage/logs/\n";
    echo "storage/framework/\n";
    echo "storage/framework/cache/\n";
    echo "storage/framework/sessions/\n";
    echo "storage/framework/views/\n";
    echo "bootstrap/cache/</pre></li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . " | PHP Version: " . PHP_VERSION . "</p>";
?>
