<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>Adding Referral System Fields</h2>";

try {
    // Add referral_code to volunteers table
    if (!Schema::hasColumn('volunteers', 'referral_code')) {
        DB::statement('ALTER TABLE volunteers ADD COLUMN referral_code VARCHAR(20) UNIQUE AFTER user_id');
        echo "<p style='color: green;'>✅ Added referral_code to volunteers table</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ referral_code already exists in volunteers table</p>";
    }
    
    // Add referred_by to users table
    if (!Schema::hasColumn('users', 'referred_by')) {
        DB::statement('ALTER TABLE users ADD COLUMN referred_by BIGINT UNSIGNED NULL AFTER role');
        DB::statement('ALTER TABLE users ADD FOREIGN KEY (referred_by) REFERENCES volunteers(id) ON DELETE SET NULL');
        echo "<p style='color: green;'>✅ Added referred_by to users table</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ referred_by already exists in users table</p>";
    }
    
    // Generate referral codes for existing volunteers
    $volunteers = DB::table('volunteers')->whereNull('referral_code')->get();
    foreach ($volunteers as $volunteer) {
        $code = 'FV-' . strtoupper(substr(md5($volunteer->id . time()), 0, 8));
        DB::table('volunteers')->where('id', $volunteer->id)->update(['referral_code' => $code]);
    }
    
    echo "<p style='color: green;'>✅ Generated referral codes for " . count($volunteers) . " volunteers</p>";
    echo "<p><strong>Done! Referral system ready.</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<br><p style='color: red;'>⚠️ DELETE THIS FILE AFTER SUCCESS!</p>";