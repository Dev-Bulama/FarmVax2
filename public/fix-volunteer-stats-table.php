<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Fixing volunteer_stats Table</h2>";
echo "<hr>";

try {
    // Check if table exists
    if (!Schema::hasTable('volunteer_stats')) {
        echo "<p style='color: orange;'>⚠️ volunteer_stats table doesn't exist. Creating it...</p>";
        
        DB::statement("
            CREATE TABLE volunteer_stats (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                volunteer_id BIGINT UNSIGNED NOT NULL,
                total_enrollments INT NOT NULL DEFAULT 0,
                active_farmers INT NOT NULL DEFAULT 0,
                total_points INT NOT NULL DEFAULT 0,
                current_badge VARCHAR(255) DEFAULT 'bronze',
                rank INT NOT NULL DEFAULT 0,
                badges_earned INT NOT NULL DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (volunteer_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
        
        echo "<p style='color: green;'>✅ volunteer_stats table created!</p>";
    } else {
        echo "<p style='color: green;'>✅ volunteer_stats table exists</p>";
        
        // Show current columns
        $columns = DB::select("SHOW COLUMNS FROM volunteer_stats");
        $existingColumns = array_column($columns, 'Field');
        
        echo "<h3>Current Columns:</h3>";
        echo "<p>" . implode(', ', $existingColumns) . "</p>";
        echo "<hr>";
        
        // Add missing columns
        $columnsToAdd = [
            'total_enrollments' => "ALTER TABLE volunteer_stats ADD COLUMN total_enrollments INT NOT NULL DEFAULT 0 AFTER volunteer_id",
            'active_farmers' => "ALTER TABLE volunteer_stats ADD COLUMN active_farmers INT NOT NULL DEFAULT 0 AFTER total_enrollments",
            'total_points' => "ALTER TABLE volunteer_stats ADD COLUMN total_points INT NOT NULL DEFAULT 0 AFTER active_farmers",
            'current_badge' => "ALTER TABLE volunteer_stats ADD COLUMN current_badge VARCHAR(255) DEFAULT 'bronze' AFTER total_points",
            'rank' => "ALTER TABLE volunteer_stats ADD COLUMN rank INT NOT NULL DEFAULT 0 AFTER current_badge",
            'badges_earned' => "ALTER TABLE volunteer_stats ADD COLUMN badges_earned INT NOT NULL DEFAULT 0 AFTER rank",
        ];
        
        $added = 0;
        foreach ($columnsToAdd as $columnName => $sql) {
            if (!in_array($columnName, $existingColumns)) {
                try {
                    DB::statement($sql);
                    echo "<p style='color: green;'>✅ Added column: <strong>{$columnName}</strong></p>";
                    $added++;
                } catch (\Exception $e) {
                    echo "<p style='color: red;'>❌ Error adding {$columnName}: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p style='color: gray;'>⚪ Column exists: {$columnName}</p>";
            }
        }
        
        if ($added > 0) {
            echo "<br><p style='color: green;'><strong>✅ Added {$added} column(s)</strong></p>";
        } else {
            echo "<br><p style='color: green;'><strong>✅ All columns already exist!</strong></p>";
        }
    }
    
    // Show final table structure
    echo "<br><h3>Final Table Structure:</h3>";
    $finalColumns = DB::select("SHOW COLUMNS FROM volunteer_stats");
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
    
    echo "<br><br><a href='/volunteer/dashboard' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px; font-size: 18px;'>✅ Go to Volunteer Dashboard</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red; font-size: 18px;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><strong style='color: red; font-size: 18px;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</strong>";