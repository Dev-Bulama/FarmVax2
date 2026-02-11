<?php
/**
 * FarmVax — SAFE FARMS Excel → Livestock Import CSV Converter
 *
 * 1. Upload the SAFE FARMS Excel file (or enter path if already on server)
 * 2. Tool auto-maps columns → livestock import format
 * 3. Download the converted CSV ready for import
 *
 * Uses PhpSpreadsheet (already in vendor/).
 * DELETE THIS FILE after use.
 */

$password = 'FarmVax2025Fix';
if (!isset($_GET['key']) || $_GET['key'] !== $password) {
    http_response_code(403);
    die('<h2>Access denied.</h2><p>Append <code>?key=' . $password . '</code> to the URL.</p>');
}

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// ── Import format columns ─────────────────────────────────────────────────
// owner_email, livestock_type, breed, tag_number, name, gender, health_status, age_years, notes, quantity

// ── Keyword-based auto-mapper ─────────────────────────────────────────────
function autoMap(array $headers): array {
    $map = [
        'owner_email'    => null,
        'livestock_type' => null,
        'breed'          => null,
        'tag_number'     => null,
        'name'           => null,
        'gender'         => null,
        'health_status'  => null,
        'age_years'      => null,
        'notes'          => null,
        'quantity'       => null,
    ];

    $patterns = [
        'owner_email'    => ['email', 'e-mail', 'owner email', 'farmer email'],
        'livestock_type' => ['livestock type', 'animal type', 'type of livestock', 'type of animal', 'type of farm',
                             'animal', 'species', 'livestock', 'poultry type', 'farm type'],
        'breed'          => ['breed', 'strain', 'variety'],
        'tag_number'     => ['tag', 'id number', 'animal id', 'ear tag', 'tag number'],
        'name'           => ['animal name', 'name of animal', 'farm name', 'livestock name', 'name'],
        'gender'         => ['gender', 'sex'],
        'health_status'  => ['health', 'health status', 'condition', 'vaccination status', 'health condition'],
        'age_years'      => ['age', 'age (years)', 'age in years'],
        'notes'          => ['note', 'notes', 'remark', 'remarks', 'comment', 'additional', 'other'],
        'quantity'       => ['total farm capacity', 'capacity', 'total capacity', 'quantity', 'number of animals',
                             'total animals', 'total number', 'no. of animals', 'number', 'flock size',
                             'herd size', 'total livestock', 'population', 'stock'],
    ];

    foreach ($headers as $idx => $header) {
        $h = strtolower(trim((string)$header));
        foreach ($patterns as $field => $keywords) {
            if ($map[$field] !== null) continue; // already mapped
            foreach ($keywords as $kw) {
                if (str_contains($h, $kw) || $h === $kw) {
                    $map[$field] = $idx;
                    break 2;
                }
            }
        }
    }

    return $map;
}

// ── Livestock type normaliser ─────────────────────────────────────────────
function normaliseType(string $raw): string {
    $raw = strtolower(trim($raw));
    $map = [
        'cattle'   => ['cattle', 'cow', 'cows', 'bull', 'bulls', 'beef', 'dairy', 'heifer'],
        'goat'     => ['goat', 'goats', 'caprine'],
        'sheep'    => ['sheep', 'lamb', 'lambs', 'ovine'],
        'pig'      => ['pig', 'pigs', 'swine', 'pork', 'hog', 'hogs', 'porcine'],
        'chicken'  => ['chicken', 'chickens', 'poultry', 'broiler', 'broilers', 'layer', 'layers',
                       'hen', 'hens', 'cock', 'cockerel', 'noiler', 'noilers', 'kenbro'],
        'duck'     => ['duck', 'ducks'],
        'turkey'   => ['turkey', 'turkeys'],
        'rabbit'   => ['rabbit', 'rabbits'],
        'horse'    => ['horse', 'horses', 'equine', 'pony'],
        'donkey'   => ['donkey', 'donkeys', 'ass', 'mule'],
        'other'    => ['other', 'misc', 'mixed'],
    ];
    foreach ($map as $type => $aliases) {
        foreach ($aliases as $alias) {
            if ($raw === $alias || str_starts_with($raw, $alias)) return $type;
        }
    }
    // If the raw value contains a known type word anywhere
    foreach ($map as $type => $aliases) {
        foreach ($aliases as $alias) {
            if (str_contains($raw, $alias)) return $type;
        }
    }
    return 'other';
}

// ── Parse quantity from cell value ───────────────────────────────────────
function parseQty($raw): int {
    $s = str_replace(',', '', trim((string)$raw));
    preg_match('/(\d+)/', $s, $m);
    $n = isset($m[1]) ? (int)$m[1] : 1;
    return max(1, $n);
}

