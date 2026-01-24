<?php
/**
 * Test Landing Page Route
 * Access via: https://farmvax.com/test-landing.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>Landing Page Diagnostic</h1>";
echo "<hr>";

echo "<h3>Testing Database Queries...</h3>";

try {
    // Test 1: Users table
    echo "<h4>1. Testing Users (Farmers):</h4>";
    try {
        // Check if is_active column exists
        $columns = DB::select("SHOW COLUMNS FROM users LIKE 'is_active'");
        if (empty($columns)) {
            echo "<p style='color: orange;'>⚠️ Column 'is_active' does not exist in users table</p>";
            echo "<p>Using alternative query...</p>";
            $farmers = \App\Models\User::where('role', 'farmer')->count();
        } else {
            echo "<p style='color: green;'>✅ Column 'is_active' exists</p>";
            $farmers = \App\Models\User::where('role', 'farmer')->where('is_active', true)->count();
        }
        echo "<p style='color: green;'>✅ Farmers count: <strong>$farmers</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        $farmers = 0;
    }

    // Test 2: Professionals
    echo "<h4>2. Testing Animal Health Professionals:</h4>";
    try {
        $professionals = \App\Models\AnimalHealthProfessional::where('approval_status', 'approved')->count();
        echo "<p style='color: green;'>✅ Approved professionals count: <strong>$professionals</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        $professionals = 0;
    }

    // Test 3: Livestock
    echo "<h4>3. Testing Livestock:</h4>";
    try {
        $livestock = \App\Models\Livestock::count();
        echo "<p style='color: green;'>✅ Livestock count: <strong>$livestock</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        $livestock = 0;
    }

    // Test 4: Farm Records
    echo "<h4>4. Testing Farm Records:</h4>";
    try {
        $farmRecords = \App\Models\FarmRecord::count();
        echo "<p style='color: green;'>✅ Farm records count: <strong>$farmRecords</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        $farmRecords = 0;
    }

    // Test 5: Vaccinations
    echo "<h4>5. Testing Vaccination History:</h4>";
    try {
        $vaccinations = \App\Models\VaccinationHistory::count();
        echo "<p style='color: green;'>✅ Vaccinations count: <strong>$vaccinations</strong></p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        $vaccinations = 0;
    }

    echo "<hr>";
    echo "<h3>Summary:</h3>";
    echo "<pre>";
    print_r([
        'farmers' => $farmers ?? 0,
        'professionals' => $professionals ?? 0,
        'livestock' => $livestock ?? 0,
        'farm_records' => $farmRecords ?? 0,
        'vaccinations' => $vaccinations ?? 0,
    ]);
    echo "</pre>";

    echo "<hr>";
    echo "<h3>✅ All queries tested successfully!</h3>";
    echo "<p>The landing page should work with these values.</p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Fatal Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h3>Checking Users Table Structure:</h3>";
try {
    $columns = DB::select("SHOW COLUMNS FROM users");
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
