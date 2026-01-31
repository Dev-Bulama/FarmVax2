<?php
// Handle AJAX requests first
if (isset($_GET['action'])) {
    require_once __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    header('Content-Type: application/json');

    $action = $_GET['action'];
    $logs = [];

    function addLog($message) {
        global $logs;
        $logs[] = date('H:i:s') . ' - ' . $message;
    }

    if ($action === 'check') {
        try {
            addLog('Checking database schema...');

            $columns = DB::select("SHOW COLUMNS FROM chatbot_conversations");
            $columnNames = array_column($columns, 'Field');

            addLog('Current columns: ' . implode(', ', $columnNames));

            $requiredColumns = [
                'human_requested',
                'human_requested_at',
                'human_takeover',
                'human_takeover_at',
                'handled_by_admin_id',
                'notification_sent'
            ];

            $missingColumns = array_diff($requiredColumns, $columnNames);

            if (empty($missingColumns)) {
                addLog('✅ All required columns exist');
                echo json_encode([
                    'needs_migration' => false,
                    'logs' => $logs
                ]);
            } else {
                addLog('⚠️ Missing columns: ' . implode(', ', $missingColumns));
                echo json_encode([
                    'needs_migration' => true,
                    'missing_columns' => $missingColumns,
                    'logs' => $logs
                ]);
            }
        } catch (Exception $e) {
            addLog('❌ Error: ' . $e->getMessage());
            echo json_encode([
                'needs_migration' => true,
                'error' => $e->getMessage(),
                'logs' => $logs
            ]);
        }
    } elseif ($action === 'migrate') {
        try {
            addLog('Starting migration...');

            // Check if columns exist before adding
            $existingColumns = DB::select("SHOW COLUMNS FROM chatbot_conversations");
            $existingColumnNames = array_column($existingColumns, 'Field');

            // Add columns only if they don't exist
            if (!in_array('human_requested', $existingColumnNames)) {
                addLog('Adding human_requested column...');
                DB::statement('ALTER TABLE chatbot_conversations ADD COLUMN human_requested TINYINT(1) DEFAULT 0 AFTER is_active');
            } else {
                addLog('human_requested column already exists, skipping...');
            }

            if (!in_array('human_requested_at', $existingColumnNames)) {
                addLog('Adding human_requested_at column...');
                DB::statement('ALTER TABLE chatbot_conversations ADD COLUMN human_requested_at TIMESTAMP NULL AFTER human_requested');
            } else {
                addLog('human_requested_at column already exists, skipping...');
            }

            if (!in_array('human_takeover', $existingColumnNames)) {
                addLog('Adding human_takeover column...');
                DB::statement('ALTER TABLE chatbot_conversations ADD COLUMN human_takeover TINYINT(1) DEFAULT 0 AFTER human_requested_at');
            } else {
                addLog('human_takeover column already exists, skipping...');
            }

            if (!in_array('human_takeover_at', $existingColumnNames)) {
                addLog('Adding human_takeover_at column...');
                DB::statement('ALTER TABLE chatbot_conversations ADD COLUMN human_takeover_at TIMESTAMP NULL AFTER human_takeover');
            } else {
                addLog('human_takeover_at column already exists, skipping...');
            }

            if (!in_array('handled_by_admin_id', $existingColumnNames)) {
                addLog('Adding handled_by_admin_id column...');
                DB::statement('ALTER TABLE chatbot_conversations ADD COLUMN handled_by_admin_id BIGINT UNSIGNED NULL AFTER human_takeover_at');
            } else {
                addLog('handled_by_admin_id column already exists, skipping...');
            }

            if (!in_array('notification_sent', $existingColumnNames)) {
                addLog('Adding notification_sent column...');
                DB::statement('ALTER TABLE chatbot_conversations ADD COLUMN notification_sent TINYINT(1) DEFAULT 0 AFTER handled_by_admin_id');
            } else {
                addLog('notification_sent column already exists, skipping...');
            }

            // Add foreign key constraint
            addLog('Checking for foreign key constraint...');
            try {
                // Check if constraint already exists
                $constraints = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'chatbot_conversations' AND CONSTRAINT_NAME = 'fk_handled_by_admin'");

                if (empty($constraints)) {
                    addLog('Adding foreign key constraint...');
                    DB::statement('ALTER TABLE chatbot_conversations ADD CONSTRAINT fk_handled_by_admin FOREIGN KEY (handled_by_admin_id) REFERENCES users(id) ON DELETE SET NULL');
                } else {
                    addLog('Foreign key constraint already exists, skipping...');
                }
            } catch (Exception $e) {
                addLog('Foreign key error (might already exist): ' . $e->getMessage());
            }

            // Modify chatbot_messages sender_type enum
            addLog('Updating chatbot_messages sender_type to include admin...');
            try {
                DB::statement("ALTER TABLE chatbot_messages MODIFY sender_type ENUM('user', 'bot', 'admin') DEFAULT 'user'");
                addLog('sender_type updated successfully');
            } catch (Exception $e) {
                addLog('Enum modification note: ' . $e->getMessage());
            }

            addLog('✅ Migration completed successfully!');

            echo json_encode([
                'success' => true,
                'message' => 'Database migration completed successfully. All columns added.',
                'logs' => $logs
            ]);
        } catch (Exception $e) {
            addLog('❌ Migration failed: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'logs' => $logs
            ]);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmVax Migration Tool</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #11455B 0%, #0d3345 100%); color: white; padding: 30px; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 24px; margin-bottom: 10px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .status-box { padding: 15px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid; }
        .status-box.info { background: #e3f2fd; border-color: #2196F3; color: #1976D2; }
        .status-box.success { background: #e8f5e9; border-color: #4CAF50; color: #388E3C; }
        .status-box.error { background: #ffebee; border-color: #f44336; color: #c62828; }
        .status-box.warning { background: #fff3e0; border-color: #ff9800; color: #e65100; }
        button { background: #11455B; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.3s; }
        button:hover { background: #0d3345; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        button:disabled { background: #ccc; cursor: not-allowed; transform: none; }
        .log { background: #263238; color: #00FF00; padding: 15px; border-radius: 6px; font-family: 'Courier New', monospace; font-size: 13px; max-height: 400px; overflow-y: auto; margin-top: 20px; }
        .log-line { margin-bottom: 5px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 FarmVax Database Migration Tool</h1>
            <p>Chatbot Human Takeover Migration</p>
        </div>

        <div class="content">
            <div class="status-box info">
                <strong>ℹ️ What This Does:</strong><br>
                This tool will add the necessary columns to your database for the chatbot human takeover feature:
                <ul style="margin-top: 10px; margin-left: 20px;">
                    <li>human_requested (boolean)</li>
                    <li>human_requested_at (timestamp)</li>
                    <li>human_takeover (boolean)</li>
                    <li>human_takeover_at (timestamp)</li>
                    <li>handled_by_admin_id (foreign key)</li>
                    <li>notification_sent (boolean)</li>
                    <li>Updates chatbot_messages to support 'admin' sender type</li>
                </ul>
            </div>

            <button onclick="checkStatus()" id="checkBtn">Check Database Status</button>
            <button onclick="runMigration()" id="migrateBtn" style="background: #2FCB6E; margin-left: 10px;">Run Migration</button>

            <div id="output"></div>
        </div>
    </div>

    <script>
        async function checkStatus() {
            const btn = document.getElementById('checkBtn');
            const output = document.getElementById('output');

            btn.disabled = true;
            btn.textContent = 'Checking...';

            output.innerHTML = '<div class="status-box info">Checking database...</div>';

            try {
                const response = await fetch('?action=check');
                const data = await response.json();

                let html = '<div class="log">';
                data.logs.forEach(log => {
                    html += `<div class="log-line">${log}</div>`;
                });
                html += '</div>';

                if (data.needs_migration) {
                    html = '<div class="status-box warning"><strong>⚠️ Migration Needed</strong><br>The database is missing required columns. Click "Run Migration" to fix this.</div>' + html;
                } else {
                    html = '<div class="status-box success"><strong>✅ Database is Up to Date</strong><br>All required columns exist. No migration needed!</div>' + html;
                }

                output.innerHTML = html;
            } catch (error) {
                output.innerHTML = '<div class="status-box error"><strong>❌ Error</strong><br>' + error.message + '</div>';
            }

            btn.disabled = false;
            btn.textContent = 'Check Database Status';
        }

        async function runMigration() {
            if (!confirm('This will modify your database. Are you sure you want to continue?')) {
                return;
            }

            const btn = document.getElementById('migrateBtn');
            const output = document.getElementById('output');

            btn.disabled = true;
            btn.textContent = 'Running Migration...';

            output.innerHTML = '<div class="status-box info">Running migration... Please wait.</div>';

            try {
                const response = await fetch('?action=migrate');
                const data = await response.json();

                let html = '<div class="log">';
                data.logs.forEach(log => {
                    html += `<div class="log-line">${log}</div>`;
                });
                html += '</div>';

                if (data.success) {
                    html = '<div class="status-box success"><strong>✅ Migration Successful!</strong><br>' + data.message + '</div>' + html;
                } else {
                    html = '<div class="status-box error"><strong>❌ Migration Failed</strong><br>' + data.message + '</div>' + html;
                }

                output.innerHTML = html;
            } catch (error) {
                output.innerHTML = '<div class="status-box error"><strong>❌ Error</strong><br>' + error.message + '</div>';
            }

            btn.disabled = false;
            btn.textContent = 'Run Migration';
        }

        // Auto-check on load
        window.addEventListener('load', checkStatus);
    </script>
</body>
</html>
