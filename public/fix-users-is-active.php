<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Adding is_active Column to Users Table</h2>";

try {
    $columns = DB::select("SHOW COLUMNS FROM users");
    $existingColumns = array_map(function($col) { return $col->Field; }, $columns);
    
    echo "<strong>Current columns:</strong><br>";
    echo implode(', ', $existingColumns) . "<br><br>";
    
    // Add is_active column
    if (!in_array('is_active', $existingColumns)) {
        DB::statement("ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1");
        echo "✅ Added is_active column<br>";
    } else {
        echo "✅ is_active column already exists<br>";
    }
    
    echo "<br>✅ <strong style='color: green;'>Users table fixed!</strong><br>";
    
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

echo "<br><a href='/register/farmer'><strong>Try Registration Again</strong></a><br>";
echo "<br><strong style='color: red;'>DELETE THIS FILE!</strong>";