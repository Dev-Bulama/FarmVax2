<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<style>body{font-family:Arial;padding:20px;} .good{color:green;} .bad{color:red;}</style>";
echo "<h2>Registration Flow Diagnostic</h2>";

// Check recent registrations
echo "<h3>Recent 10 Registrations:</h3>";
$recentUsers = DB::table('users')
    ->select('id', 'name', 'email', 'role', 'country_id', 'state_id', 'lga_id', 'created_at')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "<table border='1' style='border-collapse:collapse;width:100%;'>";
echo "<tr><th>ID</th><th>Name</th><th>Role</th><th>Country ID</th><th>State ID</th><th>LGA ID</th><th>Registered</th><th>Status</th></tr>";

foreach ($recentUsers as $user) {
    $hasLocation = $user->country_id && $user->state_id && $user->lga_id;
    $status = $hasLocation ? "<span class='good'>✓ HAS LOCATION</span>" : "<span class='bad'>✗ NO LOCATION</span>";
    
    echo "<tr>";
    echo "<td>{$user->id}</td>";
    echo "<td>{$user->name}</td>";
    echo "<td>{$user->role}</td>";
    echo "<td>" . ($user->country_id ?? '<span class="bad">NULL</span>') . "</td>";
    echo "<td>" . ($user->state_id ?? '<span class="bad">NULL</span>') . "</td>";
    echo "<td>" . ($user->lga_id ?? '<span class="bad">NULL</span>') . "</td>";
    echo "<td>" . date('Y-m-d H:i', strtotime($user->created_at)) . "</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}
echo "</table>";

// Check if users table has location columns
echo "<h3>Users Table Structure (Location Columns):</h3>";
$columns = DB::select("SHOW COLUMNS FROM users WHERE Field IN ('country_id', 'state_id', 'lga_id', 'address', 'latitude', 'longitude')");

echo "<table border='1' style='border-collapse:collapse;'>";
echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Default</th></tr>";
foreach ($columns as $col) {
    echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>" . ($col->Default ?? 'NULL') . "</td></tr>";
}
echo "</table>";

// Check location tables data
echo "<h3>Location Tables:</h3>";
echo "<p>Countries: <strong>" . DB::table('countries')->count() . "</strong></p>";
echo "<p>States: <strong>" . DB::table('states')->count() . "</strong></p>";
echo "<p>LGAs: <strong>" . DB::table('lgas')->count() . "</strong></p>";

echo "<br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER CHECKING!</p>";