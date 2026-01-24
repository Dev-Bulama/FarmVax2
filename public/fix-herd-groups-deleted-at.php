<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Fixing herd_groups Table</h2>";
echo "<hr>";

try {
    // Check if table exists
    if (!Schema::hasTable('herd_groups')) {
        echo "<p style='color: red;'>❌ herd_groups table does not exist!</p>";
        echo "<p>Running migration...</p>";
        
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_01_03_create_herd_groups_table.php'
        ]);
        
        echo "<pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
        echo "<p style='color: green;'>✅ Migration completed!</p>";
    } else {
        echo "<p style='color: green;'>✅ herd_groups table exists</p>";
        
        // Check if deleted_at column exists
        if (!Schema::hasColumn('herd_groups', 'deleted_at')) {
            echo "<p style='color: orange;'>⚠️ deleted_at column missing. Adding it...</p>";
            
            DB::statement('ALTER TABLE herd_groups ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_at');
            
            echo "<p style='color: green;'>✅ deleted_at column added!</p>";
        } else {
            echo "<p style='color: green;'>✅ deleted_at column already exists</p>";
        }
        
        // Check if herd_group_id exists in livestock table
        if (!Schema::hasColumn('livestock', 'herd_group_id')) {
            echo "<p style='color: orange;'>⚠️ herd_group_id column missing in livestock table. Adding it...</p>";
            
            DB::statement('ALTER TABLE livestock ADD COLUMN herd_group_id BIGINT UNSIGNED NULL AFTER user_id');
            DB::statement('ALTER TABLE livestock ADD CONSTRAINT livestock_herd_group_id_foreign FOREIGN KEY (herd_group_id) REFERENCES herd_groups(id) ON DELETE SET NULL');
            
            echo "<p style='color: green;'>✅ herd_group_id column added to livestock table!</p>";
        } else {
            echo "<p style='color: green;'>✅ herd_group_id column exists in livestock table</p>";
        }
    }
    
    // Show table structure
    echo "<br><h3>Current Table Structure:</h3>";
    $columns = DB::select("SHOW COLUMNS FROM herd_groups");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column->Field . "</td>";
        echo "<td>" . $column->Type . "</td>";
        echo "<td>" . $column->Null . "</td>";
        echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><h3 style='color: green;'>✅ ALL FIXES COMPLETE!</h3>";
    echo "<br><a href='/farmer/herd-groups' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Go to Herd Groups</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><strong style='color: red;'>DELETE THIS FILE AFTER SUCCESS!</strong>";