// ── Normalise gender ──────────────────────────────────────────────────────
function normaliseGender(string $raw): string {
    $r = strtolower(trim($raw));
    if (in_array($r, ['male', 'm', 'bull', 'cock', 'boar', 'ram', 'buck'])) return 'male';
    if (in_array($r, ['female', 'f', 'cow', 'hen', 'sow', 'ewe', 'doe'])) return 'female';
    return 'unknown';
}

// ── Normalise health status ───────────────────────────────────────────────
function normaliseHealth(string $raw): string {
    $r = strtolower(trim($raw));
    if (str_contains($r, 'sick') || str_contains($r, 'ill') || str_contains($r, 'disease')) return 'sick';
    if (str_contains($r, 'recover') || str_contains($r, 'treatment')) return 'recovering';
    if (str_contains($r, 'dead') || str_contains($r, 'deceas')) return 'deceased';
    return 'healthy';
}

// ── Parse .env for DB (to look up user emails) ────────────────────────────
function parseEnv($path) {
    $vars = [];
    if (!file_exists($path)) return $vars;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $vars[trim($k)] = trim($v, " \t\"'");
    }
    return $vars;
}

$env  = parseEnv(__DIR__ . '/../.env');
$pdo  = null;
try {
    $pdo = new PDO(
        "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']};charset=utf8mb4",
        $env['DB_USERNAME'], $env['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) { /* DB optional — only for email lookup */ }

// ── Step routing ──────────────────────────────────────────────────────────
$step      = $_POST['step'] ?? 'upload';
$error     = '';
$headers   = [];
$colMap    = [];
$rows      = [];
$sheetPath = '';

if ($step === 'map' || $step === 'download') {
    // Load spreadsheet from temp path stored in hidden field
    $sheetPath = $_POST['sheet_path'] ?? '';
    if (!$sheetPath || !file_exists($sheetPath)) {
        $step  = 'upload';
        $error = 'Session expired or file missing. Please re-upload.';
    } else {
        try {
            $spreadsheet = IOFactory::load($sheetPath);
            $sheet       = $spreadsheet->getActiveSheet();
            $rawRows     = $sheet->toArray(null, true, true, false);
            $headers     = array_map('strval', $rawRows[0] ?? []);
            $rows        = array_slice($rawRows, 1);
        } catch (Exception $e) {
            $step  = 'upload';
            $error = 'Could not read spreadsheet: ' . $e->getMessage();
        }
    }
}

// ── STEP: upload ─────────────────────────────────────────────────────────
if ($step === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload error code ' . $file['error'];
    } else {
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls', 'csv', 'ods'])) {
            $error = 'Unsupported file type. Please upload .xlsx, .xls, .csv or .ods';
        } else {
            $dest = sys_get_temp_dir() . '/farmvax_safe_' . uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $dest);
            try {
                $spreadsheet = IOFactory::load($dest);
                $sheet       = $spreadsheet->getActiveSheet();
                $rawRows     = $sheet->toArray(null, true, true, false);
                $headers     = array_map('strval', $rawRows[0] ?? []);
                $rows        = array_slice($rawRows, 1);
                $colMap      = autoMap($headers);
                $sheetPath   = $dest;
                $step        = 'map';
            } catch (Exception $e) {
                $error = 'Could not read file: ' . $e->getMessage();
                @unlink($dest);
            }
        }
    }
}

// ── STEP: map (form submitted) → build column map from POST ──────────────
if ($step === 'map' && isset($_POST['col_owner_email'])) {
    $colMap = [
        'owner_email'    => $_POST['col_owner_email']    !== '' ? (int)$_POST['col_owner_email']    : null,
        'livestock_type' => $_POST['col_livestock_type'] !== '' ? (int)$_POST['col_livestock_type'] : null,
        'breed'          => $_POST['col_breed']          !== '' ? (int)$_POST['col_breed']          : null,
        'tag_number'     => $_POST['col_tag_number']     !== '' ? (int)$_POST['col_tag_number']     : null,
        'name'           => $_POST['col_name']           !== '' ? (int)$_POST['col_name']           : null,
        'gender'         => $_POST['col_gender']         !== '' ? (int)$_POST['col_gender']         : null,
        'health_status'  => $_POST['col_health_status']  !== '' ? (int)$_POST['col_health_status']  : null,
        'age_years'      => $_POST['col_age_years']      !== '' ? (int)$_POST['col_age_years']      : null,
        'notes'          => $_POST['col_notes']          !== '' ? (int)$_POST['col_notes']          : null,
        'quantity'       => $_POST['col_quantity']       !== '' ? (int)$_POST['col_quantity']       : null,
    ];
    $step = 'download';
}

