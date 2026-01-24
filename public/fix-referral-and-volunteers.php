<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<style>body{font-family:Arial;padding:20px;} .success{color:#10b981;font-weight:bold;} .error{color:#ef4444;font-weight:bold;}</style>";
echo "<h2>🔧 Fixing Referral System & Volunteers</h2>";

try {
    // 1. Add referral_code to volunteers table
    if (!Schema::hasColumn('volunteers', 'referral_code')) {
        DB::statement('ALTER TABLE volunteers ADD COLUMN referral_code VARCHAR(20) UNIQUE NULL AFTER user_id');
        DB::statement('CREATE UNIQUE INDEX idx_referral_code ON volunteers(referral_code)');
        echo "<p class='success'>✅ Added referral_code to volunteers table</p>";
    } else {
        echo "<p class='success'>✅ referral_code already exists</p>";
    }
    
    // 2. Add referred_by to users table
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
    
    // 4. Check volunteer_stats columns
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
    
    // 5. Sync enrollment counts and points
    $enrollmentCounts = DB::table('farmer_enrollments')
        ->select('enrolled_by', DB::raw('COUNT(*) as total'))
        ->groupBy('enrolled_by')
        ->get();
    
    foreach ($enrollmentCounts as $count) {
        $points = $count->total * 10; // 10 points per enrollment
        
        DB::table('volunteer_stats')->updateOrInsert(
            ['volunteer_id' => $count->enrolled_by],
            [
                'total_enrollments' => $count->total,
                'total_points' => $points,
                'updated_at' => now()
            ]
        );
    }
    echo "<p class='success'>✅ Synced enrollment counts and points</p>";
    
    echo "<br><h3 class='success'>🎉 All Fixed! Summary:</h3>";
    echo "<p>Total Volunteers: " . DB::table('volunteers')->count() . "</p>";
    echo "<p>Volunteers with Referral Codes: " . DB::table('volunteers')->whereNotNull('referral_code')->count() . "</p>";
    echo "<p>Total Referrals: " . DB::table('users')->whereNotNull('referred_by')->count() . "</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<br><br><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</p>";