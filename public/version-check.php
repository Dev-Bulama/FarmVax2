<?php
/**
 * Deployment Version Check
 * Place this file in public/ and access via https://farmvax.com/version-check.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>FarmVax Deployment Check</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #11455b; }
        .check { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        .label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 FarmVax Deployment Status</h1>
        <p>This page helps verify your deployment status and identifies missing features.</p>

        <?php
        $checks = [];

        // Check 1: Admin Livestock Controller exists
        $livestockController = __DIR__ . '/../app/Http/Controllers/Admin/LivestockController.php';
        $checks[] = [
            'name' => 'Admin Livestock Controller',
            'status' => file_exists($livestockController),
            'message' => file_exists($livestockController)
                ? 'File exists at: ' . $livestockController
                : 'File NOT found. Need to pull commit 82734fe or later.',
        ];

        // Check 2: Admin Livestock Index View exists
        $livestockView = __DIR__ . '/../resources/views/admin/livestock/index.blade.php';
        $checks[] = [
            'name' => 'Admin Livestock Index View',
            'status' => file_exists($livestockView),
            'message' => file_exists($livestockView)
                ? 'View file exists'
                : 'View file NOT found. Need to pull commit 82734fe or later.',
        ];

        // Check 3: Sidebar has livestock link
        $sidebarFile = __DIR__ . '/../resources/views/admin/partials/sidebar.blade.php';
        $sidebarContent = file_exists($sidebarFile) ? file_get_contents($sidebarFile) : '';
        $hasLivestockLink = strpos($sidebarContent, "route('admin.livestock.index')") !== false;
        $checks[] = [
            'name' => 'Sidebar Livestock Link',
            'status' => $hasLivestockLink,
            'message' => $hasLivestockLink
                ? 'Livestock link found in sidebar'
                : 'Livestock link NOT in sidebar. Need to pull commit 82734fe or later.',
        ];

        // Check 4: Routes file has admin livestock routes
        $routesFile = __DIR__ . '/../routes/web.php';
        $routesContent = file_exists($routesFile) ? file_get_contents($routesFile) : '';
        $hasLivestockRoutes = strpos($routesContent, "App\Http\Controllers\Admin\LivestockController") !== false;
        $checks[] = [
            'name' => 'Admin Livestock Routes',
            'status' => $hasLivestockRoutes,
            'message' => $hasLivestockRoutes
                ? 'Livestock routes registered in web.php'
                : 'Livestock routes NOT found in web.php. Need to pull commit 82734fe or later.',
        ];

        // Check 5: Git commit hash (if available)
        $gitHead = __DIR__ . '/../.git/HEAD';
        $currentCommit = 'Unknown';
        if (file_exists($gitHead)) {
            $head = trim(file_get_contents($gitHead));
            if (preg_match('/^ref: (.+)$/', $head, $matches)) {
                $refFile = __DIR__ . '/../.git/' . $matches[1];
                if (file_exists($refFile)) {
                    $currentCommit = substr(trim(file_get_contents($refFile)), 0, 7);
                }
            }
        }
        $expectedCommit = 'ad3b872'; // Latest commit with all fixes
        $checks[] = [
            'name' => 'Git Commit Version',
            'status' => $currentCommit === $expectedCommit,
            'message' => "Current: <code>{$currentCommit}</code> | Expected: <code>{$expectedCommit}</code> or later",
        ];

        // Display all checks
        foreach ($checks as $check) {
            $class = $check['status'] ? 'success' : 'error';
            $icon = $check['status'] ? '✅' : '❌';
            echo "<div class='check {$class}'>";
            echo "<div class='label'>{$icon} {$check['name']}</div>";
            echo "<div>{$check['message']}</div>";
            echo "</div>";
        }

        // Overall status
        $allPassed = array_reduce($checks, function($carry, $check) {
            return $carry && $check['status'];
        }, true);

        if (!$allPassed) {
            echo "<div class='check warning'>";
            echo "<h3>⚠️ Action Required</h3>";
            echo "<p>Your production server is missing the livestock management feature. To fix:</p>";
            echo "<ol>";
            echo "<li>SSH into your production server</li>";
            echo "<li>Navigate to your FarmVax directory</li>";
            echo "<li>Run: <code>git fetch origin</code></li>";
            echo "<li>Run: <code>git pull origin claude/farmvax-production-fixes-P2RFL</code></li>";
            echo "<li>Run: <code>php artisan route:clear && php artisan cache:clear && php artisan view:clear</code></li>";
            echo "<li>Reload this page to verify</li>";
            echo "</ol>";
            echo "</div>";
        } else {
            echo "<div class='check success'>";
            echo "<h3>✅ All Systems Operational</h3>";
            echo "<p>Your deployment has all the latest features including admin livestock management.</p>";
            echo "<p>You can access it at: <a href='/admin/livestock'>https://farmvax.com/admin/livestock</a></p>";
            echo "</div>";
        }
        ?>

        <hr style="margin: 30px 0;">
        <p style="color: #999; font-size: 14px;">
            <strong>Note:</strong> Delete this file after verification for security.<br>
            Command: <code>rm public/version-check.php</code>
        </p>
    </div>
</body>
</html>
