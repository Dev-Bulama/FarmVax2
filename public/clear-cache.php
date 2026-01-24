<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Clearing All Caches...</h2>";

$caches = [
    'cache:clear' => 'Application Cache',
    'config:clear' => 'Configuration Cache',
    'route:clear' => 'Route Cache',
    'view:clear' => 'View Cache',
];

foreach ($caches as $command => $name) {
    try {
        Artisan::call($command);
        echo "✅ {$name} cleared<br>";
    } catch (\Exception $e) {
        echo "❌ {$name}: {$e->getMessage()}<br>";
    }
}

// Also manually delete cache files
$cachePaths = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes-v7.php',
    __DIR__ . '/../bootstrap/cache/packages.php',
    __DIR__ . '/../bootstrap/cache/services.php',
];

echo "<br><strong>Deleting cache files...</strong><br>";
foreach ($cachePaths as $path) {
    if (file_exists($path)) {
        unlink($path);
        echo "✅ Deleted: " . basename($path) . "<br>";
    }
}

echo "<br><br>✅ <strong style='color: green;'>All caches cleared!</strong><br>";
echo "<a href='/admin/outbreak-alerts'>Try Outbreak Alerts Now</a><br><br>";
echo "<strong>DELETE THIS FILE NOW!</strong>";