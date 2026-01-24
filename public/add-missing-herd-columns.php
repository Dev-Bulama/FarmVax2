<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Adding Missing Columns to herd_groups Table</h2>";
echo "<hr>";

try {
    // Check current columns
    $columns = DB::select("SHOW COLUMNS FROM herd_groups");
    $existingColumns = [];
    
    foreach ($columns as $column) {
        $existingColumns[] = $column->Field;
    }
    
    echo "<h3>Current Columns:</h3>";
    echo "<p>" . implode(', ', $existingColumns) . "</p>";
    echo "<hr>";
    
    $columnsAdded = 0;
    
    // Add missing columns one by one
    $requiredColumns = [
        'type' => "ALTER TABLE herd_groups ADD COLUMN type VARCHAR(255) NULL AFTER name",
        'description' => "ALTER TABLE herd_groups ADD COLUMN description TEXT NULL AFTER type",
        'purpose' => "ALTER TABLE herd_groups ADD COLUMN purpose VARCHAR(255) NULL AFTER description",
        'total_count' => "ALTER TABLE herd_groups ADD COLUMN total_count INT NOT NULL DEFAULT 0 AFTER purpose",
        'healthy_count' => "ALTER TABLE herd_groups ADD COLUMN healthy_count INT NOT NULL DEFAULT 0 AFTER total_count",
        'sick_count' => "ALTER TABLE herd_groups ADD COLUMN sick_count INT NOT NULL DEFAULT 0 AFTER healthy_count",
        'location' => "ALTER TABLE herd_groups ADD COLUMN location VARCHAR(255) NULL AFTER sick_count",
        'color_code' => "ALTER TABLE herd_groups ADD COLUMN color_code VARCHAR(255) NOT NULL DEFAULT '#2fcb6e' AFTER location",
        'is_active' => "ALTER TABLE herd_groups ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER color_code",
        'deleted_at' => "ALTER TABLE herd_groups ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_at",
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
    
    // Check livestock table for herd_group_id
    if (Schema::hasTable('livestock')) {
        $livestockColumns = DB::select("SHOW COLUMNS FROM livestock");
        $livestockColumnNames = array_column($livestockColumns, 'Field');
        
        if (!in_array('herd_group_id', $livestockColumnNames)) {
            echo "<p style='color: orange;'>⚠️ Adding herd_group_id to livestock table...</p>";
            
            DB::statement('ALTER TABLE livestock ADD COLUMN herd_group_id BIGINT UNSIGNED NULL AFTER user_id');
            
            // Add foreign key
            try {
                DB::statement('ALTER TABLE livestock ADD CONSTRAINT livestock_herd_group_id_foreign FOREIGN KEY (herd_group_id) REFERENCES herd_groups(id) ON DELETE SET NULL');
                echo "<p style='color: green;'>✅ herd_group_id column and foreign key added to livestock table!</p>";
            } catch (\Exception $e) {
                echo "<p style='color: green;'>✅ herd_group_id column added (foreign key may already exist)</p>";
            }
            
            $columnsAdded++;
        } else {
            echo "<p style='color: green;'>✅ herd_group_id already exists in livestock table</p>";
        }
    }
    
    echo "<hr>";
    
    if ($columnsAdded > 0) {
        echo "<h3 style='color: green;'>✅ SUCCESS! Added {$columnsAdded} column(s)</h3>";
    } else {
        echo "<h3 style='color: green;'>✅ All columns already exist!</h3>";
    }
    
    // Show final structure
    echo "<br><h3>Final Table Structure:</h3>";
    $finalColumns = DB::select("SHOW COLUMNS FROM herd_groups");
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($finalColumns as $column) {
        echo "<tr>";
        echo "<td><strong>" . $column->Field . "</strong></td>";
        echo "<td>" . $column->Type . "</td>";
        echo "<td>" . $column->Null . "</td>";
        echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><br><a href='/farmer/herd-groups' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>✅ Go to Herd Groups</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red; font-size: 18px;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><strong style='color: red; font-size: 18px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</strong>";