<?php
/**
 * Fix: livestock.type column missing DEFAULT value
 * Reads .env directly - no Laravel bootstrap needed.
 * DELETE THIS FILE after running.
 */

$password = 'FarmVax2025Fix';
if (!isset($_GET['key']) || $_GET['key'] !== $password) {
    http_response_code(403);
    die('<h2>Access denied.</h2><p>Append <code>?key=' . $password . '</code> to the URL.</p>');
}

// Parse .env file
function parseEnv($path) {
    $vars = [];
    if (!file_exists($path)) return $vars;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') === false) continue;
        [$key, $val] = explode('=', $line, 2);
        $val = trim($val, " \t\n\r\0\x0B\"'");
        $vars[trim($key)] = $val;
    }
    return $vars;
}

$envPath = __DIR__ . '/../.env';
$env = parseEnv($envPath);

$host   = $env['DB_HOST']     ?? '127.0.0.1';
$port   = $env['DB_PORT']     ?? '3306';
$dbname = $env['DB_DATABASE'] ?? '';
$user   = $env['DB_USERNAME'] ?? '';
$pass   = $env['DB_PASSWORD'] ?? '';

if (!$dbname) {
    die('<p style="color:red">Could not read DB config from .env at: ' . htmlspecialchars($envPath) . '</p>');
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('<p style="color:red">DB connection failed: ' . htmlspecialchars($e->getMessage()) . '</p>');
}

$action   = $_GET['action'] ?? 'info';
$messages = [];
$errors   = [];

if ($action === 'make_nullable') {
    try {
        $col = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
        $colType = $col['Type'] ?? 'varchar(255)';
        $pdo->exec("ALTER TABLE `livestock` MODIFY COLUMN `type` $colType NULL DEFAULT NULL");
        $messages[] = "SUCCESS: `type` column is now nullable. INSERTs that omit `type` will store NULL instead of failing.";
    } catch (Exception $e) {
        $errors[] = 'ALTER failed: ' . $e->getMessage();
    }
}

if ($action === 'add_trigger') {
    try {
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
    } catch (Exception $e) {
        $errors[] = 'Trigger creation failed: ' . $e->getMessage();
    }
}

if ($action === 'backfill') {
    try {
        $count = $pdo->exec("UPDATE `livestock` SET `type` = `livestock_type` WHERE `type` IS NULL OR `type` = ''");
        $messages[] = "SUCCESS: Backfilled `type` from `livestock_type` for {$count} rows.";
    } catch (Exception $e) {
        $errors[] = 'Backfill failed: ' . $e->getMessage();
    }
}

// Refresh column info after action
$colInfo     = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
$allCols     = $pdo->query("SHOW COLUMNS FROM `livestock`")->fetchAll(PDO::FETCH_ASSOC);
$triggerInfo = $pdo->query("SHOW TRIGGERS WHERE `Table` = 'livestock'")->fetchAll(PDO::FETCH_ASSOC);

