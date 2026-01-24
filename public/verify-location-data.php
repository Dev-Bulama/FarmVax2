<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "<style>
body{font-family:Arial;padding:20px;}
table{border-collapse:collapse;width:100%;margin:20px 0;}
th,td{border:1px solid #ddd;padding:8px;text-align:left;}
th{background:#f4f4f4;}
.good{color:green;font-weight:bold;}
.bad{color:red;font-weight:bold;}
</style>";

echo "<h1>Location Data Verification</h1>";

// Test exactly like the controller does
echo "<h2>Test 1: Using Eloquent with Eager Loading</h2>";
try {
    $users = User::with(['country', 'state', 'lga'])->limit(5)->get();
    
    echo "<p>Retrieved {$users->count()} users</p>";
    
    echo "<table>";
    echo "<tr>
        <th>User ID</th>
        <th>Name</th>
        <th>country_id (DB)</th>
        <th>Country Relationship</th>
        <th>state_id (DB)</th>
        <th>State Relationship</th>
        <th>lga_id (DB)</th>
        <th>LGA Relationship</th>
    </tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>{$user->id}</td>";
        echo "<td>{$user->name}</td>";
        echo "<td>" . ($user->country_id ?? '<span class="bad">NULL</span>') . "</td>";
        
        // Check country
        if (is_object($user->country)) {
            echo "<td class='good'>✓ Object: {$user->country->name}</td>";
        } elseif (is_string($user->country)) {
            echo "<td class='bad'>✗ STRING: {$user->country}</td>";
        } else {
            echo "<td class='bad'>✗ NULL</td>";
        }
        
        echo "<td>" . ($user->state_id ?? '<span class="bad">NULL</span>') . "</td>";
        
        // Check state
        if (is_object($user->state)) {
            echo "<td class='good'>✓ Object: {$user->state->name}</td>";
        } elseif (is_string($user->state)) {
            echo "<td class='bad'>✗ STRING: {$user->state}</td>";
        } else {
            echo "<td class='bad'>✗ NULL</td>";
        }
        
        echo "<td>" . ($user->lga_id ?? '<span class="bad">NULL</span>') . "</td>";
        
        // Check lga
        if (is_object($user->lga)) {
            echo "<td class='good'>✓ Object: {$user->lga->name}</td>";
        } elseif (is_string($user->lga)) {
            echo "<td class='bad'>✗ STRING: {$user->lga}</td>";
        } else {
            echo "<td class='bad'>✗ NULL</td>";
        }
        
        echo "</tr>";
    }
    
    echo "</table>";
} catch (\Exception $e) {
    echo "<p class='bad'>ERROR: {$e->getMessage()}</p>";
}

// Test 2: Raw database query
echo "<h2>Test 2: Raw Database Query (What Actually Exists)</h2>";
$raw = \DB::select("
    SELECT 
        u.id, 
        u.name,
        u.country_id,
        c.name as country_name,
        u.state_id,
        s.name as state_name,
        u.lga_id,
        l.name as lga_name
    FROM users u
    LEFT JOIN countries c ON u.country_id = c.id
    LEFT JOIN states s ON u.state_id = s.id
    LEFT JOIN lgas l ON u.lga_id = l.id
    LIMIT 10
");

echo "<table>";
echo "<tr><th>ID</th><th>Name</th><th>Country ID → Name</th><th>State ID → Name</th><th>LGA ID → Name</th><th>Status</th></tr>";
foreach ($raw as $row) {
    $status = ($row->country_name && $row->state_name && $row->lga_name) 
        ? '<span class="good">COMPLETE</span>' 
        : '<span class="bad">INCOMPLETE</span>';
    
    echo "<tr>";
    echo "<td>{$row->id}</td>";
    echo "<td>{$row->name}</td>";
    echo "<td>{$row->country_id} → " . ($row->country_name ?? '<span class="bad">NULL</span>') . "</td>";
    echo "<td>{$row->state_id} → " . ($row->state_name ?? '<span class="bad">NULL</span>') . "</td>";
    echo "<td>{$row->lga_id} → " . ($row->lga_name ?? '<span class="bad">NULL</span>') . "</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}
echo "</table>";

// Test 3: Check User model relationships
echo "<h2>Test 3: Check User Model Relationships</h2>";
echo "<pre>";
$user = User::first();
if ($user) {
    echo "Sample User ID: {$user->id}\n";
    echo "Has 'country' method: " . (method_exists($user, 'country') ? 'YES' : 'NO') . "\n";
    echo "Has 'state' method: " . (method_exists($user, 'state') ? 'YES' : 'NO') . "\n";
    echo "Has 'lga' method: " . (method_exists($user, 'lga') ? 'YES' : 'NO') . "\n";
    
    // Try to load relationships
    echo "\nTrying to load country relationship...\n";
    try {
        $country = $user->country;
        echo "Type: " . gettype($country) . "\n";
        if (is_object($country)) {
            echo "Class: " . get_class($country) . "\n";
            echo "Has 'name' property: " . (property_exists($country, 'name') ? 'YES' : 'NO') . "\n";
        }
    } catch (\Exception $e) {
        echo "ERROR: {$e->getMessage()}\n";
    }
}
echo "</pre>";

// Test 4: Check tables structure
echo "<h2>Test 4: Database Structure Check</h2>";
echo "<pre>";
echo "Countries table records: " . \DB::table('countries')->count() . "\n";
echo "States table records: " . \DB::table('states')->count() . "\n";
echo "LGAs table records: " . \DB::table('lgas')->count() . "\n";
echo "\nUsers table columns:\n";
$columns = \DB::select("DESCRIBE users");
foreach ($columns as $col) {
    if (in_array($col->Field, ['country_id', 'state_id', 'lga_id', 'country', 'state', 'lga', 'city'])) {
        echo "  - {$col->Field} ({$col->Type}) " . ($col->Null == 'YES' ? 'nullable' : 'not null') . "\n";
    }
}
echo "</pre>";

echo "<br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER CHECKING!</p>";