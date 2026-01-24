<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<h2>Clearing All Caches</h2>";
echo "<hr>";

$commands = [
    'route:clear' => 'Route cache',
    'cache:clear' => 'Application cache',
    'view:clear' => 'View cache',
    'config:clear' => 'Config cache',
];

foreach ($commands as $command => $name) {
    try {
        Artisan::call($command);
        echo "✅ Cleared {$name}<br>";
    } catch (\Exception $e) {
        echo "❌ Error clearing {$name}: " . $e->getMessage() . "<br>";
    }
}

echo "<br><strong style='color: green;'>All caches cleared!</strong><br>";
echo "<br><a href='/professional/service-requests'>Go to Service Requests</a>";
echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";