$base = '?key=' . $password;
?>
<!DOCTYPE html>
<html>
<head>
<title>Fix livestock.type column</title>
<style>
body  { font-family: monospace; background:#111; color:#eee; padding:30px; }
h1    { color:#f90; }
h2    { color:#7cf; }
table { border-collapse:collapse; width:100%; margin:10px 0; }
th,td { border:1px solid #444; padding:8px 12px; text-align:left; }
th    { background:#222; color:#adf; }
tr:nth-child(even) { background:#1a1a1a; }
.ok   { background:#1a3a1a; border:1px solid #4a4; color:#8f8; padding:12px; margin:10px 0; border-radius:4px; }
.err  { background:#3a1a1a; border:1px solid #a44; color:#f88; padding:12px; margin:10px 0; border-radius:4px; }
.warn { background:#3a3a1a; border:1px solid #aa4; color:#ff8; padding:12px; margin:10px 0; border-radius:4px; }
.btn  { display:inline-block; background:#0055aa; color:#fff; padding:10px 20px; margin:6px 4px;
        border-radius:4px; text-decoration:none; font-weight:bold; }
.btn:hover   { background:#0077ee; }
.btn-green   { background:#006622; } .btn-green:hover  { background:#009933; }
.btn-orange  { background:#885500; } .btn-orange:hover { background:#bb7700; }
</style>
</head>
<body>
<h1>FarmVax — Fix <code>livestock.type</code> Column</h1>
<p>Connected to: <strong><?= htmlspecialchars($dbname) ?></strong> on <?= htmlspecialchars($host) ?>:<?= htmlspecialchars($port) ?></p>

<?php foreach ($messages as $m): ?>
<div class="ok"><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>
<?php foreach ($errors as $e): ?>
<div class="err"><?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<h2>Current state of <code>type</code> column</h2>
<?php if ($colInfo): ?>
<table>
<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>
<tr>
  <td><?= htmlspecialchars($colInfo['Field']) ?></td>
  <td><?= htmlspecialchars($colInfo['Type']) ?></td>
  <td><?= htmlspecialchars($colInfo['Null']) ?></td>
  <td><?= htmlspecialchars($colInfo['Key'] ?? '') ?></td>
  <td><?= htmlspecialchars($colInfo['Default'] ?? '—') ?></td>
  <td><?= htmlspecialchars($colInfo['Extra'] ?? '') ?></td>
</tr>
</table>

<?php if ($colInfo['Null'] === 'NO' && $colInfo['Default'] === null): ?>
<div class="warn"><strong>PROBLEM CONFIRMED:</strong> Column is NOT NULL with no default — every INSERT that omits `type` will fail with SQLSTATE 1364. Click Option 1 below to fix.</div>
<?php elseif ($colInfo['Null'] === 'YES'): ?>
<div class="ok">Column is nullable — the import should work now. You can delete this file.</div>
<?php endif; ?>

<?php else: ?>
<div class="warn"><strong>`type` column does not exist on this server.</strong> The error may be from a different column — check the full column list below.</div>
<?php endif; ?>

<h2>Fix Options</h2>

<a class="btn btn-green" href="<?= $base ?>&action=make_nullable">
  Option 1: Make `type` nullable &rarr; (RECOMMENDED)
</a>
<br><small style="color:#aaa">Alters column to allow NULL. Works with current and old code. One-click fix.</small>

<br><br>
<a class="btn btn-orange" href="<?= $base ?>&action=add_trigger">
  Option 2: Add BEFORE INSERT trigger
</a>
<br><small style="color:#aaa">Creates a trigger that copies livestock_type &rarr; type on every INSERT. Column stays NOT NULL.</small>

<br><br>
<a class="btn" href="<?= $base ?>&action=backfill">
  Option 3: Backfill existing NULL values
</a>
<br><small style="color:#aaa">Sets type = livestock_type for any rows where type is NULL or empty. Run after Option 1.</small>

<?php if ($triggerInfo): ?>
<h2>Active triggers on <code>livestock</code></h2>
<table>
<tr><th>Trigger</th><th>Event</th><th>Timing</th><th>Statement (truncated)</th></tr>
<?php foreach ($triggerInfo as $t): ?>
<tr>
  <td><?= htmlspecialchars($t['Trigger']) ?></td>
  <td><?= htmlspecialchars($t['Event']) ?></td>
  <td><?= htmlspecialchars($t['Timing']) ?></td>
  <td><pre style="margin:0;font-size:11px;white-space:pre-wrap"><?= htmlspecialchars(substr($t['Statement'], 0, 300)) ?></pre></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<h2>All <code>livestock</code> columns</h2>
<table>
<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th><th>Extra</th></tr>
<?php foreach ($allCols as $c): ?>
<tr<?= $c['Field'] === 'type' ? ' style="background:#2a2a00;color:#ff8"' : '' ?>>
  <td><?= htmlspecialchars($c['Field']) ?></td>
  <td><?= htmlspecialchars($c['Type']) ?></td>
  <td><?= htmlspecialchars($c['Null']) ?></td>
  <td><?= htmlspecialchars($c['Default'] ?? 'NULL') ?></td>
  <td><?= htmlspecialchars($c['Extra'] ?? '') ?></td>
</tr>
<?php endforeach; ?>
</table>

<hr style="border-color:#444;margin-top:40px">
<p style="color:#666">Remember to delete <code>public/fix-livestock-type-column.php</code> after use.</p>
</body>
</html>
