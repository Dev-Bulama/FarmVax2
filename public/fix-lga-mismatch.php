<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<style>body{font-family:Arial;padding:20px;} table{border-collapse:collapse;width:100%;margin:20px 0;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f4f4f4;} .good{color:green;} .bad{color:red;}</style>";

echo "<h2>LGA Data Fix for Lagos State</h2>";

// Check what LGAs exist for Lagos (state_id = 25)
echo "<h3>LGAs Available for Lagos State (ID: 25):</h3>";
$lagosLGAs = DB::table('lgas')
    ->where('state_id', 25)
    ->orderBy('name')
    ->get();

if ($lagosLGAs->count() > 0) {
    echo "<p class='good'>Found {$lagosLGAs->count()} LGAs for Lagos</p>";
    echo "<table>";
    echo "<tr><th>LGA ID</th><th>LGA Name</th><th>State ID</th></tr>";
    foreach ($lagosLGAs as $lga) {
        $highlight = ($lga->name == 'Ikeja' || stripos($lga->name, 'Ikeja') !== false) ? 'style="background-color: #fff3cd; font-weight: bold;"' : '';
        echo "<tr {$highlight}><td>{$lga->id}</td><td>{$lga->name}</td><td>{$lga->state_id}</td></tr>";
    }
    echo "</table>";
    
    // Find Ikeja
    $ikeja = DB::table('lgas')
        ->where('state_id', 25)
        ->where('name', 'LIKE', '%Ikeja%')
        ->first();
    
    if ($ikeja) {
        echo "<h3>Found Ikeja LGA:</h3>";
        echo "<p><strong>Correct LGA ID for Ikeja:</strong> <span class='good'>{$ikeja->id}</span></p>";
        echo "<p><strong>Name:</strong> {$ikeja->name}</p>";
        
        // Update user 244 with correct LGA
        echo "<h3>Updating User 244:</h3>";
        $updated = DB::table('users')
            ->where('id', 244)
            ->update([
                'country_id' => 1,
                'state_id' => 25,
                'lga_id' => $ikeja->id
            ]);
        
        if ($updated || DB::table('users')->where('id', 244)->where('lga_id', $ikeja->id)->exists()) {
            echo "<p class='good'>✓ User 244 updated with correct Ikeja LGA ID!</p>";
            
            // Verify
            $user = DB::table('users')
                ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
                ->leftJoin('states', 'users.state_id', '=', 'states.id')
                ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
                ->where('users.id', 244)
                ->select('countries.name as country', 'states.name as state', 'lgas.name as lga')
                ->first();
            
            echo "<p><strong>New Location:</strong> <span class='good'>{$user->lga}, {$user->state}, {$user->country}</span></p>";
            echo "<p class='good'>✓✓✓ NOW CHECK THE ADMIN USERS PAGE - LOCATION SHOULD SHOW!</p>";
        }
    } else {
        echo "<p class='bad'>✗ Ikeja not found in Lagos LGAs. Check the table above for correct name.</p>";
    }
} else {
    echo "<p class='bad'>✗ NO LGAs found for Lagos State! Your lgas table might be missing data.</p>";
    
    // Check total LGAs in database
    $totalLGAs = DB::table('lgas')->count();
    echo "<p>Total LGAs in database: {$totalLGAs}</p>";
    
    if ($totalLGAs == 0) {
        echo "<p class='bad'>Your lgas table is EMPTY! You need to seed it with data.</p>";
    }
}

// Show LGA 493 if it exists
echo "<h3>Checking LGA ID 493 (Your Current LGA):</h3>";
$lga493 = DB::table('lgas')
    ->leftJoin('states', 'lgas.state_id', '=', 'states.id')
    ->where('lgas.id', 493)
    ->select('lgas.*', 'states.name as state_name')
    ->first();

if ($lga493) {
    echo "<p>LGA 493 exists:</p>";
    echo "<ul>";
    echo "<li><strong>Name:</strong> {$lga493->name}</li>";
    echo "<li><strong>State ID:</strong> {$lga493->state_id}</li>";
    echo "<li><strong>State Name:</strong> {$lga493->state_name}</li>";
    echo "</ul>";
    echo "<p class='bad'>This LGA is NOT in Lagos State! That's why the location isn't showing correctly.</p>";
} else {
    echo "<p class='bad'>LGA ID 493 does NOT exist in your database!</p>";
}

echo "<br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER RUNNING!</p>";