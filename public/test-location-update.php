<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get your volunteer user ID (replace with your actual ID)
$yourUserId = auth()->check() ? auth()->id() : 244; // Replace 244 with your actual user ID

echo "<h2>Testing Location Update for User ID: {$yourUserId}</h2>";

// Get current data
$user = DB::table('users')->where('id', $yourUserId)->first();

echo "<h3>Current Data:</h3>";
echo "<pre>";
echo "Country ID: " . ($user->country_id ?? 'NULL') . "\n";
echo "State ID: " . ($user->state_id ?? 'NULL') . "\n";
echo "LGA ID: " . ($user->lga_id ?? 'NULL') . "\n";
echo "</pre>";

// Try to update with sample location (Nigeria -> Lagos -> Ikeja)
$updated = DB::table('users')
    ->where('id', $yourUserId)
    ->update([
        'country_id' => 1, // Nigeria
        'state_id' => 25,  // Lagos
        'lga_id' => 493,   // Ikeja
    ]);

echo "<h3>Update Result:</h3>";
if ($updated) {
    echo "<p style='color:green;'>✓ Successfully updated location!</p>";
    
    // Get updated data
    $user = DB::table('users')
        ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
        ->leftJoin('states', 'users.state_id', '=', 'states.id')
        ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
        ->where('users.id', $yourUserId)
        ->select('users.*', 'countries.name as country_name', 'states.name as state_name', 'lgas.name as lga_name')
        ->first();
    
    echo "<h3>New Location:</h3>";
    echo "<pre>";
    echo "Country: " . ($user->country_name ?? 'NULL') . "\n";
    echo "State: " . ($user->state_name ?? 'NULL') . "\n";
    echo "LGA: " . ($user->lga_name ?? 'NULL') . "\n";
    echo "</pre>";
    
    echo "<p>Now check the admin users page - your location should show!</p>";
} else {
    echo "<p style='color:red;'>✗ Update failed or no changes made</p>";
}

echo "<br><p style='color:red;'><strong>⚠️ DELETE THIS FILE AFTER TESTING!</strong></p>";