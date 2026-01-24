<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Checking Countries Table Structure</h2>";
echo "<hr>";

try {
    $columns = DB::select("SHOW COLUMNS FROM countries");
    
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
    
    echo "<br><h3>Column Names:</h3>";
    echo "<p>";
    foreach ($columns as $column) {
        echo "<code>" . $column->Field . "</code>, ";
    }
    echo "</p>";
    
    // Check if phone_code exists
    $hasPhoneCode = false;
    foreach ($columns as $column) {
        if ($column->Field === 'phone_code') {
            $hasPhoneCode = true;
            break;
        }
    }
    
    if (!$hasPhoneCode) {
        echo "<br><h3 style='color: orange;'>⚠️ phone_code column is MISSING</h3>";
        echo "<p>Click below to add it:</p>";
        
        if (isset($_GET['add_column']) && $_GET['add_column'] == 'yes') {
            try {
                DB::statement("ALTER TABLE countries ADD COLUMN phone_code VARCHAR(10) NULL AFTER code");
                echo "<p style='color: green;'>✅ phone_code column added successfully!</p>";
                echo "<p><a href='?'>Refresh to verify</a></p>";
            } catch (\Exception $e) {
                echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<a href='?add_column=yes' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Add phone_code Column</a>";
        }
    } else {
        echo "<br><p style='color: green;'>✅ phone_code column exists</p>";
    }
    
    // Show sample data
    echo "<br><h3>Sample Data:</h3>";
    $countries = DB::table('countries')->limit(5)->get();
    if ($countries->count() > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>" . $column->Field . "</th>";
        }
        echo "</tr>";
        foreach ($countries as $country) {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>" . ($country->{$column->Field} ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No data found</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><a href='/populate-locations.php' style='padding: 10px 20px; background: #11455b; color: white; text-decoration: none; border-radius: 5px;'>Back to Populate Tool</a>";
echo "<br><br><strong style='color: red;'>DELETE THIS FILE AFTER CHECKING!</strong>";