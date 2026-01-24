<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Checking bulk_messages Table Structure</h2>";
echo "<hr>";

try {
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM bulk_messages");
    
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
    
    // List all columns
    echo "<br><h3>Column Names:</h3>";
    echo "<p>";
    foreach ($columns as $column) {
        echo $column->Field . ", ";
    }
    echo "</p>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";