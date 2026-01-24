<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<style>
body{font-family:Arial;padding:20px;}
table{border-collapse:collapse;width:100%;margin:20px 0;}
th,td{border:1px solid #ddd;padding:8px;text-align:left;}
th{background:#f4f4f4;}
.good{color:green;font-weight:bold;}
.bad{color:red;font-weight:bold;}
</style>";

echo "<h1>Reset Default Locations to NULL</h1>";

// Find the first Lagos LGA (which we used as default)
$defaultLGA = DB::table('lgas')->where('state_id', 25)->first();

if (!$defaultLGA) {
    echo "<p class='bad'>Could not find default LGA</p>";
    exit;
}

echo "<p>Default location that was set: <strong>Nigeria (1) → Lagos (25) → {$defaultLGA->name} ({$defaultLGA->id})</strong></p>";

// Find users with this exact default location
$usersWithDefault = DB::table('users')
    ->where('country_id', 1)
    ->where('state_id', 25)
    ->where('lga_id', $defaultLGA->id)
    ->get(['id', 'name', 'email', 'created_at']);

echo "<h2>Found {$usersWithDefault->count()} users with default location</h2>";

if ($usersWithDefault->count() > 0) {
    echo "<table>";
    echo "<tr><th>User ID</th><th>Name</th><th>Email</th><th>Registered</th><th>Action</th></tr>";
    
    foreach ($usersWithDefault as $user) {
        $registeredDate = date('Y-m-d', strtotime($user->created_at));
        
        // Check if user registered BEFORE we set the default (they might be genuine Lagos users)
        $beforeDefault = strtotime($user->created_at) < strtotime('2026-01-09'); // Adjust this date
        
        if ($beforeDefault) {
            echo "<tr style='background-color:#fffbcc;'>";
            echo "<td>{$user->id}</td>";
            echo "<td>{$user->name}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$registeredDate}</td>";
            echo "<td><span class='good'>KEEP (Registered before default was set)</span></td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td>{$user->id}</td>";
            echo "<td>{$user->name}</td>";
            echo "<td>{$user->email}</td>";
            echo "<td>{$registeredDate}</td>";
            echo "<td><span class='bad'>RESET TO NULL</span></td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Ask for confirmation
    echo "<h2>Do you want to proceed?</h2>";
    echo "<form method='POST'>";
    echo "<p><strong>This will set location to NULL for users registered AFTER 2026-01-09</strong></p>";
    echo "<button type='submit' name='confirm' value='yes' style='background:red;color:white;padding:10px 20px;font-size:16px;border:none;border-radius:5px;cursor:pointer;'>YES - RESET TO NULL</button>";
    echo "</form>";
    
    if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
        echo "<h2 style='color:red;'>RESETTING...</h2>";
        
        // Reset to NULL for users registered after default was set
        $resetCount = DB::table('users')
            ->where('country_id', 1)
            ->where('state_id', 25)
            ->where('lga_id', $defaultLGA->id)
            ->where('created_at', '>', '2026-01-09 00:00:00')
            ->update([
                'country_id' => null,
                'state_id' => null,
                'lga_id' => null
            ]);
        
        echo "<p class='good'>✓ Reset {$resetCount} users to NULL location</p>";
        echo "<p>Users who registered BEFORE the default was set kept their Lagos location (they might be genuine)</p>";
        echo "<p><a href='?'>Refresh to see updated list</a></p>";
    }
} else {
    echo "<p class='good'>No users found with the default location!</p>";
}

echo "<br><p style='color:red;'><strong>DELETE THIS FILE AFTER USE!</strong></p>";