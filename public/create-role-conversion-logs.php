<?php
/**
 * Create role_conversion_logs table for tracking user role changes
 * Access via: https://farmvax.com/create-role-conversion-logs.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h1>FarmVax - Create Role Conversion Logs Table</h1>";
echo "<hr>";

try {
    echo "<h3>Step 1: Checking if role_conversion_logs table exists...</h3>";

    // Check if table exists
    if (Schema::hasTable('role_conversion_logs')) {
        echo "<p style='color: orange;'>⚠️ Table 'role_conversion_logs' already exists!</p>";

        // Display current structure
        echo "<h3>Current Table Structure</h3>";
        $columns = DB::select("SHOW COLUMNS FROM role_conversion_logs");

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

    } else {
        echo "<p style='color: orange;'>⚠️ Table 'role_conversion_logs' does not exist. Creating now...</p>";

        // Create table
        DB::statement("
            CREATE TABLE `role_conversion_logs` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned NOT NULL,
                `old_role` varchar(50) NOT NULL,
                `new_role` varchar(50) NOT NULL,
                `converted_by` bigint(20) unsigned NOT NULL,
                `converted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `notes` text DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `role_conversion_logs_user_id_foreign` (`user_id`),
                KEY `role_conversion_logs_converted_by_foreign` (`converted_by`),
                KEY `role_conversion_logs_converted_at_index` (`converted_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        echo "<p style='color: green;'>✅ Table created successfully!</p>";

        // Display structure
        echo "<h3>Step 2: Table Structure</h3>";
        $columns = DB::select("SHOW COLUMNS FROM role_conversion_logs");

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
    }

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ Role Conversion Logs Table Ready!</h2>";
    echo "<p><strong>What this enables:</strong></p>";
    echo "<ul>";
    echo "<li>Admins can now convert users between roles (Farmer ↔ Volunteer ↔ Professional)</li>";
    echo "<li>All role changes are tracked for audit purposes</li>";
    echo "<li>User details remain intact during conversion</li>";
    echo "<li>Users are automatically logged out and redirected to new dashboard</li>";
    echo "</ul>";

    echo "<p><strong>How to use:</strong></p>";
    echo "<ol>";
    echo "<li>Go to: <a href='/admin/users'>Admin → User Management</a></li>";
    echo "<li>Click the <strong>Convert Role</strong> icon (↔️) next to any user</li>";
    echo "<li>Select the new role from the dropdown</li>";
    echo "<li>Confirm the conversion</li>";
    echo "<li>User will be logged out immediately and can login with new role</li>";
    echo "</ol>";

    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>Test the role conversion feature in Admin → User Management</li>";
    echo "<li><strong>Delete this file for security:</strong> public/create-role-conversion-logs.php</li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Error Occurred</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";

    echo "<hr>";
    echo "<h3>Troubleshooting</h3>";
    echo "<ul>";
    echo "<li>Make sure database credentials in .env are correct</li>";
    echo "<li>Ensure MySQL user has CREATE TABLE permissions</li>";
    echo "<li>Check if database exists and is accessible</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
