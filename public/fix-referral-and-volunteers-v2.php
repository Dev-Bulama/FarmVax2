<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<style>body{font-family:Arial;padding:20px;} .success{color:#10b981;font-weight:bold;} .error{color:#ef4444;font-weight:bold;}</style>";
echo "<h2>🔧 Fixing Referral System & Volunteers (v2)</h2>";

try {
    // 1. Check referral_code column
    if (!Schema::hasColumn('volunteers', 'referral_code')) {
        DB::statement('ALTER TABLE volunteers ADD COLUMN referral_code VARCHAR(20) UNIQUE NULL AFTER user_id');
        echo "<p class='success'>✅ Added referral_code to volunteers table</p>";
    } else {
        echo "<p class='success'>✅ referral_code already exists</p>";
    }
    
    // 2. Check referred_by column
    if (!Schema::hasColumn('users', 'referred_by')) {
        DB::statement('ALTER TABLE users ADD COLUMN referred_by BIGINT UNSIGNED NULL AFTER role');
        echo "<p class='success'>✅ Added referred_by to users table</p>";
    } else {
        echo "<p class='success'>✅ referred_by already exists</p>";
    }
    
    // 3. Generate referral codes for existing volunteers
    $volunteers = DB::table('volunteers')->whereNull('referral_code')->get();
    $count = 0;
    foreach ($volunteers as $volunteer) {
        $code = 'FV' . strtoupper(substr(md5($volunteer->id . time() . rand()), 0, 8));
        DB::table('volunteers')->where('id', $volunteer->id)->update(['referral_code' => $code]);
        $count++;
    }
    echo "<p class='success'>✅ Generated {$count} referral codes</p>";
    
    // 4. Check volunteer_stats structure
    $statsColumns = DB::select("SHOW COLUMNS FROM volunteer_stats");
    $statsColumnNames = array_column($statsColumns, 'Field');
    
    if (!in_array('total_enrollments', $statsColumnNames)) {
        DB::statement('ALTER TABLE volunteer_stats ADD COLUMN total_enrollments INT NOT NULL DEFAULT 0 AFTER volunteer_id');
        echo "<p class='success'>✅ Added total_enrollments to volunteer_stats</p>";
    }
    
    if (!in_array('total_points', $statsColumnNames)) {
        DB::statement('ALTER TABLE volunteer_stats ADD COLUMN total_points INT NOT NULL DEFAULT 0 AFTER total_enrollments');
        echo "<p class='success'>✅ Added total_points to volunteer_stats</p>";
    }
    
    // 5. FIXED: Properly map enrollments to volunteer IDs
    // Get all volunteers with their user_ids
    $volunteers = DB::table('volunteers')->get();
    
    foreach ($volunteers as $volunteer) {
        // Count enrollments where enrolled_by matches this volunteer's ID
        $enrollmentCount = DB::table('farmer_enrollments')
            ->where('enrolled_by', $volunteer->id)
            ->count();
        
        if ($enrollmentCount > 0) {
            $points = $enrollmentCount * 10;
            
            // Update or insert stats for this volunteer
            DB::table('volunteer_stats')->updateOrInsert(
                ['volunteer_id' => $volunteer->id],
                [
                    'total_enrollments' => $enrollmentCount,
                    'total_points' => $points,
                    'current_badge' => 'bronze',
                    'rank' => 0,
                    'badges_earned' => 0,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
            
            echo "<p class='success'>✅ Synced stats for volunteer #{$volunteer->id}: {$enrollmentCount} enrollments, {$points} points</p>";
        }
    }
    
    echo "<br><h3 class='success'>🎉 All Fixed! Summary:</h3>";
    echo "<p>Total Volunteers: " . DB::table('volunteers')->count() . "</p>";
    echo "<p>Volunteers with Referral Codes: " . DB::table('volunteers')->whereNotNull('referral_code')->count() . "</p>";
    echo "<p>Total Referrals (via code): " . DB::table('users')->whereNotNull('referred_by')->count() . "</p>";
    echo "<p>Volunteer Stats Records: " . DB::table('volunteer_stats')->count() . "</p>";
    
    // Show sample referral codes
    echo "<br><h3>Sample Referral Codes:</h3>";
    $samples = DB::table('volunteers')
        ->join('users', 'volunteers.user_id', '=', 'users.id')
        ->select('users.name', 'volunteers.referral_code', 'volunteers.farmers_enrolled')
        ->whereNotNull('volunteers.referral_code')
        ->limit(5)
        ->get();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Volunteer Name</th><th>Referral Code</th><th>Farmers Enrolled</th></tr>";
    foreach ($samples as $sample) {
        echo "<tr><td>{$sample->name}</td><td><strong>{$sample->referral_code}</strong></td><td>{$sample->farmers_enrolled}</td></tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre style='background: #fee; padding: 10px; border-radius: 5px;'>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</p>";