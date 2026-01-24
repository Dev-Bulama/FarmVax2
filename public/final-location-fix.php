<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>FINAL LOCATION FIX</h1>";

// Check current state
$usersWithoutLocation = DB::table('users')
    ->whereNull('country_id')
    ->orWhereNull('state_id')
    ->orWhereNull('lga_id')
    ->count();

echo "<p><strong>Users WITHOUT location: {$usersWithoutLocation}</strong></p>";

if ($usersWithoutLocation > 0) {
    // Get first Lagos LGA
    $lga = DB::table('lgas')->where('state_id', 25)->first();
    
    if (!$lga) {
        echo "<p style='color:red;'>ERROR: No Lagos LGAs in database!</p>";
        exit;
    }
    
    echo "<p>Setting all users to: <strong>{$lga->name}, Lagos, Nigeria</strong></p>";
    
    // Fix ALL users at once
    DB::statement("
        UPDATE users 
        SET country_id = 1, 
            state_id = 25, 
            lga_id = {$lga->id}
        WHERE country_id IS NULL 
           OR state_id IS NULL 
           OR lga_id IS NULL
    ");
    
    echo "<h2 style='color:green;'>✓ FIXED!</h2>";
}

// Verify with actual query like the controller uses
$testUsers = DB::table('users')
    ->join('countries', 'users.country_id', '=', 'countries.id')
    ->join('states', 'users.state_id', '=', 'states.id')
    ->join('lgas', 'users.lga_id', '=', 'lgas.id')
    ->select('users.id', 'users.name', 'lgas.name as lga', 'states.name as state', 'countries.name as country')
    ->limit(5)
    ->get();

echo "<h2>Sample Users (How Admin Will See):</h2>";
echo "<table border='1' style='border-collapse:collapse;'>";
echo "<tr><th>ID</th><th>Name</th><th>Location</th></tr>";
foreach ($testUsers as $user) {
    echo "<tr><td>{$user->id}</td><td>{$user->name}</td><td>{$user->lga}, {$user->state}, {$user->country}</td></tr>";
}
echo "</table>";

// Clear Laravel cache
try {
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    echo "<p style='color:green;'>✓ Cache cleared</p>";
} catch (\Exception $e) {
    echo "<p style='color:orange;'>Cache clear skipped (run manually if needed)</p>";
}

echo "<h2 style='color:green;'>✓✓✓ NOW HARD REFRESH YOUR BROWSER (Ctrl+Shift+R) AND CHECK ADMIN!</h2>";
echo "<p style='color:red;'><strong>DELETE THIS FILE!</strong></p>";