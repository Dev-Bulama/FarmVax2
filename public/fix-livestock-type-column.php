<?php
/**
 * Fix: livestock.type column missing DEFAULT value
 *
 * This script alters the `type` column to either:
 *   1. Be nullable (safest - works with old AND new code)
 *   2. Add a DEFAULT '' (empty string fallback)
 *   3. Add a BEFORE INSERT trigger to copy livestock_type -> type
 *
 * Upload to /public/ and visit in browser.
 * DELETE THIS FILE after running.
 */

// Simple password protection
$password = 'FarmVax2025Fix';
if (!isset($_GET['key']) || $_GET['key'] !== $password) {
    http_response_code(403);
    die('<h2>Access denied.</h2><p>Append <code>?key=' . $password . '</code> to the URL.</p>');
}

$action = $_GET['action'] ?? 'info';

// Bootstrap Laravel to get DB config
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$dbConfig = $app['config']['database.connections'][$app['config']['database.default']];
$host     = $dbConfig['host'] ?? 'localhost';
$port     = $dbConfig['port'] ?? 3306;
$dbname   = $dbConfig['database'];
$username = $dbConfig['username'];
$db_pass  = $dbConfig['password'];

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('<p style="color:red">DB connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>');
}

// Get current column info
$colInfo = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
$allCols = $pdo->query("SHOW COLUMNS FROM `livestock`")->fetchAll(PDO::FETCH_ASSOC);
$triggerInfo = $pdo->query("SHOW TRIGGERS LIKE 'livestock'")->fetchAll(PDO::FETCH_ASSOC);

$messages = [];
$errors   = [];

if ($action === 'make_nullable') {
    try {
        // Get current column type first
        $colType = $colInfo['Type'] ?? 'varchar(255)';
        $pdo->exec("ALTER TABLE `livestock` MODIFY COLUMN `type` $colType NULL DEFAULT NULL");
        $messages[] = "SUCCESS: `type` column is now nullable. Inserts without `type` will store NULL.";
        // Refresh col info
        $colInfo = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $errors[] = 'ALTER failed: ' . $e->getMessage();
    }
}

