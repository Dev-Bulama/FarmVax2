<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Adding Location Columns to Users Table</h2>";

try {
    $columns = DB::select("SHOW COLUMNS FROM users");
    $existingColumns = array_map(function($col) { return $col->Field; }, $columns);
    
    echo "<strong>Current columns:</strong><br>";
    echo implode(', ', $existingColumns) . "<br><br>";
    
    // Add latitude
    if (!in_array('latitude', $existingColumns)) {
        DB::statement("ALTER TABLE users ADD COLUMN latitude DECIMAL(10, 8) NULL");
        echo "✅ Added latitude column<br>";
    } else {
        echo "✅ latitude column already exists<br>";
    }
    
    // Add longitude
    if (!in_array('longitude', $existingColumns)) {
        DB::statement("ALTER TABLE users ADD COLUMN longitude DECIMAL(11, 8) NULL");
        echo "✅ Added longitude column<br>";
    } else {
        echo "✅ longitude column already exists<br>";
    }
    
    // Add address
    if (!in_array('address', $existingColumns)) {
        DB::statement("ALTER TABLE users ADD COLUMN address VARCHAR(255) NULL");
        echo "✅ Added address column<br>";
    } else {
        echo "✅ address column already exists<br>";
    }
    
    // Add farm_name
    if (!in_array('farm_name', $existingColumns)) {
        DB::statement("ALTER TABLE users ADD COLUMN farm_name VARCHAR(255) NULL");
        echo "✅ Added farm_name column<br>";
    } else {
        echo "✅ farm_name column already exists<br>";
    }
    
    // Add farm_size
    if (!in_array('farm_size', $existingColumns)) {
        DB::statement("ALTER TABLE users ADD COLUMN farm_size DECIMAL(10, 2) NULL");
        echo "✅ Added farm_size column<br>";
    } else {
        echo "✅ farm_size column already exists<br>";
    }
    
    echo "<br>✅ <strong style='color: green;'>Users table updated successfully!</strong><br>";
    
    // Show final structure
    echo "<br><strong>Final columns:</strong><br>";
    $finalColumns = DB::select("SHOW COLUMNS FROM users");
    foreach ($finalColumns as $col) {
        echo "- {$col->Field} ({$col->Type})<br>";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Clear cache
Artisan::call('cache:clear');
Artisan::call('config:clear');
echo "<br>✅ Caches cleared<br>";

echo "<br><a href='/register'><strong>Go to Registration</strong></a><br>";
echo "<br><strong style='color: red;'>DELETE THIS FILE!</strong>";