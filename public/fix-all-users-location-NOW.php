<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>Setting Default Location for ALL Users</h1>";

// Get first available Lagos LGA
$lagosLGA = DB::table('lgas')
    ->where('state_id', 25)
    ->first();

if (!$lagosLGA) {
    echo "<p style='color:red;'>ERROR: No Lagos LGAs found!</p>";
    exit;
}

echo "<p>Using default location: <strong>{$lagosLGA->name}, Lagos, Nigeria</strong></p>";

// Update ALL users without location
$updated = DB::table('users')
    ->whereNull('country_id')
    ->orWhereNull('state_id')
    ->orWhereNull('lga_id')
    ->update([
        'country_id' => 1,           // Nigeria
        'state_id' => 25,            // Lagos
        'lga_id' => $lagosLGA->id    // First Lagos LGA
    ]);

echo "<h2 style='color:green;'>✓ DONE! Updated {$updated} users</h2>";

// Verify
$usersWithLocation = DB::table('users')
    ->whereNotNull('country_id')
    ->whereNotNull('state_id')
    ->whereNotNull('lga_id')
    ->count();

echo "<p><strong>Users with location now: {$usersWithLocation}</strong></p>";

echo "<h3 style='color:green;'>✓✓✓ REFRESH YOUR ADMIN PAGE - ALL LOCATIONS WILL NOW SHOW!</h3>";
echo "<p style='color:red;'><strong>DELETE THIS FILE NOW!</strong></p>";