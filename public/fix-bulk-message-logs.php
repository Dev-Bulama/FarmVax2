<?php
/**
 * Fix bulk_message_logs table - Add missing 'channel' column
 * Access via: https://farmvax.com/fix-bulk-message-logs.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h1>FarmVax - Fix Bulk Message Logs Table</h1>";
echo "<hr>";

try {
    echo "<h3>Step 1: Checking bulk_message_logs table structure...</h3>";

    // Check if table exists
    if (!Schema::hasTable('bulk_message_logs')) {
        echo "<p style='color: red;'>❌ Table 'bulk_message_logs' does not exist!</p>";
        echo "<p>Creating table...</p>";

        // Create table with all required columns
        DB::statement("
            CREATE TABLE `bulk_message_logs` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `bulk_message_id` bigint(20) unsigned NOT NULL,
                `user_id` bigint(20) unsigned DEFAULT NULL,
                `recipient` varchar(255) DEFAULT NULL,
                `channel` enum('sms','email') NOT NULL DEFAULT 'sms',
                `status` enum('pending','sent','failed','delivered') NOT NULL DEFAULT 'pending',
                `error_message` text DEFAULT NULL,
                `sent_at` timestamp NULL DEFAULT NULL,
                `delivered_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `bulk_message_logs_bulk_message_id_foreign` (`bulk_message_id`),
                KEY `bulk_message_logs_user_id_foreign` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        echo "<p style='color: green;'>✅ Table created successfully!</p>";
    } else {
        echo "<p style='color: green;'>✅ Table exists</p>";

        // Check if 'channel' column exists
        echo "<h3>Step 2: Checking for 'channel' column...</h3>";
        $columns = DB::select("SHOW COLUMNS FROM bulk_message_logs LIKE 'channel'");

        if (empty($columns)) {
            echo "<p style='color: orange;'>⚠️ Column 'channel' is missing. Adding it now...</p>";

            // Add channel column
            DB::statement("
                ALTER TABLE `bulk_message_logs`
                ADD COLUMN `channel` enum('sms','email') NOT NULL DEFAULT 'sms' AFTER `user_id`
            ");

            echo "<p style='color: green;'>✅ Column 'channel' added successfully!</p>";
        } else {
            echo "<p style='color: green;'>✅ Column 'channel' already exists</p>";
        }

        // Check if 'recipient' column exists
        echo "<h3>Step 3: Checking for 'recipient' column...</h3>";
        $recipientColumns = DB::select("SHOW COLUMNS FROM bulk_message_logs LIKE 'recipient'");

        if (empty($recipientColumns)) {
            echo "<p style='color: orange;'>⚠️ Column 'recipient' is missing. Adding it now...</p>";

            // Add recipient column
            DB::statement("
                ALTER TABLE `bulk_message_logs`
                ADD COLUMN `recipient` varchar(255) DEFAULT NULL AFTER `user_id`
            ");

            echo "<p style='color: green;'>✅ Column 'recipient' added successfully!</p>";
        } else {
            echo "<p style='color: green;'>✅ Column 'recipient' already exists</p>";
        }
    }

    // Display current table structure
    echo "<h3>Step 4: Current Table Structure</h3>";
    $columns = DB::select("SHOW COLUMNS FROM bulk_message_logs");

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

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Bulk Message Logs Table Fixed!</h2>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>SMS and Email bulk messaging should now work correctly</li>";
    echo "<li>Try sending a test bulk message from: <a href='/admin/bulk-messages/create'>Admin → Bulk Messages</a></li>";
    echo "<li><strong>Delete this file for security:</strong> public/fix-bulk-message-logs.php</li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Error Occurred</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
