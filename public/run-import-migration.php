<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
h1{color:#11455b;border-bottom:3px solid #2fcb6e;padding-bottom:10px;}
.success{background:#d4edda;border-left:4px solid #28a745;padding:15px;margin:10px 0;border-radius:4px;}
.error{background:#f8d7da;border-left:4px solid #dc3545;padding:15px;margin:10px 0;border-radius:4px;}
.info{background:#d1ecf1;border-left:4px solid #17a2b8;padding:15px;margin:10px 0;border-radius:4px;}
.warning{background:#fff3cd;border-left:4px solid #ffc107;padding:15px;margin:10px 0;border-radius:4px;color:#856404;}
pre{background:#f4f4f4;padding:10px;border-radius:4px;overflow-x:auto;}
</style>";

echo "<div class='container'>";
echo "<h1>🚀 User Import System - Database Migration</h1>";

try {
    // Check if tables already exist
    if (Schema::hasTable('user_imports')) {
        echo "<div class='warning'>";
        echo "<strong>⚠️ Tables Already Exist!</strong><br>";
        echo "The user_imports and imported_users tables already exist in your database.";
        echo "</div>";
        
        // Show existing table structure
        echo "<div class='info'>";
        echo "<strong>Existing Tables:</strong>";
        echo "<ul>";
        echo "<li>✓ user_imports</li>";
        echo "<li>✓ imported_users</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='info'><strong>Creating tables...</strong></div>";
        
        // Create user_imports table
        DB::statement("
            CREATE TABLE user_imports (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                imported_by BIGINT UNSIGNED NOT NULL,
                original_filename VARCHAR(255) NOT NULL,
                stored_filename VARCHAR(255) NOT NULL,
                user_type ENUM('farmer', 'volunteer', 'animal_health_professional') DEFAULT 'farmer',
                total_records INT DEFAULT 0,
                successful_imports INT DEFAULT 0,
                failed_imports INT DEFAULT 0,
                duplicate_emails INT DEFAULT 0,
                status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
                column_mapping JSON NULL,
                errors JSON NULL,
                imported_user_ids JSON NULL,
                started_at TIMESTAMP NULL,
                completed_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_imported_by (imported_by),
                INDEX idx_status (status),
                INDEX idx_user_type (user_type),
                INDEX idx_created_at (created_at),
                FOREIGN KEY (imported_by) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "<div class='success'>✓ Created user_imports table</div>";
        
        // Create imported_users table
        DB::statement("
            CREATE TABLE imported_users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                import_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                generated_password VARCHAR(255) NOT NULL,
                welcome_email_sent TINYINT(1) DEFAULT 0,
                welcome_email_sent_at TIMESTAMP NULL,
                email_resend_count INT DEFAULT 0,
                last_email_sent_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_import_id (import_id),
                INDEX idx_user_id (user_id),
                INDEX idx_welcome_email_sent (welcome_email_sent),
                FOREIGN KEY (import_id) REFERENCES user_imports(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "<div class='success'>✓ Created imported_users table</div>";
        
        // Insert migration record
        DB::table('migrations')->insert([
            'migration' => '2026_01_11_000001_create_user_imports_table',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        
        echo "<div class='success'>✓ Migration recorded in migrations table</div>";
    }
    
    // Show table structure
    echo "<h2>📋 Database Structure Created</h2>";
    
    echo "<h3>Table: user_imports</h3>";
    echo "<pre>";
    $columns = DB::select("DESCRIBE user_imports");
    foreach ($columns as $column) {
        echo sprintf("%-25s %-20s %s\n", $column->Field, $column->Type, $column->Null);
    }
    echo "</pre>";
    
    echo "<h3>Table: imported_users</h3>";
    echo "<pre>";
    $columns = DB::select("DESCRIBE imported_users");
    foreach ($columns as $column) {
        echo sprintf("%-25s %-20s %s\n", $column->Field, $column->Type, $column->Null);
    }
    echo "</pre>";
    
    echo "<div class='success'>";
    echo "<h2>✅ SUCCESS!</h2>";
    echo "<p><strong>Database tables created successfully!</strong></p>";
    echo "<p>The user import system is now ready.</p>";
    echo "</div>";
    
    echo "<div class='warning'>";
    echo "<h3>⚠️ IMPORTANT - DELETE THIS FILE NOW!</h3>";
    echo "<p>For security reasons, please delete this file immediately:</p>";
    echo "<pre>public/run-import-migration.php</pre>";
    echo "<p>You can delete it via File Manager in hPanel.</p>";
    echo "</div>";
    
} catch (\Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ ERROR</h3>";
    echo "<p><strong>Migration failed:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

echo "</div>";