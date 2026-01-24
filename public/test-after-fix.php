<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "<h1>Testing After Column Drop</h1>";

$users = User::with(['country', 'state', 'lga'])->limit(5)->get();

echo "<table border='1' style='border-collapse:collapse;'>";
echo "<tr><th>User</th><th>Country</th><th>State</th><th>LGA</th><th>Status</th></tr>";

foreach ($users as $user) {
    $country = is_object($user->country) ? $user->country->name : 'ERROR';
    $state = is_object($user->state) ? $user->state->name : 'ERROR';
    $lga = is_object($user->lga) ? $user->lga->name : 'ERROR';
    
    $status = (is_object($user->country) && is_object($user->state) && is_object($user->lga)) 
        ? '<span style="color:green;font-weight:bold;">✓ WORKING!</span>' 
        : '<span style="color:red;font-weight:bold;">✗ BROKEN</span>';
    
    echo "<tr>";
    echo "<td>{$user->name}</td>";
    echo "<td>{$country}</td>";
    echo "<td>{$state}</td>";
    echo "<td>{$lga}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2 style='color:green;'>If you see ✓ WORKING! - Delete this file and check your admin page!</h2>";