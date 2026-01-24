<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "<h2>Complete Bulk Messages Table Fix</h2>";

// First, check what columns exist
try {
    $columns = DB::select("SHOW COLUMNS FROM bulk_messages");
    
    echo "<strong>Existing Columns:</strong><br>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th></tr>";
    
    $existingColumns = [];
    foreach ($columns as $column) {
        echo "<tr><td>{$column->Field}</td><td>{$column->Type}</td></tr>";
        $existingColumns[] = $column->Field;
    }
    echo "</table><br><br>";
    
    // Define all required columns
    $requiredColumns = [
        'id', 'title', 'message', 'type', 'target_roles', 'target_locations',
        'specific_users', 'recipient_data', 'total_recipients', 'sent_count',
        'failed_count', 'success_count', 'status', 'scheduled_at', 'sent_at',
        'created_by', 'created_at', 'updated_at'
    ];
    
    $missingColumns = array_diff($requiredColumns, $existingColumns);
    
    if (!empty($missingColumns)) {
        echo "<strong style='color: orange;'>Missing Columns:</strong><br>";
        echo implode(', ', $missingColumns) . "<br><br>";
        
        echo "<strong>Adding missing columns...</strong><br>";
        
        Schema::table('bulk_messages', function (Blueprint $table) use ($existingColumns) {
            // Add columns in order
            if (!in_array('specific_users', $existingColumns)) {
                $table->json('specific_users')->nullable()->after('target_locations');
                echo "✅ Added specific_users column<br>";
            }
            
            if (!in_array('recipient_data', $existingColumns)) {
                if (in_array('specific_users', $existingColumns)) {
                    $table->json('recipient_data')->nullable()->after('specific_users');
                } else {
                    $table->json('recipient_data')->nullable();
                }
                echo "✅ Added recipient_data column<br>";
            }
            
            if (!in_array('success_count', $existingColumns)) {
                if (in_array('failed_count', $existingColumns)) {
                    $table->integer('success_count')->default(0)->after('failed_count');
                } else {
                    $table->integer('success_count')->default(0);
                }
                echo "✅ Added success_count column<br>";
            }
            
            if (!in_array('sent_count', $existingColumns)) {
                if (in_array('total_recipients', $existingColumns)) {
                    $table->integer('sent_count')->default(0)->after('total_recipients');
                } else {
                    $table->integer('sent_count')->default(0);
                }
                echo "✅ Added sent_count column<br>";
            }
            
            if (!in_array('failed_count', $existingColumns)) {
                if (in_array('sent_count', $existingColumns)) {
                    $table->integer('failed_count')->default(0)->after('sent_count');
                } else {
                    $table->integer('failed_count')->default(0);
                }
                echo "✅ Added failed_count column<br>";
            }
        });
        
        echo "<br>✅ <strong style='color: green;'>All missing columns added!</strong><br>";
    } else {
        echo "✅ <strong style='color: green;'>All required columns already exist!</strong><br>";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Clear cache
try {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    echo "<br>✅ All caches cleared<br>";
} catch (\Exception $e) {
    echo "⚠️ Cache: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/admin/bulk-messages'>Go to Bulk Messages</a><br>";
echo "<strong>DELETE THIS FILE AFTER SUCCESS!</strong>";