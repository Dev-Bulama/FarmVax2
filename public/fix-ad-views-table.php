<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Fixing ad_views Table</h2>";
echo "<hr>";

try {
    // Check if ad_views table exists
    if (!Schema::hasTable('ad_views')) {
        echo "❌ ad_views table does not exist!<br>";
        echo "Creating ad_views table...<br><br>";
        
        DB::statement("
            CREATE TABLE `ad_views` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `ad_id` BIGINT(20) UNSIGNED NOT NULL,
                `user_id` BIGINT(20) UNSIGNED NULL,
                `ip_address` VARCHAR(45) NULL,
                `user_agent` TEXT NULL,
                `viewed_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                `clicked` TINYINT(1) NOT NULL DEFAULT 0,
                `clicked_at` TIMESTAMP NULL,
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `ad_views_ad_id_foreign` (`ad_id`),
                KEY `ad_views_user_id_foreign` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "✅ ad_views table created successfully!<br>";
    } else {
        echo "✅ ad_views table exists<br><br>";
        
        // Check current columns
        $columns = DB::select("SHOW COLUMNS FROM ad_views");
        $existingColumns = array_map(function($col) { return $col->Field; }, $columns);
        
        echo "<strong>Current columns:</strong><br>";
        echo implode(', ', $existingColumns) . "<br><br>";
        
        // Add missing columns
        $columnsToAdd = [
            'user_agent' => "ALTER TABLE ad_views ADD COLUMN user_agent TEXT NULL",
            'clicked' => "ALTER TABLE ad_views ADD COLUMN clicked TINYINT(1) NOT NULL DEFAULT 0",
            'clicked_at' => "ALTER TABLE ad_views ADD COLUMN clicked_at TIMESTAMP NULL",
        ];
        
        foreach ($columnsToAdd as $column => $sql) {
            if (!in_array($column, $existingColumns)) {
                DB::statement($sql);
                echo "✅ Added column: <strong>$column</strong><br>";
            } else {
                echo "✅ Column already exists: <strong>$column</strong><br>";
            }
        }
    }
    
    echo "<br>✅ <strong style='color: green;'>ad_views table is now complete!</strong><br>";
    
    // Show final structure
    echo "<br><strong>Final table structure:</strong><br>";
    $finalColumns = DB::select("SHOW COLUMNS FROM ad_views");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    foreach ($finalColumns as $col) {
        echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>" . ($col->Default ?? 'NULL') . "</td></tr>";
    }
    echo "</table><br>";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Clear cache
Artisan::call('cache:clear');
Artisan::call('config:clear');
echo "<br>✅ Caches cleared<br>";

echo "<br><a href='/check-ads-display.php'><strong>Run Diagnostic Again</strong></a><br>";
echo "<a href='/farmer/dashboard'><strong>Go to Farmer Dashboard</strong></a><br>";
echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER SUCCESS!</strong>";