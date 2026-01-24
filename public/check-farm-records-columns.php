<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Farm Records Table Structure</h2>";
echo "<hr>";

try {
    $columns = DB::select("SHOW COLUMNS FROM farm_records");
    
    echo "<h3>Columns in farm_records table:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Column Name</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>{$col->Field}</strong></td>";
        echo "<td>{$col->Type}</td>";
        echo "<td>{$col->Null}</td>";
        echo "<td>{$col->Key}</td>";
        echo "<td>" . ($col->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Check for common column names
    echo "<hr>";
    echo "<h3>Looking for user/owner columns:</h3>";
    $columnNames = array_map(function($col) { return $col->Field; }, $columns);
    
    $possibleUserColumns = ['user_id', 'owner_id', 'farmer_id', 'created_by', 'submitted_by', 'data_collector_id'];
    
    foreach ($possibleUserColumns as $colName) {
        if (in_array($colName, $columnNames)) {
            echo "✅ Found: <strong>{$colName}</strong><br>";
        } else {
            echo "❌ Missing: {$colName}<br>";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";