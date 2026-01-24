<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Checking outbreak_alerts Table Structure</h2>";
echo "<hr>";

try {
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM outbreak_alerts");
    
    echo "<h3>Available Columns:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column->Field . "</td>";
        echo "<td>" . $column->Type . "</td>";
        echo "<td>" . $column->Null . "</td>";
        echo "<td>" . $column->Key . "</td>";
        echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if status column exists
    $hasStatus = false;
    foreach ($columns as $column) {
        if ($column->Field === 'status') {
            $hasStatus = true;
            break;
        }
    }
    
    echo "<br><h3>Results:</h3>";
    if ($hasStatus) {
        echo "<p style='color: green;'>✅ status column EXISTS</p>";
    } else {
        echo "<p style='color: red;'>❌ status column DOES NOT EXIST</p>";
        echo "<p>We need to either:</p>";
        echo "<ol>";
        echo "<li>Remove the status filter from the dashboard query</li>";
        echo "<li>OR add a status column to the table</li>";
        echo "</ol>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";