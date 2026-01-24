<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "<h2>Adding Missing Columns to outbreak_alerts</h2>";

try {
    Schema::table('outbreak_alerts', function (Blueprint $table) {
        if (!Schema::hasColumn('outbreak_alerts', 'confirmed_cases')) {
            $table->integer('confirmed_cases')->default(0)->after('symptoms');
            echo "✅ Added confirmed_cases column<br>";
        }
        
        if (!Schema::hasColumn('outbreak_alerts', 'deaths')) {
            $table->integer('deaths')->default(0)->after('confirmed_cases');
            echo "✅ Added deaths column<br>";
        }
        
        if (!Schema::hasColumn('outbreak_alerts', 'outbreak_date')) {
            $table->date('outbreak_date')->nullable()->after('deaths');
            echo "✅ Added outbreak_date column<br>";
        }
        
        if (!Schema::hasColumn('outbreak_alerts', 'radius_km')) {
            $table->integer('radius_km')->default(50)->after('lga_id');
            echo "✅ Added radius_km column<br>";
        }
    });
    
    echo "<br>✅ <strong style='color: green;'>All columns added successfully!</strong><br>";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/admin/outbreak-alerts'>Go to Outbreak Alerts</a><br>";
echo "<strong>DELETE THIS FILE!</strong>";