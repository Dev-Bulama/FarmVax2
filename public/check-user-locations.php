<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<style>body{font-family:Arial;padding:20px;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f4f4f4;} .success{color:green;} .error{color:red;}</style>";

echo "<h2>User Location Data Check</h2>";

// Check users with location data
$usersWithLocation = DB::table('users')
    ->whereNotNull('country_id')
    ->orWhereNotNull('state_id')
    ->orWhereNotNull('lga_id')
    ->count();

$usersWithoutLocation = DB::table('users')
    ->whereNull('country_id')
    ->whereNull('state_id')
    ->whereNull('lga_id')
    ->count();

echo "<div style='margin: 20px 0;'>";
echo "<p><strong>Users WITH location data:</strong> <span class='success'>{$usersWithLocation}</span></p>";
echo "<p><strong>Users WITHOUT location data:</strong> <span class='error'>{$usersWithoutLocation}</span></p>";
echo "</div>";

// Show sample users with locations
echo "<h3>Sample Users with Locations</h3>";
$sampleUsers = DB::table('users')
    ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
    ->leftJoin('states', 'users.state_id', '=', 'states.id')
    ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
    ->select(
        'users.id',
        'users.name',
        'users.email',
        'users.role',
        'users.country_id',
        'users.state_id',
        'users.lga_id',
        'countries.name as country_name',
        'states.name as state_name',
        'lgas.name as lga_name'
    )
    ->limit(20)
    ->get();

echo "<table>";
echo "<tr><th>ID</th><th>Name</th><th>Role</th><th>Country ID</th><th>Country</th><th>State ID</th><th>State</th><th>LGA ID</th><th>LGA</th></tr>";

foreach ($sampleUsers as $user) {
    $countryStatus = $user->country_id ? ($user->country_name ? '✓' : '❌ Missing') : '-';
    $stateStatus = $user->state_id ? ($user->state_name ? '✓' : '❌ Missing') : '-';
    $lgaStatus = $user->lga_id ? ($user->lga_name ? '✓' : '❌ Missing') : '-';
    
    echo "<tr>";
    echo "<td>{$user->id}</td>";
    echo "<td>{$user->name}</td>";
    echo "<td>{$user->role}</td>";
    echo "<td>" . ($user->country_id ?? '-') . "</td>";
    echo "<td>" . ($user->country_name ?? $countryStatus) . "</td>";
    echo "<td>" . ($user->state_id ?? '-') . "</td>";
    echo "<td>" . ($user->state_name ?? $stateStatus) . "</td>";
    echo "<td>" . ($user->lga_id ?? '-') . "</td>";
    echo "<td>" . ($user->lga_name ?? $lgaStatus) . "</td>";
    echo "</tr>";
}

echo "</table>";

// Check if location tables have data
echo "<h3>Location Tables Status</h3>";
echo "<table>";
echo "<tr><th>Table</th><th>Count</th></tr>";
echo "<tr><td>Countries</td><td>" . DB::table('countries')->count() . "</td></tr>";
echo "<tr><td>States</td><td>" . DB::table('states')->count() . "</td></tr>";
echo "<tr><td>LGAs</td><td>" . DB::table('lgas')->count() . "</td></tr>";
echo "</table>";

echo "<br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER CHECKING!</p>";
