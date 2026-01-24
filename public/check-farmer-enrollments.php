<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Checking Farmer Enrollments Structure</h2>";

// Check table structure
$columns = DB::select("SHOW COLUMNS FROM farmer_enrollments");
echo "<h3>Table Columns:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
foreach ($columns as $col) {
    echo "<tr><td>{$col->Field}</td><td>{$col->Type}</td><td>{$col->Null}</td><td>{$col->Key}</td></tr>";
}
echo "</table>";

// Check sample data
echo "<h3>Sample Enrollments (First 10):</h3>";
$enrollments = DB::table('farmer_enrollments')
    ->join('users as farmer', 'farmer_enrollments.farmer_id', '=', 'farmer.id')
    ->join('volunteers', 'farmer_enrollments.enrolled_by', '=', 'volunteers.id')
    ->join('users as volunteer_user', 'volunteers.user_id', '=', 'volunteer_user.id')
    ->select(
        'farmer_enrollments.id',
        'farmer_enrollments.enrolled_by',
        'farmer.name as farmer_name',
        'volunteer_user.name as volunteer_name',
        'farmer_enrollments.created_at'
    )
    ->limit(10)
    ->get();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Enrolled By (Volunteer ID)</th><th>Farmer Name</th><th>Volunteer Name</th><th>Date</th></tr>";
foreach ($enrollments as $enroll) {
    echo "<tr><td>{$enroll->id}</td><td>{$enroll->enrolled_by}</td><td>{$enroll->farmer_name}</td><td>{$enroll->volunteer_name}</td><td>{$enroll->created_at}</td></tr>";
}
echo "</table>";

// Count by volunteer
echo "<h3>Enrollments by Volunteer:</h3>";
$counts = DB::table('farmer_enrollments')
    ->join('volunteers', 'farmer_enrollments.enrolled_by', '=', 'volunteers.id')
    ->join('users', 'volunteers.user_id', '=', 'users.id')
    ->select('users.name', 'volunteers.id', 'volunteers.referral_code', DB::raw('COUNT(*) as total'))
    ->groupBy('volunteers.id', 'users.name', 'volunteers.referral_code')
    ->orderBy('total', 'desc')
    ->get();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Volunteer</th><th>Volunteer ID</th><th>Referral Code</th><th>Total Enrollments</th></tr>";
foreach ($counts as $count) {
    echo "<tr><td>{$count->name}</td><td>{$count->id}</td><td>{$count->referral_code}</td><td><strong>{$count->total}</strong></td></tr>";
}
echo "</table>";

echo "<br><p style='color: red;'>⚠️ DELETE THIS FILE AFTER CHECKING!</p>";
