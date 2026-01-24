<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<style>
body{font-family:Arial;padding:20px;} 
table{border-collapse:collapse;width:100%;margin:20px 0;} 
th,td{border:1px solid #ddd;padding:8px;text-align:left;} 
th{background:#f4f4f4;font-weight:bold;} 
.good{color:green;font-weight:bold;} 
.bad{color:red;font-weight:bold;}
</style>";

echo "<h1>Location Data Mismatch Diagnostic</h1>";

// Check 1: Users with location data
echo "<h2>1. Users with Location IDs</h2>";
$usersWithLocation = DB::table('users')
    ->whereNotNull('country_id')
    ->orWhereNotNull('state_id')
    ->orWhereNotNull('lga_id')
    ->count();

echo "<p>Users with location data: <strong>{$usersWithLocation}</strong></p>";

// Check 2: Sample users with broken locations
echo "<h2>2. Sample Users - Location Lookup Test (First 10)</h2>";
$sampleUsers = DB::table('users')
    ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
    ->leftJoin('states', 'users.state_id', '=', 'states.id')
    ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
    ->select(
        'users.id',
        'users.name',
        'users.country_id',
        'countries.name as country_name',
        'users.state_id',
        'states.name as state_name',
        'users.lga_id',
        'lgas.name as lga_name'
    )
    ->whereNotNull('users.country_id')
    ->limit(10)
    ->get();

echo "<table>";
echo "<tr>
    <th>User ID</th>
    <th>Name</th>
    <th>Country ID</th>
    <th>Country Name</th>
    <th>State ID</th>
    <th>State Name</th>
    <th>LGA ID</th>
    <th>LGA Name</th>
    <th>Status</th>
</tr>";

foreach ($sampleUsers as $user) {
    $countryStatus = $user->country_id && $user->country_name ? '✓' : '✗';
    $stateStatus = $user->state_id && $user->state_name ? '✓' : '✗';
    $lgaStatus = $user->lga_id && $user->lga_name ? '✓' : '✗';
    
    $overallStatus = ($user->country_name && $user->state_name && $user->lga_name) 
        ? '<span class="good">GOOD</span>' 
        : '<span class="bad">BROKEN</span>';
    
    echo "<tr>";
    echo "<td>{$user->id}</td>";
    echo "<td>{$user->name}</td>";
    echo "<td>{$user->country_id}</td>";
    echo "<td>" . ($user->country_name ?? '<span class="bad">NULL</span>') . " {$countryStatus}</td>";
    echo "<td>{$user->state_id}</td>";
    echo "<td>" . ($user->state_name ?? '<span class="bad">NULL</span>') . " {$stateStatus}</td>";
    echo "<td>{$user->lga_id}</td>";
    echo "<td>" . ($user->lga_name ?? '<span class="bad">NULL</span>') . " {$lgaStatus}</td>";
    echo "<td>{$overallStatus}</td>";
    echo "</tr>";
}
echo "</table>";

// Check 3: Orphaned IDs (IDs in users table that don't exist in reference tables)
echo "<h2>3. Orphaned Location IDs (Mismatches)</h2>";

// Check orphaned countries
$orphanedCountries = DB::table('users')
    ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
    ->whereNotNull('users.country_id')
    ->whereNull('countries.id')
    ->select('users.country_id', DB::raw('COUNT(*) as count'))
    ->groupBy('users.country_id')
    ->get();

echo "<h3>Orphaned Country IDs:</h3>";
if ($orphanedCountries->count() > 0) {
    echo "<p class='bad'>Found {$orphanedCountries->count()} orphaned country IDs:</p>";
    echo "<table><tr><th>Country ID</th><th>Users Affected</th></tr>";
    foreach ($orphanedCountries as $row) {
        echo "<tr><td>{$row->country_id}</td><td>{$row->count}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='good'>✓ No orphaned country IDs</p>";
}

// Check orphaned states
$orphanedStates = DB::table('users')
    ->leftJoin('states', 'users.state_id', '=', 'states.id')
    ->whereNotNull('users.state_id')
    ->whereNull('states.id')
    ->select('users.state_id', DB::raw('COUNT(*) as count'))
    ->groupBy('users.state_id')
    ->get();

echo "<h3>Orphaned State IDs:</h3>";
if ($orphanedStates->count() > 0) {
    echo "<p class='bad'>Found {$orphanedStates->count()} orphaned state IDs:</p>";
    echo "<table><tr><th>State ID</th><th>Users Affected</th></tr>";
    foreach ($orphanedStates as $row) {
        echo "<tr><td>{$row->state_id}</td><td>{$row->count}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='good'>✓ No orphaned state IDs</p>";
}

// Check orphaned LGAs
$orphanedLGAs = DB::table('users')
    ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
    ->whereNotNull('users.lga_id')
    ->whereNull('lgas.id')
    ->select('users.lga_id', DB::raw('COUNT(*) as count'))
    ->groupBy('users.lga_id')
    ->get();

echo "<h3>Orphaned LGA IDs:</h3>";
if ($orphanedLGAs->count() > 0) {
    echo "<p class='bad'>Found {$orphanedLGAs->count()} orphaned LGA IDs affecting users:</p>";
    echo "<table><tr><th>LGA ID</th><th>Users Affected</th></tr>";
    foreach ($orphanedLGAs as $row) {
        echo "<tr><td>{$row->lga_id}</td><td>{$row->count}</td></tr>";
    }
    echo "</table>";
    
    echo "<h4>Total users with broken LGA references: <span class='bad'>" . $orphanedLGAs->sum('count') . "</span></h4>";
} else {
    echo "<p class='good'>✓ No orphaned LGA IDs</p>";
}

// Check 4: Available data in reference tables
echo "<h2>4. Available Reference Data</h2>";
echo "<table>";
echo "<tr><th>Table</th><th>Record Count</th></tr>";
echo "<tr><td>Countries</td><td>" . DB::table('countries')->count() . "</td></tr>";
echo "<tr><td>States</td><td>" . DB::table('states')->count() . "</td></tr>";
echo "<tr><td>LGAs</td><td>" . DB::table('lgas')->count() . "</td></tr>";
echo "</table>";

// Check 5: Show available countries
echo "<h3>Available Countries:</h3>";
$countries = DB::table('countries')->get();
echo "<table><tr><th>ID</th><th>Name</th><th>Code</th></tr>";
foreach ($countries as $country) {
    echo "<tr><td>{$country->id}</td><td>{$country->name}</td><td>{$country->code}</td></tr>";
}
echo "</table>";

echo "<br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER CHECKING!</p>";