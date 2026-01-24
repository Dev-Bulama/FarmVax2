<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Adding Profile Columns to Users Table</h2>";
echo "<hr>";

try {
    // Check current columns
    $columns = DB::select("SHOW COLUMNS FROM users");
    $existingColumns = array_column($columns, 'Field');
    
    echo "<h3>Current Columns:</h3>";
    echo "<p>" . implode(', ', $existingColumns) . "</p>";
    echo "<hr>";
    
    $columnsAdded = 0;
    
    // Required profile columns
    $requiredColumns = [
        'phone' => "ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER email",
        'address' => "ALTER TABLE users ADD COLUMN address TEXT NULL AFTER phone",
        'city' => "ALTER TABLE users ADD COLUMN city VARCHAR(255) NULL AFTER address",
        'state' => "ALTER TABLE users ADD COLUMN state VARCHAR(255) NULL AFTER city",
        'country' => "ALTER TABLE users ADD COLUMN country VARCHAR(255) NULL AFTER state",
        'lga' => "ALTER TABLE users ADD COLUMN lga VARCHAR(255) NULL AFTER state",
    ];
    
    foreach ($requiredColumns as $columnName => $sql) {
        if (!in_array($columnName, $existingColumns)) {
            try {
                DB::statement($sql);
                echo "<p style='color: green;'>✅ Added column: <strong>{$columnName}</strong></p>";
                $columnsAdded++;
            } catch (\Exception $e) {
                echo "<p style='color: red;'>❌ Error adding {$columnName}: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: gray;'>⚪ Column exists: {$columnName}</p>";
        }
    }
    
    echo "<hr>";
    
    if ($columnsAdded > 0) {
        echo "<h3 style='color: green;'>✅ SUCCESS! Added {$columnsAdded} column(s)</h3>";
    } else {
        echo "<h3 style='color: green;'>✅ All columns already exist!</h3>";
    }
    
    // Show final structure
    echo "<br><h3>Final Columns:</h3>";
    $finalColumns = DB::select("SHOW COLUMNS FROM users");
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($finalColumns as $column) {
        $highlight = in_array($column->Field, ['phone', 'address', 'city', 'state', 'country', 'lga']) ? 'background-color: #d1fae5;' : '';
        echo "<tr style='{$highlight}'>";
        echo "<td><strong>" . $column->Field . "</strong></td>";
        echo "<td>" . $column->Type . "</td>";
        echo "<td>" . $column->Null . "</td>";
        echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><br><a href='/farmer/profile' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>✅ Go to Profile</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red; font-size: 18px;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><strong style='color: red; font-size: 18px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</strong>";