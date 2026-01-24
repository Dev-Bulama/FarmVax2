<?php
/**
 * Create system_versions table for update management
 * Access via: https://farmvax.com/create-system-versions-table.php
 */

// Include Laravel bootstrap
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "<h1>FarmVax - Create System Versions Table</h1>";
echo "<hr>";

try {
    // Check if table already exists
    if (Schema::hasTable('system_versions')) {
        echo "<p style='color: orange;'>⚠️ Table 'system_versions' already exists. Skipping creation.</p>";
    } else {
        // Create system_versions table
        Schema::create('system_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 20); // e.g., "2.0.1"
            $table->string('release_name', 100)->nullable(); // e.g., "FarmVax Production Update"
            $table->text('description')->nullable();
            $table->text('changelog')->nullable(); // Detailed changes
            $table->string('update_file_path')->nullable(); // Path to ZIP file
            $table->string('update_file_size')->nullable();
            $table->enum('status', ['pending', 'applied', 'failed', 'rolled_back'])->default('pending');
            $table->timestamp('applied_at')->nullable();
            $table->unsignedBigInteger('applied_by')->nullable();
            $table->text('error_log')->nullable();
            $table->boolean('requires_migration')->default(false);
            $table->boolean('requires_cache_clear')->default(true);
            $table->boolean('requires_restart')->default(false);
            $table->json('backup_info')->nullable(); // Info about backups created
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            // Add indexes
            $table->index('is_current');
            $table->index('status');

            // Add foreign key constraint
            $table->foreign('applied_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });

        echo "<p style='color: green;'>✅ Table 'system_versions' created successfully!</p>";

        // Insert initial version
        DB::table('system_versions')->insert([
            'version' => '1.0.0',
            'release_name' => 'FarmVax Initial Release',
            'description' => 'Initial production system',
            'status' => 'applied',
            'applied_at' => now(),
            'is_current' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "<p style='color: green;'>✅ Initial version record (v1.0.0) created successfully!</p>";
    }

    // Verify table structure
    echo "<h3>Table Structure:</h3>";
    $columns = DB::select("SHOW COLUMNS FROM system_versions");
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Show existing records
    echo "<h3>Existing Records:</h3>";
    $records = DB::table('system_versions')->get();
    if ($records->count() > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Version</th><th>Release Name</th><th>Status</th><th>Current</th><th>Applied At</th></tr>";
        foreach ($records as $record) {
            echo "<tr>";
            echo "<td>{$record->id}</td>";
            echo "<td>{$record->version}</td>";
            echo "<td>{$record->release_name}</td>";
            echo "<td>{$record->status}</td>";
            echo "<td>" . ($record->is_current ? 'Yes' : 'No') . "</td>";
            echo "<td>{$record->applied_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found.</p>";
    }

    echo "<hr>";
    echo "<h3 style='color: green;'>✅ Setup Complete!</h3>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>You can now access System Updates in Admin Panel → System Updates</li>";
    echo "<li>Delete this file for security: <code>public/create-system-versions-table.php</code></li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><a href='/admin/system-updates'>Go to System Updates</a> | <a href='/admin/dashboard'>Go to Admin Dashboard</a></p>";
echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
