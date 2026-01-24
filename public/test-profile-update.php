<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get your user ID (replace with your actual volunteer user ID)
$yourUserId = 244; // REPLACE THIS WITH YOUR USER ID

echo "<style>body{font-family:Arial;padding:20px;} .good{color:green;font-weight:bold;} .bad{color:red;font-weight:bold;}</style>";
echo "<h2>Profile Update Diagnostic - User ID: {$yourUserId}</h2>";

// Get current user data
$user = DB::table('users')
    ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
    ->leftJoin('states', 'users.state_id', '=', 'states.id')
    ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
    ->where('users.id', $yourUserId)
    ->select(
        'users.*',
        'countries.name as country_name',
        'states.name as state_name',
        'lgas.name as lga_name'
    )
    ->first();

if (!$user) {
    echo "<p class='bad'>User not found!</p>";
    exit;
}

echo "<h3>Current User Data:</h3>";
echo "<table border='1' style='border-collapse:collapse;'>";
echo "<tr><th>Field</th><th>Value</th><th>Status</th></tr>";
echo "<tr><td>Name</td><td>{$user->name}</td><td>✓</td></tr>";
echo "<tr><td>Email</td><td>{$user->email}</td><td>✓</td></tr>";
echo "<tr><td>Country ID</td><td>" . ($user->country_id ?? '<span class="bad">NULL</span>') . "</td><td>" . ($user->country_id ? '<span class="good">✓ HAS VALUE</span>' : '<span class="bad">✗ MISSING</span>') . "</td></tr>";
echo "<tr><td>Country Name</td><td>" . ($user->country_name ?? '<span class="bad">NULL</span>') . "</td><td>" . ($user->country_name ? '<span class="good">✓</span>' : '<span class="bad">✗</span>') . "</td></tr>";
echo "<tr><td>State ID</td><td>" . ($user->state_id ?? '<span class="bad">NULL</span>') . "</td><td>" . ($user->state_id ? '<span class="good">✓ HAS VALUE</span>' : '<span class="bad">✗ MISSING</span>') . "</td></tr>";
echo "<tr><td>State Name</td><td>" . ($user->state_name ?? '<span class="bad">NULL</span>') . "</td><td>" . ($user->state_name ? '<span class="good">✓</span>' : '<span class="bad">✗</span>') . "</td></tr>";
echo "<tr><td>LGA ID</td><td>" . ($user->lga_id ?? '<span class="bad">NULL</span>') . "</td><td>" . ($user->lga_id ? '<span class="good">✓ HAS VALUE</span>' : '<span class="bad">✗ MISSING</span>') . "</td></tr>";
echo "<tr><td>LGA Name</td><td>" . ($user->lga_name ?? '<span class="bad">NULL</span>') . "</td><td>" . ($user->lga_name ? '<span class="good">✓</span>' : '<span class="bad">✗</span>') . "</td></tr>";
echo "</table>";

// Check if location columns are in fillable array
echo "<h3>User Model Check:</h3>";
$userModel = new \App\Models\User();
$fillable = $userModel->getFillable();
echo "<p>Fillable fields include:</p><ul>";
echo "<li>country_id: " . (in_array('country_id', $fillable) ? '<span class="good">✓ YES</span>' : '<span class="bad">✗ NO - ADD TO FILLABLE!</span>') . "</li>";
echo "<li>state_id: " . (in_array('state_id', $fillable) ? '<span class="good">✓ YES</span>' : '<span class="bad">✗ NO - ADD TO FILLABLE!</span>') . "</li>";
echo "<li>lga_id: " . (in_array('lga_id', $fillable) ? '<span class="good">✓ YES</span>' : '<span class="bad">✗ NO - ADD TO FILLABLE!</span>') . "</li>";
echo "</ul>";

// Test a manual update
echo "<h3>Test Manual Update:</h3>";
echo "<p>Attempting to update your location to: Nigeria → Lagos → Ikeja</p>";

try {
    $updated = DB::table('users')
        ->where('id', $yourUserId)
        ->update([
            'country_id' => 1,  // Nigeria
            'state_id' => 25,   // Lagos
            'lga_id' => 493,    // Ikeja
        ]);
    
    if ($updated) {
        echo "<p class='good'>✓ Manual update SUCCESSFUL!</p>";
        
        // Get updated data
        $updatedUser = DB::table('users')
            ->leftJoin('countries', 'users.country_id', '=', 'countries.id')
            ->leftJoin('states', 'users.state_id', '=', 'states.id')
            ->leftJoin('lgas', 'users.lga_id', '=', 'lgas.id')
            ->where('users.id', $yourUserId)
            ->select('countries.name as country', 'states.name as state', 'lgas.name as lga')
            ->first();
        
        echo "<p><strong>New Location:</strong> {$updatedUser->lga}, {$updatedUser->state}, {$updatedUser->country}</p>";
        echo "<p class='good'>Now refresh the admin users page - location should show!</p>";
    } else {
        echo "<p class='bad'>✗ Update returned 0 rows affected (maybe already had this location?)</p>";
    }
} catch (\Exception $e) {
    echo "<p class='bad'>✗ ERROR: " . $e->getMessage() . "</p>";
}

echo "<br><p style='color:red;'><strong>⚠️ DELETE THIS FILE AFTER CHECKING!</strong></p>";