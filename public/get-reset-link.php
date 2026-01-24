<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h2>Get Password Reset Link (Testing Only)</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    // Check if user exists
    $user = DB::table('users')->where('email', $email)->first();
    
    if (!$user) {
        echo "<p style='color: red;'>User not found with email: $email</p>";
    } else {
        // Get the most recent token for this email
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($resetToken) {
            $resetLink = url('/password/reset/' . $resetToken->token . '?email=' . urlencode($email));
            
            echo "<div style='background: #d1fae5; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
            echo "<h3 style='color: #059669;'>✅ Reset Link Generated!</h3>";
            echo "<p><strong>User:</strong> {$user->name} ({$email})</p>";
            echo "<p><strong>Token expires:</strong> " . \Carbon\Carbon::parse($resetToken->created_at)->addMinutes(60)->format('Y-m-d H:i:s') . "</p>";
            echo "<p><strong>Reset Link:</strong></p>";
            echo "<input type='text' value='$resetLink' style='width: 100%; padding: 10px; margin: 10px 0;' readonly>";
            echo "<br><a href='$resetLink' target='_blank'><button style='padding: 15px 30px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;'>Open Reset Page</button></a>";
            echo "</div>";
        } else {
            echo "<p style='color: orange;'>No reset token found. Go to <a href='/password/forgot'>/password/forgot</a> and request a reset first.</p>";
        }
    }
}
?>

<form method="POST" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h3>Enter Email to Get Reset Link</h3>
    <p style="color: #6b7280; font-size: 14px;">First request a password reset from <a href="/password/forgot">/password/forgot</a>, then enter the email here.</p>
    
    <label style="display: block; margin: 20px 0 5px 0; font-weight: bold;">Email Address:</label>
    <input type="email" name="email" required 
           style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px;"
           placeholder="user@example.com">
    
    <button type="submit" style="margin-top: 20px; padding: 15px 30px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%;">
        Get Reset Link
    </button>
</form>

<p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
    <strong>⚠️ FOR TESTING ONLY!</strong> Delete this file in production!
</p>