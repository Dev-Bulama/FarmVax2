<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "<h2>Ad Image Path Checker</h2>";
echo "<hr>";

$ads = DB::table('ads')->get();

echo "<h3>Current Ad Images:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Title</th><th>image_url</th><th>image_path</th><th>File Exists?</th><th>Preview</th></tr>";

foreach ($ads as $ad) {
    echo "<tr>";
    echo "<td>{$ad->id}</td>";
    echo "<td>{$ad->title}</td>";
    echo "<td>" . ($ad->image_url ?? 'NULL') . "</td>";
    echo "<td>" . ($ad->image_path ?? 'NULL') . "</td>";
    
    // Check if file exists
    $exists = false;
    $correctPath = '';
    
    if ($ad->image_url) {
        // Check different possible paths
        if (file_exists(public_path($ad->image_url))) {
            $exists = true;
            $correctPath = asset($ad->image_url);
        } elseif (file_exists(storage_path('app/public/' . $ad->image_url))) {
            $exists = true;
            $correctPath = asset('storage/' . $ad->image_url);
        } elseif (file_exists(public_path('storage/' . $ad->image_url))) {
            $exists = true;
            $correctPath = asset('storage/' . $ad->image_url);
        }
    }
    
    if ($ad->image_path) {
        if (file_exists(storage_path('app/public/' . $ad->image_path))) {
            $exists = true;
            $correctPath = asset('storage/' . $ad->image_path);
        } elseif (file_exists(public_path('storage/' . $ad->image_path))) {
            $exists = true;
            $correctPath = asset('storage/' . $ad->image_path);
        }
    }
    
    echo "<td>" . ($exists ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td>";
    if ($exists) {
        echo "<img src='{$correctPath}' style='max-width: 100px; max-height: 60px;'><br>";
        echo "<small>{$correctPath}</small>";
    } else {
        echo "Not found";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";

// Check storage link
echo "<h3>Storage Configuration:</h3>";
$storageLinked = is_link(public_path('storage'));
echo "Storage symlink exists: " . ($storageLinked ? '✅ Yes' : '❌ No') . "<br>";

if (!$storageLinked) {
    echo "<br>⚠️ <strong>Storage link is missing!</strong><br>";
    echo "Run this command: <code>php artisan storage:link</code><br>";
    echo "Or create manually via script below.<br><br>";
    
    try {
        if (!file_exists(public_path('storage'))) {
            symlink(storage_path('app/public'), public_path('storage'));
            echo "✅ Storage link created!<br>";
        }
    } catch (\Exception $e) {
        echo "❌ Could not create storage link: " . $e->getMessage() . "<br>";
    }
}

echo "<br>Storage path: <code>" . storage_path('app/public') . "</code><br>";
echo "Public storage path: <code>" . public_path('storage') . "</code><br>";

// List files in storage/ads
echo "<hr>";
echo "<h3>Files in storage/app/public/ads:</h3>";
$adsPath = storage_path('app/public/ads');
if (is_dir($adsPath)) {
    $files = scandir($adsPath);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "<li>{$file}</li>";
        }
    }
    echo "</ul>";
} else {
    echo "❌ Directory does not exist: {$adsPath}<br>";
    mkdir($adsPath, 0755, true);
    echo "✅ Created directory<br>";
}

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";