// ── STEP: download — generate and stream CSV ──────────────────────────────
if ($step === 'download') {
    // Collect all known farmer emails from DB if available
    $knownEmails = [];
    if ($pdo) {
        $stmt = $pdo->query("SELECT email FROM users WHERE role IN ('farmer','individual') AND email IS NOT NULL");
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $e) {
            $knownEmails[strtolower(trim($e))] = $e;
        }
    }

    // Build CSV in memory
    $output  = fopen('php://temp', 'r+');
    fputcsv($output, ['owner_email','livestock_type','breed','tag_number','name','gender','health_status','age_years','notes','quantity']);

    $written = 0;
    $skipped = 0;

    foreach ($rows as $row) {
        // Skip empty rows
        $rowStr = trim(implode('', array_map('strval', $row)));
        if ($rowStr === '') { $skipped++; continue; }

        $get = fn($field) => isset($colMap[$field]) && $colMap[$field] !== null
            ? trim((string)($row[$colMap[$field]] ?? ''))
            : '';

        $rawType  = $get('livestock_type');
        $type     = $rawType !== '' ? normaliseType($rawType) : '';

        if ($type === '') { $skipped++; continue; } // Must have livestock type

        $emailRaw = $get('owner_email');
        $email    = '';
        if ($emailRaw !== '') {
            $emailRaw = strtolower(trim($emailRaw));
            $email    = $knownEmails[$emailRaw] ?? $emailRaw;
        }

        $rawGender = $get('gender');
        $gender    = $rawGender !== '' ? normaliseGender($rawGender) : 'unknown';

        $rawHealth = $get('health_status');
        $health    = $rawHealth !== '' ? normaliseHealth($rawHealth) : 'healthy';

        $ageRaw = $get('age_years');
        preg_match('/(\d+)/', str_replace(',', '', $ageRaw), $am);
        $age = isset($am[1]) ? (int)$am[1] : 0;

        $qty = parseQty($get('quantity') ?: '1');

        fputcsv($output, [
            $email,
            $type,
            $get('breed'),
            $get('tag_number'),
            $get('name'),
            $gender,
            $health,
            $age > 0 ? $age : '',
            $get('notes'),
            $qty,
        ]);
        $written++;
    }

    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);

    // Deliver as download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="safe_farms_livestock_import.csv"');
    header('Content-Length: ' . strlen($csv));
    echo $csv;
    @unlink($sheetPath);
    exit;
}

// ── Auto-map on fresh file load ───────────────────────────────────────────
if ($step === 'map' && empty($colMap)) {
    $colMap = autoMap($headers);
}

// ── HTML helpers ──────────────────────────────────────────────────────────
function sel(array $colMap, string $field, array $headers): string {
    $out  = '<select name="col_' . $field . '" style="background:#222;color:#eee;border:1px solid #555;padding:4px 8px;width:100%;border-radius:3px">';
    $out .= '<option value="">(not in file)</option>';
    foreach ($headers as $i => $h) {
        $sel  = (isset($colMap[$field]) && $colMap[$field] === $i) ? ' selected' : '';
        $out .= '<option value="' . $i . '"' . $sel . '>' . htmlspecialchars($h) . '</option>';
    }
    $out .= '</select>';
    return $out;
}

