<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Dashboard File Checker</h2>";
echo "<hr>";

$possibleLocations = [
    'resources/views/farmer/dashboard.blade.php',
    'resources/views/individual/dashboard.blade.php',
    'resources/views/dashboard.blade.php',
    'resources/views/layouts/farmer-dashboard.blade.php',
];

echo "<h3>Checking possible dashboard locations:</h3>";

foreach ($possibleLocations as $location) {
    $fullPath = base_path($location);
    if (file_exists($fullPath)) {
        echo "✅ Found: <strong>{$location}</strong><br>";
        
        // Check if it has AdService code
        $content = file_get_contents($fullPath);
        
        if (strpos($content, 'AdService') !== false) {
            echo "   ✅ Has AdService code<br>";
        } else {
            echo "   ❌ <strong style='color: red;'>Missing AdService code!</strong><br>";
            echo "   This file needs to be updated with ad display code<br>";
        }
        
        if (strpos($content, '$bannerAds') !== false) {
            echo "   ✅ Has banner ads variable<br>";
        } else {
            echo "   ❌ Missing banner ads variable<br>";
        }
        
        if (strpos($content, '$sidebarAds') !== false) {
            echo "   ✅ Has sidebar ads variable<br>";
        } else {
            echo "   ❌ Missing sidebar ads variable<br>";
        }
        
        echo "<br>";
    } else {
        echo "❌ Not found: {$location}<br>";
    }
}

echo "<hr>";
echo "<strong>If a dashboard file is missing AdService code, you need to update it!</strong>";
echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";