if ($action === 'add_trigger') {
    try {
        // Drop existing trigger if any
        $pdo->exec("DROP TRIGGER IF EXISTS `set_livestock_type_before_insert`");
        $pdo->exec("
            CREATE TRIGGER `set_livestock_type_before_insert`
            BEFORE INSERT ON `livestock`
            FOR EACH ROW
            BEGIN
                IF NEW.type IS NULL OR NEW.type = '' THEN
                    SET NEW.type = NEW.livestock_type;
                END IF;
            END
        ");
        $messages[] = "SUCCESS: Trigger created. `type` will auto-copy from `livestock_type` on every INSERT.";
        $triggerInfo = $pdo->query("SHOW TRIGGERS LIKE 'livestock'")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $errors[] = 'Trigger creation failed: ' . $e->getMessage();
    }
}

if ($action === 'backfill') {
    try {
        $stmt = $pdo->exec("UPDATE `livestock` SET `type` = `livestock_type` WHERE `type` IS NULL OR `type` = ''");
        $messages[] = "SUCCESS: Backfilled `type` from `livestock_type` for $stmt rows.";
    } catch (Exception $e) {
        $errors[] = 'Backfill failed: ' . $e->getMessage();
    }
}

$base = '?key=' . $password;
?>
<!DOCTYPE html>
<html>
<head>
<title>Fix livestock.type column</title>
<style>
body { font-family: monospace; background: #111; color: #eee; padding: 30px; }
h1 { color: #f90; }
h2 { color: #7cf; }
table { border-collapse: collapse; width: 100%; margin: 10px 0; }
th, td { border: 1px solid #444; padding: 8px 12px; text-align: left; }
th { background: #222; color: #adf; }
tr:nth-child(even) { background: #1a1a1a; }
.success { background: #1a3a1a; border: 1px solid #4a4; color: #8f8; padding: 12px; margin: 10px 0; border-radius: 4px; }
.error   { background: #3a1a1a; border: 1px solid #a44; color: #f88; padding: 12px; margin: 10px 0; border-radius: 4px; }
.warning { background: #3a3a1a; border: 1px solid #aa4; color: #ff8; padding: 12px; margin: 10px 0; border-radius: 4px; }
.btn { display: inline-block; background: #0055aa; color: #fff; padding: 10px 20px; margin: 6px 4px;
       border-radius: 4px; text-decoration: none; font-weight: bold; }
.btn:hover { background: #0077ee; }
.btn-green { background: #006622; }
.btn-green:hover { background: #009933; }
.btn-red { background: #660000; }
.btn-red:hover { background: #990000; }
</style>
</head>
<body>

<h1>FarmVax — Fix <code>livestock.type</code> Column</h1>

<?php foreach ($messages as $m): ?>
<div class="success"><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>
<?php foreach ($errors as $e): ?>
<div class="error"><?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<h2>Current state of <code>type</code> column</h2>
<?php if ($colInfo): ?>
<table>
<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>
<tr>
  <td><?= $colInfo['Field'] ?></td>
  <td><?= $colInfo['Type'] ?></td>
  <td><?= $colInfo['Null'] ?></td>
  <td><?= $colInfo['Key'] ?></td>
  <td><?= $colInfo['Default'] ?? '<em>none</em>' ?></td>
  <td><?= $colInfo['Extra'] ?></td>
</tr>
</table>
<?php if ($colInfo['Null'] === 'NO' && $colInfo['Default'] === null): ?>
<div class="warning">
  <strong>PROBLEM:</strong> Column is NOT NULL with no default — every INSERT that omits this column will fail with SQLSTATE 1364.
</div>
<?php elseif ($colInfo['Null'] === 'YES'): ?>
<div class="success">Column is nullable — INSERTs without `type` will work (store NULL).</div>
<?php endif; ?>
<?php else: ?>
<div class="warning"><strong>`type` column does not exist on this server.</strong> The production DB schema differs from local. The error may be a different column.</div>
<?php endif; ?>

<h2>Active triggers on <code>livestock</code></h2>
<?php if ($triggerInfo): ?>
<table>
<tr><th>Trigger</th><th>Event</th><th>Timing</th><th>Statement</th></tr>
<?php foreach ($triggerInfo as $t): ?>
<tr>
  <td><?= htmlspecialchars($t['Trigger']) ?></td>
  <td><?= htmlspecialchars($t['Event']) ?></td>
  <td><?= htmlspecialchars($t['Timing']) ?></td>
  <td><pre style="margin:0;font-size:11px"><?= htmlspecialchars(substr($t['Statement'], 0, 200)) ?></pre></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No triggers on <code>livestock</code>.</p>
<?php endif; ?>

<h2>Fix Options</h2>
<p>Choose the fix that best suits your situation:</p>

<a class="btn btn-green" href="<?= $base ?>&action=make_nullable">
  Option 1: Make `type` nullable
</a>
<br><small style="color:#aaa;margin-left:4px">Safest. Alters column to allow NULL. Works with old and new code. Recommended.</small>

<br><br>
<a class="btn" href="<?= $base ?>&action=add_trigger">
  Option 2: Add auto-copy trigger
</a>
<br><small style="color:#aaa;margin-left:4px">Creates a BEFORE INSERT trigger that copies livestock_type → type automatically. Column stays NOT NULL.</small>

<br><br>
<a class="btn" href="<?= $base ?>&action=backfill">
  Option 3: Backfill existing NULLs
</a>
<br><small style="color:#aaa;margin-left:4px">Run after Option 1 or 2 to fill any NULL `type` values from `livestock_type`.</small>

<h2>All <code>livestock</code> columns (for reference)</h2>
<table>
<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th><th>Extra</th></tr>
<?php foreach ($allCols as $c): ?>
<tr<?= $c['Field'] === 'type' ? ' style="background:#2a2a00;color:#ff8"' : '' ?>>
  <td><?= htmlspecialchars($c['Field']) ?></td>
  <td><?= htmlspecialchars($c['Type']) ?></td>
  <td><?= htmlspecialchars($c['Null']) ?></td>
  <td><?= htmlspecialchars($c['Default'] ?? 'NULL') ?></td>
  <td><?= htmlspecialchars($c['Extra']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<hr style="border-color:#444;margin-top:40px">
<p style="color:#666">Delete <code>public/fix-livestock-type-column.php</code> after use.</p>
</body>
</html>
