<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "<h2>Fixing Bulk Messages Table</h2>";

try {
    Schema::table('bulk_messages', function (Blueprint $table) {
        if (!Schema::hasColumn('bulk_messages', 'recipient_data')) {
            $table->json('recipient_data')->nullable()->after('specific_users');
            echo "✅ Added recipient_data column<br>";
        }
        
        if (!Schema::hasColumn('bulk_messages', 'success_count')) {
            $table->integer('success_count')->default(0)->after('failed_count');
            echo "✅ Added success_count column<br>";
        }
    });
    
    echo "<br>✅ <strong style='color: green;'>Table updated successfully!</strong><br>";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Clear cache
try {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    echo "✅ Cache cleared<br>";
} catch (\Exception $e) {
    echo "⚠️ Cache: " . $e->getMessage() . "<br>";
}

echo "<br><a href='/admin/bulk-messages'>Go to Bulk Messages</a><br>";
echo "<strong>DELETE THIS FILE!</strong>";