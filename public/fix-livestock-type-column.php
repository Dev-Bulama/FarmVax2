<?php
/**
 * FarmVax DB Fix Tool - livestock table schema fixes
 *  1. Fix `type` column: make nullable (stops SQLSTATE 1364 import errors)
 *  2. Add `quantity` column: INT UNSIGNED NOT NULL DEFAULT 1
 *     (required for batch/flock livestock import feature)
 *
 * Reads .env directly — no Laravel bootstrap needed.
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

// ── Action: fix type column ────────────────────────────────────────────────
if ($action === 'make_type_nullable') {
    try {
        $col = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
        $colType = $col['Type'] ?? 'varchar(255)';
        $pdo->exec("ALTER TABLE `livestock` MODIFY COLUMN `type` $colType NULL DEFAULT NULL");
        $messages[] = "SUCCESS: `type` column is now nullable. INSERTs that omit `type` will store NULL.";
    } catch (Exception $e) {
        $errors[] = 'ALTER type failed: ' . $e->getMessage();
    }
}

if ($action === 'add_type_trigger') {
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
        $messages[] = "SUCCESS: Trigger created — `type` auto-copies from `livestock_type` on every INSERT.";
    } catch (Exception $e) {
        $errors[] = 'Trigger creation failed: ' . $e->getMessage();
    }
}

if ($action === 'backfill_type') {
    try {
        $count = $pdo->exec("UPDATE `livestock` SET `type` = `livestock_type` WHERE `type` IS NULL OR `type` = ''");
        $messages[] = "SUCCESS: Backfilled `type` from `livestock_type` for {$count} rows.";
    } catch (Exception $e) {
        $errors[] = 'Backfill type failed: ' . $e->getMessage();
    }
}

// ── Action: add quantity column ────────────────────────────────────────────
if ($action === 'add_quantity') {
    try {
        $exists = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'quantity'")->fetch(PDO::FETCH_ASSOC);
        if ($exists) {
            $messages[] = "INFO: `quantity` column already exists — no change needed.";
        } else {
            // Add after `type` column if it exists, otherwise after `livestock_type`
            $typeExists = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
            $after = $typeExists ? 'AFTER `type`' : 'AFTER `livestock_type`';
            $pdo->exec("ALTER TABLE `livestock` ADD COLUMN `quantity` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Number of animals this record represents' $after");
            $messages[] = "SUCCESS: `quantity` column added (INT UNSIGNED NOT NULL DEFAULT 1).";
        }
    } catch (Exception $e) {
        $errors[] = 'Add quantity column failed: ' . $e->getMessage();
    }
}

if ($action === 'backfill_quantity') {
    try {
        $count = $pdo->exec("UPDATE `livestock` SET `quantity` = 1 WHERE `quantity` IS NULL OR `quantity` < 1");
        $messages[] = "SUCCESS: Set `quantity` = 1 for {$count} rows that had NULL or zero.";
    } catch (Exception $e) {
        $errors[] = 'Backfill quantity failed: ' . $e->getMessage();
    }
}

// ── Action: run BOTH critical fixes ───────────────────────────────────────
if ($action === 'fix_all') {
    // 1. Fix type column
    try {
        $col = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
        if ($col && $col['Null'] === 'NO' && $col['Default'] === null) {
            $colType = $col['Type'] ?? 'varchar(255)';
            $pdo->exec("ALTER TABLE `livestock` MODIFY COLUMN `type` $colType NULL DEFAULT NULL");
            $messages[] = "Fix 1 SUCCESS: `type` column is now nullable.";
        } else {
            $messages[] = "Fix 1 SKIPPED: `type` column already OK (nullable or doesn't exist).";
        }
    } catch (Exception $e) {
        $errors[] = 'Fix 1 (type): ' . $e->getMessage();
    }

    // 2. Add quantity column
    try {
        $exists = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'quantity'")->fetch(PDO::FETCH_ASSOC);
        if (!$exists) {
            $typeExists = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
            $after = $typeExists ? 'AFTER `type`' : 'AFTER `livestock_type`';
            $pdo->exec("ALTER TABLE `livestock` ADD COLUMN `quantity` INT UNSIGNED NOT NULL DEFAULT 1 $after");
            $messages[] = "Fix 2 SUCCESS: `quantity` column added.";
        } else {
            $messages[] = "Fix 2 SKIPPED: `quantity` column already exists.";
        }
    } catch (Exception $e) {
        $errors[] = 'Fix 2 (quantity): ' . $e->getMessage();
    }
}

// ── Refresh state ──────────────────────────────────────────────────────────
$typeColInfo = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'type'")->fetch(PDO::FETCH_ASSOC);
$qtyColInfo  = $pdo->query("SHOW COLUMNS FROM `livestock` LIKE 'quantity'")->fetch(PDO::FETCH_ASSOC);
$allCols     = $pdo->query("SHOW COLUMNS FROM `livestock`")->fetchAll(PDO::FETCH_ASSOC);
$triggerInfo = $pdo->query("SHOW TRIGGERS WHERE `Table` = 'livestock'")->fetchAll(PDO::FETCH_ASSOC);

// Determine overall status
$typeOk = !$typeColInfo || $typeColInfo['Null'] === 'YES';
$qtyOk  = (bool) $qtyColInfo;
$allOk  = $typeOk && $qtyOk;

$base = '?key=' . $password;
?>
<!DOCTYPE html>
<html>
<head>
<title>FarmVax DB Fix Tool</title>
<style>
body  { font-family: monospace; background:#111; color:#eee; padding:30px; max-width:1100px; margin:0 auto; }
h1    { color:#f90; }
h2    { color:#7cf; margin-top:30px; }
table { border-collapse:collapse; width:100%; margin:10px 0; }
th,td { border:1px solid #444; padding:8px 12px; text-align:left; font-size:13px; }
th    { background:#222; color:#adf; }
tr:nth-child(even) { background:#1a1a1a; }
.ok   { background:#1a3a1a; border:1px solid #4a4; color:#8f8; padding:12px; margin:8px 0; border-radius:4px; }
.err  { background:#3a1a1a; border:1px solid #a44; color:#f88; padding:12px; margin:8px 0; border-radius:4px; }
.warn { background:#3a3a1a; border:1px solid #aa4; color:#ff8; padding:12px; margin:8px 0; border-radius:4px; }
.info { background:#1a1a3a; border:1px solid #44a; color:#aaf; padding:12px; margin:8px 0; border-radius:4px; }
.btn  { display:inline-block; background:#0055aa; color:#fff; padding:10px 20px; margin:6px 4px;
        border-radius:4px; text-decoration:none; font-weight:bold; font-size:14px; }
.btn:hover    { background:#0077ee; }
.btn-green    { background:#006622; } .btn-green:hover  { background:#009933; }
.btn-orange   { background:#885500; } .btn-orange:hover { background:#bb7700; }
.btn-big      { padding:14px 28px; font-size:16px; background:#006622; }
.btn-big:hover{ background:#009933; }
.hl-type { background:#2a2000!important; color:#ff8!important; }
.hl-qty  { background:#002a2a!important; color:#8ff!important; }
</style>
</head>
<body>
<h1>FarmVax — Livestock DB Fix Tool</h1>
<p>Connected to <strong><?= htmlspecialchars($dbname) ?></strong> @ <?= htmlspecialchars($host) ?>:<?= htmlspecialchars($port) ?></p>

<?php foreach ($messages as $m): ?>
<div class="<?= str_starts_with($m,'SUCCESS') ? 'ok' : (str_starts_with($m,'Fix') && str_contains($m,'SUCCESS') ? 'ok' : 'info') ?>"><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>
<?php foreach ($errors as $e): ?>
<div class="err"><?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<!-- ── STATUS OVERVIEW ─────────────────────────────────────── -->
<h2>Status Overview</h2>
<table>
<tr><th>Column</th><th>Exists?</th><th>Status</th><th>Required For</th></tr>
<tr>
  <td><code>type</code></td>
  <td><?= $typeColInfo ? 'Yes' : 'No' ?></td>
  <td><?php
    if (!$typeColInfo) echo '<span style="color:#8f8">Not present — OK</span>';
    elseif ($typeColInfo['Null'] === 'YES') echo '<span style="color:#8f8">Nullable — OK</span>';
    else echo '<span style="color:#f88">NOT NULL / no default — CAUSES IMPORT FAILURE</span>';
  ?></td>
  <td>All livestock inserts (production DB quirk)</td>
</tr>
<tr>
  <td><code>quantity</code></td>
  <td><?= $qtyColInfo ? 'Yes' : '<span style="color:#f88">No — MISSING</span>' ?></td>
  <td><?php
    if ($qtyColInfo) echo '<span style="color:#8f8">Present — OK</span>';
    else echo '<span style="color:#f88">Column does not exist — batch/flock import will fail</span>';
  ?></td>
  <td>Batch livestock import (e.g. 20,000 birds per row)</td>
</tr>
</table>

<?php if (!$allOk): ?>
<div class="warn">
  <strong>Action required:</strong>
  <?= !$typeOk ? '`type` column needs to be made nullable. ' : '' ?>
  <?= !$qtyOk  ? '`quantity` column is missing. ' : '' ?>
  Click <strong>"Run All Fixes"</strong> below to fix both at once.
</div>
<?php else: ?>
<div class="ok"><strong>All fixes applied!</strong> You can now import livestock CSV files. Delete this file when done.</div>
<?php endif; ?>

<!-- ── QUICK FIX ───────────────────────────────────────────── -->
<h2>Quick Fix (Recommended)</h2>
<a class="btn btn-big" href="<?= $base ?>&action=fix_all">
  Run All Fixes (fix type + add quantity)
</a>
<br><small style="color:#aaa">Applies both fixes in one click. Safe to run even if already applied — will skip what's already correct.</small>

<!-- ── INDIVIDUAL FIXES ────────────────────────────────────── -->
<h2>Individual Fixes</h2>

<strong style="color:#ff8">Fix 1 — `type` column (SQLSTATE 1364 import error)</strong><br><br>
<a class="btn btn-green" href="<?= $base ?>&action=make_type_nullable">
  Make `type` nullable
</a>
&nbsp;
<a class="btn btn-orange" href="<?= $base ?>&action=add_type_trigger">
  Add auto-copy trigger instead
</a>
&nbsp;
<a class="btn" href="<?= $base ?>&action=backfill_type">
  Backfill NULLs from livestock_type
</a>

<br><br>
<strong style="color:#8ff">Fix 2 — `quantity` column (batch/flock import)</strong><br><br>
<a class="btn btn-green" href="<?= $base ?>&action=add_quantity">
  Add `quantity` column
</a>
&nbsp;
<a class="btn" href="<?= $base ?>&action=backfill_quantity">
  Backfill quantity = 1 for existing rows
</a>

<!-- ── COLUMN LIST ─────────────────────────────────────────── -->
<h2>All <code>livestock</code> columns</h2>
<table>
<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th><th>Extra</th></tr>
<?php foreach ($allCols as $c): ?>
<tr class="<?= $c['Field'] === 'type' ? 'hl-type' : ($c['Field'] === 'quantity' ? 'hl-qty' : '') ?>">
  <td><?= htmlspecialchars($c['Field']) ?></td>
  <td><?= htmlspecialchars($c['Type']) ?></td>
  <td><?= htmlspecialchars($c['Null']) ?></td>
  <td><?= htmlspecialchars($c['Default'] ?? 'NULL') ?></td>
  <td><?= htmlspecialchars($c['Extra'] ?? '') ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php if ($triggerInfo): ?>
<h2>Active triggers on <code>livestock</code></h2>
<table>
<tr><th>Trigger</th><th>Event</th><th>Timing</th></tr>
<?php foreach ($triggerInfo as $t): ?>
<tr>
  <td><?= htmlspecialchars($t['Trigger']) ?></td>
  <td><?= htmlspecialchars($t['Event']) ?></td>
  <td><?= htmlspecialchars($t['Timing']) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<hr style="border-color:#444;margin-top:40px">
<p style="color:#666">Delete <code>public/fix-livestock-type-column.php</code> from the server after use.</p>
</body>
</html>
