<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<h2>Running Herd Groups Migration</h2>";
echo "<hr>";

try {
    // Run migration
    Artisan::call('migrate', ['--path' => 'database/migrations/2026_01_03_create_herd_groups_table.php']);
    
    echo "<p style='color: green;'>✅ Migration completed successfully!</p>";
    echo "<pre>" . Artisan::output() . "</pre>";
    
    // Check if tables exist
    $tablesExist = \Illuminate\Support\Facades\Schema::hasTable('herd_groups');
    $columnExists = \Illuminate\Support\Facades\Schema::hasColumn('livestock', 'herd_group_id');
    
    if ($tablesExist) {
        echo "<p style='color: green;'>✅ herd_groups table created</p>";
    }
    
    if ($columnExists) {
        echo "<p style='color: green;'>✅ herd_group_id column added to livestock table</p>";
    }
    
    echo "<br><a href='/farmer/herd-groups' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Go to Herd Groups</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><strong style='color: red;'>DELETE THIS FILE AFTER SUCCESS!</strong>";