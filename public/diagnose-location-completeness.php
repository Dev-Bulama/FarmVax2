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
.warning{color:orange;font-weight:bold;}
</style>";

echo "<h1>Location Data Completeness Report</h1>";

// 1. Check Countries
echo "<h2>1. Countries</h2>";
$countries = DB::table('countries')->get();

echo "<table>";
echo "<tr><th>Country ID</th><th>Country Name</th><th>Code</th><th>States</th><th>Total LGAs</th><th>Status</th></tr>";

foreach ($countries as $country) {
    $stateCount = DB::table('states')->where('country_id', $country->id)->count();
    $lgaCount = DB::table('lgas')
        ->join('states', 'lgas.state_id', '=', 'states.id')
        ->where('states.country_id', $country->id)
        ->count();
    
    $status = $stateCount > 0 ? '<span class="good">✓ Has States</span>' : '<span class="bad">✗ No States</span>';
    
    echo "<tr>";
    echo "<td>{$country->id}</td>";
    echo "<td>{$country->name}</td>";
    echo "<td>{$country->code}</td>";
    echo "<td>{$stateCount}</td>";
    echo "<td>{$lgaCount}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

// 2. Check States
echo "<h2>2. States (and their LGA counts)</h2>";
$states = DB::table('states')
    ->leftJoin('countries', 'states.country_id', '=', 'countries.id')
    ->select('states.*', 'countries.name as country_name')
    ->orderBy('countries.name')
    ->orderBy('states.name')
    ->get();

$statesWithoutLGAs = [];

echo "<table>";
echo "<tr><th>State ID</th><th>State Name</th><th>Country</th><th>LGA Count</th><th>Status</th></tr>";

foreach ($states as $state) {
    $lgaCount = DB::table('lgas')->where('state_id', $state->id)->count();
    
    if ($lgaCount == 0) {
        $statesWithoutLGAs[] = $state;
        $status = '<span class="bad">✗ NO LGAs</span>';
        $rowStyle = 'background-color:#ffcccc;';
    } elseif ($lgaCount < 5) {
        $status = '<span class="warning">⚠ Few LGAs</span>';
        $rowStyle = 'background-color:#fff3cd;';
    } else {
        $status = '<span class="good">✓ Has LGAs</span>';
        $rowStyle = '';
    }
    
    echo "<tr style='{$rowStyle}'>";
    echo "<td>{$state->id}</td>";
    echo "<td>{$state->name}</td>";
    echo "<td>{$state->country_name}</td>";
    echo "<td>{$lgaCount}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

// 3. Summary of States WITHOUT LGAs
if (count($statesWithoutLGAs) > 0) {
    echo "<h2 class='bad'>3. States WITHOUT LGAs ({count($statesWithoutLGAs)} states)</h2>";
    echo "<table>";
    echo "<tr><th>State ID</th><th>State Name</th><th>Country</th></tr>";
    
    foreach ($statesWithoutLGAs as $state) {
        echo "<tr>";
        echo "<td>{$state->id}</td>";
        echo "<td>{$state->name}</td>";
        echo "<td>{$state->country_name}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<h3>Action Required:</h3>";
    echo "<p>These states need LGAs populated. See the seeder script below to add them.</p>";
} else {
    echo "<h2 class='good'>3. All States Have LGAs ✓</h2>";
}

// 4. Check for orphaned LGAs
echo "<h2>4. Orphaned LGAs (LGAs pointing to non-existent states)</h2>";
$orphanedLGAs = DB::table('lgas')
    ->leftJoin('states', 'lgas.state_id', '=', 'states.id')
    ->whereNull('states.id')
    ->select('lgas.*')
    ->get();

if ($orphanedLGAs->count() > 0) {
    echo "<p class='bad'>Found {$orphanedLGAs->count()} orphaned LGAs!</p>";
    echo "<table>";
    echo "<tr><th>LGA ID</th><th>LGA Name</th><th>State ID (Invalid)</th></tr>";
    foreach ($orphanedLGAs as $lga) {
        echo "<tr><td>{$lga->id}</td><td>{$lga->name}</td><td>{$lga->state_id}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='good'>✓ No orphaned LGAs found</p>";
}

// 5. Statistics Summary
echo "<h2>5. Overall Statistics</h2>";
echo "<table>";
echo "<tr><th>Metric</th><th>Count</th></tr>";
echo "<tr><td>Total Countries</td><td>" . DB::table('countries')->count() . "</td></tr>";
echo "<tr><td>Total States</td><td>" . DB::table('states')->count() . "</td></tr>";
echo "<tr><td>Total LGAs</td><td>" . DB::table('lgas')->count() . "</td></tr>";
echo "<tr><td>States WITHOUT LGAs</td><td>" . count($statesWithoutLGAs) . "</td></tr>";
echo "<tr><td>Average LGAs per State</td><td>" . round(DB::table('lgas')->count() / DB::table('states')->count(), 2) . "</td></tr>";
echo "</table>";

// 6. Sample data from each state
echo "<h2>6. Sample LGAs per State (First 3 states)</h2>";
$sampleStates = DB::table('states')->limit(3)->get();

foreach ($sampleStates as $state) {
    $lgas = DB::table('lgas')->where('state_id', $state->id)->get();
    
    echo "<h3>{$state->name} State ({$lgas->count()} LGAs)</h3>";
    
    if ($lgas->count() > 0) {
        echo "<ul>";
        foreach ($lgas as $lga) {
            echo "<li>{$lga->name} (ID: {$lga->id})</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='bad'>No LGAs found for this state!</p>";
    }
}

echo "<br><p style='color:red;'><strong>DELETE THIS FILE AFTER USE!</strong></p>";