<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Clearing Routes Cache</h2>";

Artisan::call('route:clear');
echo "✅ Routes cleared<br>";

Artisan::call('cache:clear');
echo "✅ Cache cleared<br>";

Artisan::call('config:clear');
echo "✅ Config cleared<br>";

echo "<br>✅ <strong style='color: green;'>Done!</strong><br>";
echo "<br><a href='/register/farmer'><strong>Test Farmer Registration</strong></a><br>";
echo "<br><strong style='color: red;'>DELETE THIS FILE!</strong>";