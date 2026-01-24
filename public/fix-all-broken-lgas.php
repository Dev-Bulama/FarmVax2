<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<style>body{font-family:Arial;padding:20px;} table{border-collapse:collapse;width:100%;margin:20px 0;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f4f4f4;} .good{color:green;font-weight:bold;} .bad{color:red;font-weight:bold;}</style>";

echo "<h1>Fix Broken LGA References</h1>";

// Get Lagos LGAs
echo "<h2>Step 1: Available Lagos LGAs</h2>";
$lagosLGAs = DB::table('lgas')
    ->where('state_id', 25)
    ->orderBy('name')
    ->get();

echo "<p>Found <strong>{$lagosLGAs->count()}</strong> LGAs for Lagos State:</p>";
echo "<table>";
echo "<tr><th>LGA ID</th><th>LGA Name</th></tr>";
foreach ($lagosLGAs as $lga) {
    $highlight = (stripos($lga->name, 'Mainland') !== false || stripos($lga->name, 'Island') !== false) ? 'style="background-color: #fff3cd;"' : '';
    echo "<tr {$highlight}><td>{$lga->id}</td><td>{$lga->name}</td></tr>";
}
echo "</table>";

// Get users with broken LGAs
echo "<h2>Step 2: Users with Broken LGA IDs</h2>";
$brokenUsers = DB::table('users')
    ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
    ->whereNotNull('users.lga_id')
    ->whereNull('lgas.id')
    ->select('users.id', 'users.name', 'users.email', 'users.lga_id', 'users.state_id')
    ->get();

echo "<p class='bad'>Found {$brokenUsers->count()} users with broken LGA references:</p>";
echo "<table>";
echo "<tr><th>User ID</th><th>Name</th><th>Email</th><th>Broken LGA ID</th><th>State</th></tr>";
foreach ($brokenUsers as $user) {
    echo "<tr><td>{$user->id}</td><td>{$user->name}</td><td>{$user->email}</td><td>{$user->lga_id}</td><td>{$user->state_id}</td></tr>";
}
echo "</table>";

// Fix Option 1: Set to first available Lagos LGA
echo "<h2>Step 3: Auto-Fix (Set to Lagos Mainland)</h2>";

$lagosMainland = $lagosLGAs->first(function($lga) {
    return stripos($lga->name, 'Mainland') !== false;
});

if (!$lagosMainland) {
    // Fallback to first Lagos LGA
    $lagosMainland = $lagosLGAs->first();
}

if ($lagosMainland) {
    echo "<p>Will set broken Lagos users to: <strong>{$lagosMainland->name}</strong> (ID: {$lagosMainland->id})</p>";
    
    echo "<h4>Updating users...</h4>";
    
    foreach ($brokenUsers as $user) {
        // Only fix Lagos users
        if ($user->state_id == 25) {
            $updated = DB::table('users')
                ->where('id', $user->id)
                ->update(['lga_id' => $lagosMainland->id]);
            
            if ($updated) {
                echo "<p class='good'>✓ Updated User {$user->id} ({$user->name}) - LGA set to {$lagosMainland->name}</p>";
            }
        }
    }
    
    echo "<h3 class='good'>✓✓✓ ALL USERS FIXED!</h3>";
    
    // Verify
    echo "<h2>Step 4: Verification</h2>";
    $stillBroken = DB::table('users')
        ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
        ->whereNotNull('users.lga_id')
        ->whereNull('lgas.id')
        ->count();
    
    if ($stillBroken == 0) {
        echo "<p class='good'>✓ SUCCESS! No broken LGA references remaining!</p>";
        echo "<p class='good'>Now check your admin users page - ALL locations should show!</p>";
    } else {
        echo "<p class='bad'>Still {$stillBroken} broken references (might be non-Lagos users)</p>";
    }
    
    // Show sample fixed users
    echo "<h3>Sample Fixed Users (First 5):</h3>";
    $fixedUsers = DB::table('users')
        ->join('countries', 'users.country_id', '=', 'countries.id')
        ->join('states', 'users.state_id', '=', 'states.id')
        ->join('lgas', 'users.lga_id', '=', 'lgas.id')
        ->whereNotNull('users.lga_id')
        ->select(
            'users.id',
            'users.name',
            'lgas.name as lga',
            'states.name as state',
            'countries.name as country'
        )
        ->limit(5)
        ->get();
    
    echo "<table>";
    echo "<tr><th>User ID</th><th>Name</th><th>Location</th></tr>";
    foreach ($fixedUsers as $user) {
        echo "<tr><td>{$user->id}</td><td>{$user->name}</td><td>{$user->lga}, {$user->state}, {$user->country}</td></tr>";
    }
    echo "</table>";
    
} else {
    echo "<p class='bad'>ERROR: Could not find Lagos Mainland LGA!</p>";
}

echo "<br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER RUNNING!</p>";