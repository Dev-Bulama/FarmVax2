<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Checking LGAs Table Structure</h2>";
echo "<hr>";

try {
    $columns = DB::select("SHOW COLUMNS FROM lgas");
    
    echo "<h3>Available Columns:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column->Field . "</td>";
        echo "<td>" . $column->Type . "</td>";
        echo "<td>" . $column->Null . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><h3>Column Names:</h3>";
    echo "<p>";
    $columnNames = [];
    foreach ($columns as $column) {
        $columnNames[] = $column->Field;
        echo "<code>" . $column->Field . "</code>, ";
    }
    echo "</p>";
    
    // Check states table too
    echo "<br><h3>States Table Columns:</h3>";
    $stateColumns = DB::select("SHOW COLUMNS FROM states");
    echo "<p>";
    $stateColumnNames = [];
    foreach ($stateColumns as $column) {
        $stateColumnNames[] = $column->Field;
        echo "<code>" . $column->Field . "</code>, ";
    }
    echo "</p>";
    
    echo "<br><hr>";
    echo "<h3>What to do next:</h3>";
    
    // Generate the correct insert statement
    echo "<p>Your tables have these columns. I'll create a fixed populate script.</p>";
    
    echo "<br><a href='/populate-final.php' style='padding: 15px 30px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Go to Fixed Populate Script</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><strong style='color: red;'>DELETE THIS FILE!</strong>";