$base = '?key=' . $password;
?>
<!DOCTYPE html>
<html>
<head>
<title>SAFE FARMS → Livestock CSV Converter</title>
<style>
*     { box-sizing: border-box; }
body  { font-family: monospace; background:#111; color:#eee; padding:30px; max-width:960px; margin:0 auto; }
h1    { color:#f90; }
h2    { color:#7cf; margin-top:30px; }
table { border-collapse:collapse; width:100%; margin:10px 0; }
th,td { border:1px solid #444; padding:8px 10px; text-align:left; font-size:12px; vertical-align:top; }
th    { background:#222; color:#adf; }
tr:nth-child(even) { background:#1a1a1a; }
.ok   { background:#1a3a1a; border:1px solid #4a4; color:#8f8; padding:12px; margin:10px 0; border-radius:4px; }
.err  { background:#3a1a1a; border:1px solid #a44; color:#f88; padding:12px; margin:10px 0; border-radius:4px; }
.warn { background:#3a3a1a; border:1px solid #aa4; color:#ff8; padding:12px; margin:10px 0; border-radius:4px; }
.btn  { display:inline-block; background:#0055aa; color:#fff; padding:10px 22px; margin:8px 4px 8px 0;
        border:none; border-radius:4px; font-weight:bold; font-size:14px; cursor:pointer; text-decoration:none; }
.btn:hover  { background:#0077ee; }
.btn-green  { background:#006622; } .btn-green:hover { background:#009933; }
label       { display:block; margin:12px 0 3px; color:#adf; font-size:12px; }
input[type=file] { background:#222; color:#eee; border:1px dashed #555; padding:10px; width:100%; border-radius:4px; }
.req  { color:#f88; } /* required field indicator */
.auto { color:#8f8; font-size:11px; margin-left:6px; }
</style>
</head>
<body>

<h1>SAFE FARMS → Livestock Import CSV</h1>
<p>Converts your Google Forms Excel export into the FarmVax livestock import format.</p>

<?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<?php if ($step === 'upload'): ?>
<!-- ═══════════════════════════════════════════════════════════ STEP 1 -->
<h2>Step 1 — Upload the Excel file</h2>
<form method="POST" enctype="multipart/form-data" action="<?= $base ?>">
  <input type="hidden" name="step" value="upload">
  <label>Excel / CSV file (.xlsx, .xls, .csv, .ods)</label>
  <input type="file" name="excel_file" accept=".xlsx,.xls,.csv,.ods" required>
  <br><br>
  <button class="btn btn-green" type="submit">Upload & Detect Columns →</button>
</form>

<?php elseif ($step === 'map'): ?>
<!-- ═══════════════════════════════════════════════════════════ STEP 2 -->
<h2>Step 2 — Confirm Column Mapping</h2>
<div class="ok">
  Found <strong><?= count($headers) ?></strong> columns and
  <strong><?= count($rows) ?></strong> data rows. Columns auto-mapped where possible
  <span class="auto">(green = auto-detected)</span>.
</div>

<form method="POST" action="<?= $base ?>">
  <input type="hidden" name="step" value="map">
  <input type="hidden" name="sheet_path" value="<?= htmlspecialchars($sheetPath) ?>">

  <table>
  <tr><th>Import Field</th><th>Required?</th><th>Map to Excel Column</th><th>Auto-detected?</th></tr>
  <?php
  $fields = [
      'owner_email'    => ['Owner Email',    false, 'Farmer email address in the system'],
      'livestock_type' => ['Livestock Type', true,  'cattle, goat, sheep, pig, chicken, etc.'],
      'breed'          => ['Breed',          false, ''],
      'tag_number'     => ['Tag Number',     false, ''],
      'name'           => ['Animal/Farm Name',false,''],
      'gender'         => ['Gender',         false, 'male / female / unknown'],
      'health_status'  => ['Health Status',  false, 'healthy / sick / recovering / deceased'],
      'age_years'      => ['Age (years)',    false, ''],
      'notes'          => ['Notes',          false, ''],
      'quantity'       => ['Quantity',       true,  '"Total farm capacity" → number of animals'],
  ];
  foreach ($fields as $f => [$label, $req, $hint]): ?>
  <tr>
    <td>
      <strong><?= $label ?></strong><?= $req ? ' <span class="req">*</span>' : '' ?>
      <?php if ($hint): ?><br><small style="color:#888"><?= $hint ?></small><?php endif; ?>
    </td>
    <td><?= $req ? '<span class="req">Required</span>' : 'Optional' ?></td>
    <td><?= sel($colMap, $f, $headers) ?></td>
    <td><?= isset($colMap[$f]) && $colMap[$f] !== null
          ? '<span style="color:#8f8">Yes → <em>' . htmlspecialchars($headers[$colMap[$f]] ?? '') . '</em></span>'
          : '<span style="color:#888">No</span>' ?>
    </td>
  </tr>
  <?php endforeach; ?>
  </table>

  <h2>Preview (first 5 rows)</h2>
  <div style="overflow-x:auto">
  <table>
    <tr><?php foreach ($headers as $h): ?><th><?= htmlspecialchars($h) ?></th><?php endforeach; ?></tr>
    <?php foreach (array_slice($rows, 0, 5) as $row): ?>
    <tr><?php foreach ($row as $cell): ?><td><?= htmlspecialchars((string)$cell) ?></td><?php endforeach; ?></tr>
    <?php endforeach; ?>
  </table>
  </div>

  <br>
  <button class="btn btn-green" type="submit">Generate Import CSV →</button>
  <a class="btn" href="<?= $base ?>">Start over</a>
</form>

<?php endif; ?>

<hr style="border-color:#444; margin-top:50px">
<p style="color:#555">Delete <code>public/convert-safe-farms-excel.php</code> after use.</p>
</body>
</html>
