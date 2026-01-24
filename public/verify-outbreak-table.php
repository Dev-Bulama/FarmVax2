<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Outbreak Alerts Table Verification</h2>";

try {
    // Check raw SQL
    $columns = DB::select("SHOW COLUMNS FROM outbreak_alerts");
    
    echo "<strong>Columns in outbreak_alerts table:</strong><br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    
    $hasStatus = false;
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "</tr>";
        
        if ($column->Field === 'status') {
            $hasStatus = true;
        }
    }
    echo "</table><br>";
    
    if ($hasStatus) {
        echo "✅ <strong style='color: green;'>Status column EXISTS!</strong><br><br>";
        
        // Test query
        try {
            $count = DB::table('outbreak_alerts')->where('status', 'active')->count();
            echo "✅ Query test successful! Active alerts: {$count}<br>";
        } catch (\Exception $e) {
            echo "❌ Query failed: " . $e->getMessage() . "<br>";
        }
        
    } else {
        echo "❌ <strong style='color: red;'>Status column DOES NOT EXIST!</strong><br>";
        echo "<br>Adding status column now...<br>";
        
        try {
            DB::statement("ALTER TABLE outbreak_alerts ADD COLUMN `status` ENUM('active','contained','resolved') NOT NULL DEFAULT 'active' AFTER outbreak_date");
            echo "✅ Status column added!<br>";
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "<br>";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ <strong style='color: red;'>Error: " . $e->getMessage() . "</strong><br>";
}

echo "<br><strong>Clearing Laravel cache...</strong><br>";

// Clear all caches
try {
    Artisan::call('cache:clear');
    echo "✅ Cache cleared<br>";
    
    Artisan::call('config:clear');
    echo "✅ Config cleared<br>";
    
    Artisan::call('route:clear');
    echo "✅ Routes cleared<br>";
    
    Artisan::call('view:clear');
    echo "✅ Views cleared<br>";
    
} catch (\Exception $e) {
    echo "⚠️ Cache clear: " . $e->getMessage() . "<br>";
}

echo "<br><br><strong>Done! Delete this file and try again.</strong><br>";
echo "<a href='/admin/outbreak-alerts'>Test Outbreak Alerts Page</a>";