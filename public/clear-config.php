<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    Artisan::call('config:clear');
    echo "✓ Config cache cleared!<br>";
    echo "Now test email again at: <a href='/test-email.php'>test-email.php</a>";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

echo "<br><br><strong style='color:red;'>DELETE THIS FILE!</strong>";