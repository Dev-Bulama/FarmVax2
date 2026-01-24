<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Fixing Ads Tables</h2>";

try {
    // Check ad_views table
    $adViewsColumns = DB::select("SHOW COLUMNS FROM ad_views");
    $existingAdViewsCols = array_map(function($col) { return $col->Field; }, $adViewsColumns);
    
    echo "<strong>ad_views columns:</strong> " . implode(', ', $existingAdViewsCols) . "<br><br>";
    
    // Add clicked column if missing
    if (!in_array('clicked', $existingAdViewsCols)) {
        DB::statement("ALTER TABLE ad_views ADD COLUMN clicked TINYINT(1) NOT NULL DEFAULT 0");
        echo "✅ Added 'clicked' column to ad_views<br>";
    }
    
    if (!in_array('clicked_at', $existingAdViewsCols)) {
        DB::statement("ALTER TABLE ad_views ADD COLUMN clicked_at TIMESTAMP NULL");
        echo "✅ Added 'clicked_at' column to ad_views<br>";
    }
    
    // Check ads table
    $adsColumns = DB::select("SHOW COLUMNS FROM ads");
    $existingAdsCols = array_map(function($col) { return $col->Field; }, $adsColumns);
    
    echo "<br><strong>ads columns:</strong> " . implode(', ', $existingAdsCols) . "<br><br>";
    
    // Add missing columns to ads table
    if (!in_array('views_count', $existingAdsCols)) {
        DB::statement("ALTER TABLE ads ADD COLUMN views_count INT(11) NOT NULL DEFAULT 0");
        echo "✅ Added 'views_count' column to ads<br>";
    }
    
    if (!in_array('clicks_count', $existingAdsCols)) {
        DB::statement("ALTER TABLE ads ADD COLUMN clicks_count INT(11) NOT NULL DEFAULT 0");
        echo "✅ Added 'clicks_count' column to ads<br>";
    }
    
    if (!in_array('budget', $existingAdsCols)) {
        DB::statement("ALTER TABLE ads ADD COLUMN budget DECIMAL(10,2) NULL");
        echo "✅ Added 'budget' column to ads<br>";
    }
    
    if (!in_array('cost_per_click', $existingAdsCols)) {
        DB::statement("ALTER TABLE ads ADD COLUMN cost_per_click DECIMAL(10,2) NULL");
        echo "✅ Added 'cost_per_click' column to ads<br>";
    }
    
    echo "<br>✅ <strong style='color: green;'>Ads tables fixed successfully!</strong><br>";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Clear cache
try {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    echo "<br>✅ Caches cleared<br>";
} catch (\Exception $e) {
    echo "⚠️ Cache: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/admin/ads'>Go to Advertisements</a><br>";
echo "<strong>DELETE THIS FILE!</strong>";