<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Password Reset System Verification</h2>";
echo "<hr>";

$allGood = true;

// 1. Check if password_reset_tokens table exists
echo "<h3>1. Checking Database Table...</h3>";
try {
    if (Schema::hasTable('password_reset_tokens')) {
        echo "✅ password_reset_tokens table exists<br>";
        
        $columns = DB::select("SHOW COLUMNS FROM password_reset_tokens");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Column</th><th>Type</th></tr>";
        foreach ($columns as $col) {
            echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td></tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ password_reset_tokens table DOES NOT exist<br>";
        echo "<strong>Creating table...</strong><br>";
        
        DB::statement("
            CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
                `email` VARCHAR(255) NOT NULL,
                `token` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        echo "✅ Table created successfully!<br>";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    $allGood = false;
}

echo "<hr>";

// 2. Check if controller exists
echo "<h3>2. Checking Controller...</h3>";
$controllerPath = __DIR__ . '/../app/Http/Controllers/Auth/PasswordResetController.php';
if (file_exists($controllerPath)) {
    echo "✅ PasswordResetController.php exists<br>";
} else {
    echo "❌ PasswordResetController.php NOT FOUND<br>";
    $allGood = false;
}

echo "<hr>";

// 3. Check if views exist
echo "<h3>3. Checking Views...</h3>";
$views = [
    'auth/forgot-password.blade.php',
    'auth/reset-password.blade.php',
    'emails/password-reset.blade.php',
    'emails/password-changed.blade.php'
];

foreach ($views as $view) {
    $viewPath = __DIR__ . '/../resources/views/' . $view;
    if (file_exists($viewPath)) {
        echo "✅ $view exists<br>";
    } else {
        echo "❌ $view NOT FOUND<br>";
        $allGood = false;
    }
}

echo "<hr>";

// 4. Check routes
echo "<h3>4. Checking Routes...</h3>";
try {
    $routes = [
        'password.request' => '/password/forgot',
        'password.email' => '/password/email',
        'password.reset' => '/password/reset/{token}',
        'password.update' => '/password/reset'
    ];
    
    foreach ($routes as $name => $path) {
        try {
            $url = route($name, $name === 'password.reset' ? ['token' => 'test'] : []);
            echo "✅ Route '$name' registered → $path<br>";
        } catch (\Exception $e) {
            echo "❌ Route '$name' NOT FOUND<br>";
            $allGood = false;
        }
    }
} catch (\Exception $e) {
    echo "❌ Error checking routes: " . $e->getMessage() . "<br>";
    $allGood = false;
}

echo "<hr>";

// 5. Check mail configuration
echo "<h3>5. Checking Mail Configuration...</h3>";
try {
    $mailDriver = config('mail.default');
    $mailFrom = config('mail.from.address');
    
    echo "Mail Driver: <strong>$mailDriver</strong><br>";
    echo "From Address: <strong>$mailFrom</strong><br>";
    
    if (empty($mailFrom) || $mailFrom === 'hello@example.com') {
        echo "⚠️ <strong>Warning:</strong> Mail from address not configured properly<br>";
        echo "Update .env file with proper MAIL settings<br>";
    } else {
        echo "✅ Mail configuration looks good<br>";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// 6. Test users exist
echo "<h3>6. Checking Test Users...</h3>";
try {
    $userCount = DB::table('users')->count();
    echo "Total users in database: <strong>$userCount</strong><br>";
    
    if ($userCount > 0) {
        $testUser = DB::table('users')->first();
        echo "Sample user email: <strong>{$testUser->email}</strong><br>";
        echo "✅ You can test password reset with this email<br>";
    } else {
        echo "⚠️ No users found. Create a user first before testing.<br>";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Summary
if ($allGood) {
    echo "<h2 style='color: green;'>✅ ALL CHECKS PASSED!</h2>";
    echo "<p><strong>Password Reset system is ready to use!</strong></p>";
} else {
    echo "<h2 style='color: red;'>❌ SOME CHECKS FAILED</h2>";
    echo "<p>Please fix the issues above before testing.</p>";
}

// Clear cache
Artisan::call('route:clear');
Artisan::call('cache:clear');
echo "<br>✅ Caches cleared<br>";

echo "<hr>";
echo "<h3>Quick Links:</h3>";
echo "<a href='/login' style='margin-right: 10px;'><button style='padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;'>Go to Login</button></a>";
echo "<a href='/password/forgot'><button style='padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 5px; cursor: pointer;'>Test Password Reset</button></a>";

echo "<br><br><strong style='color: red;'>DELETE THIS FILE AFTER VERIFICATION!</strong>";