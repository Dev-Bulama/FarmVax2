<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Country;
use App\Models\State;
use App\Models\Lga;

echo "<style>
    body { font-family: Arial; padding: 20px; background: #f5f5f5; }
    .success { color: #10b981; font-weight: bold; }
    .error { color: #ef4444; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; background: white; }
    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    th { background: #11455b; color: white; }
</style>";

echo "<h1 style='color: #11455b;'>📍 Location Data Check</h1>";

// Check Countries
$countries = Country::count();
echo "<h2>Countries</h2>";
if ($countries > 0) {
    echo "<p class='success'>✅ Found {$countries} countries</p>";
    $countryList = Country::orderBy('name')->get();
    echo "<table><tr><th>ID</th><th>Name</th><th>States Count</th></tr>";
    foreach ($countryList as $country) {
        $stateCount = State::where('country_id', $country->id)->count();
        echo "<tr><td>{$country->id}</td><td>{$country->name}</td><td>{$stateCount}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ No countries found!</p>";
}

// Check States
$states = State::count();
echo "<h2>States</h2>";
if ($states > 0) {
    echo "<p class='success'>✅ Found {$states} states</p>";
    echo "<p><strong>Nigeria States:</strong></p>";
    $nigeriaStates = State::where('country_id', function($query) {
        $query->select('id')->from('countries')->where('name', 'Nigeria')->limit(1);
    })->orderBy('name')->get();
    
    if ($nigeriaStates->count() > 0) {
        echo "<table><tr><th>ID</th><th>Name</th><th>LGAs Count</th></tr>";
        foreach ($nigeriaStates as $state) {
            $lgaCount = Lga::where('state_id', $state->id)->count();
            echo "<tr><td>{$state->id}</td><td>{$state->name}</td><td>{$lgaCount}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ No Nigerian states found!</p>";
    }
} else {
    echo "<p class='error'>❌ No states found!</p>";
}

// Check LGAs
$lgas = Lga::count();
echo "<h2>LGAs</h2>";
if ($lgas > 0) {
    echo "<p class='success'>✅ Found {$lgas} LGAs</p>";
    echo "<p><strong>Sample LGAs from Lagos:</strong></p>";
    $lagosLgas = Lga::where('state_id', function($query) {
        $query->select('id')->from('states')->where('name', 'Lagos')->limit(1);
    })->orderBy('name')->limit(10)->get();
    
    if ($lagosLgas->count() > 0) {
        echo "<table><tr><th>ID</th><th>Name</th></tr>";
        foreach ($lagosLgas as $lga) {
            echo "<tr><td>{$lga->id}</td><td>{$lga->name}</td></tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p class='error'>❌ No LGAs found!</p>";
}

// Test API Endpoints
echo "<h2>API Endpoint Tests</h2>";
echo "<p>Test these URLs in your browser:</p>";
echo "<ul>";
echo "<li><a href='/api/countries' target='_blank'>/api/countries</a></li>";
$nigeria = Country::where('name', 'Nigeria')->first();
if ($nigeria) {
    echo "<li><a href='/api/states/{$nigeria->id}' target='_blank'>/api/states/{$nigeria->id} (Nigeria)</a></li>";
    $lagos = State::where('name', 'Lagos')->first();
    if ($lagos) {
        echo "<li><a href='/api/lgas/{$lagos->id}' target='_blank'>/api/lgas/{$lagos->id} (Lagos)</a></li>";
    }
}
echo "</ul>";

echo "<br><br><p style='color: red; font-weight: bold;'>⚠️ DELETE THIS FILE AFTER CHECKING!</p>";