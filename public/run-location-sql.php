<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Running Location Data Import...</h2>";

try {
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/../database/seeders/insert-location-data.sql');
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $count = 0;
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        DB::statement($statement);
        $count++;
    }
    
    echo "<p style='color: green;'>✅ Successfully executed {$count} SQL statements!</p>";
    
    // Show counts
    $countries = DB::table('countries')->count();
    $states = DB::table('states')->count();
    $lgas = DB::table('lgas')->count();
    
    echo "<h3>Database Counts:</h3>";
    echo "<p>Countries: {$countries}</p>";
    echo "<p>States: {$states}</p>";
    echo "<p>LGAs: {$lgas}</p>";
    
    echo "<br><a href='/check-location-data.php' style='padding: 10px 20px; background: #2fcb6e; color: white; text-decoration: none; border-radius: 5px;'>Verify Data</a>";
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><br><p style='color: red; font-weight: bold;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</p>";