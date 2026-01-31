<?php
/**
 * FarmVax Storage Symlink Diagnostic & Fix Tool
 * For hPanel (Hostinger) - No SSH Required
 *
 * Upload this file to: public_html/storage-fix.php
 * Access via: https://farmvax.com/storage-fix.php
 *
 * IMPORTANT: Delete this file after fixing the issue for security!
 */

// Security: Only allow access from admin (optional - remove if you want public access)
// Uncomment the lines below and set a password
// $ACCESS_PASSWORD = 'your-secure-password-here';
// if (!isset($_GET['pass']) || $_GET['pass'] !== $ACCESS_PASSWORD) {
//     die('Access denied. Add ?pass=your-secure-password-here to URL');
// }

error_reporting(E_ALL);
ini_set('display_errors', 1);

$results = [];
$errors = [];
$success = [];

// Get paths
$publicPath = __DIR__;
$basePath = dirname($publicPath);
$storagePath = $basePath . '/storage/app/public';
$symlinkPath = $publicPath . '/storage';

$results['Base Path'] = $basePath;
$results['Public Path'] = $publicPath;
$results['Storage Target'] = $storagePath;
$results['Symlink Path'] = $symlinkPath;

// Check if running in hPanel environment
$results['Environment'] = php_sapi_name();
$results['PHP Version'] = PHP_VERSION;
$results['User'] = function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'Unknown';

echo "<!DOCTYPE html>
<html>
<head>
    <title>FarmVax Storage Diagnostic Tool</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #11455B;
            border-bottom: 3px solid #2FCB6E;
            padding-bottom: 10px;
        }
        h2 {
            color: #11455B;
            margin-top: 30px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #11455B;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2FCB6E;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #28b35f;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 FarmVax Storage Diagnostic Tool</h1>
        <p>This tool will diagnose and fix the storage symlink issue causing 404 errors on uploaded documents.</p>
";

// Step 1: Check if symlink exists
echo "<h2>Step 1: Checking Symlink Status</h2>";

if (file_exists($symlinkPath)) {
    if (is_link($symlinkPath)) {
        $target = readlink($symlinkPath);
        if ($target === $storagePath) {
            echo "<div class='success'>✅ <strong>Symlink exists and points to correct location!</strong><br>";
            echo "From: <code>$symlinkPath</code><br>";
            echo "To: <code>$target</code></div>";
            $success[] = "Symlink is correctly configured";
        } else {
            echo "<div class='warning'>⚠️ <strong>Symlink exists but points to wrong location!</strong><br>";
            echo "Current: <code>$target</code><br>";
            echo "Should be: <code>$storagePath</code></div>";
            $errors[] = "Symlink points to wrong location";
        }
    } else {
        echo "<div class='error'>❌ <strong>Path exists but is NOT a symlink!</strong><br>";
        echo "Path: <code>$symlinkPath</code> is a " . (is_dir($symlinkPath) ? 'directory' : 'file') . "</div>";
        $errors[] = "Storage path is not a symlink";
    }
} else {
    echo "<div class='error'>❌ <strong>Symlink does NOT exist!</strong><br>";
    echo "Missing: <code>$symlinkPath</code></div>";
    $errors[] = "Symlink does not exist";
}

// Step 2: Check storage directory
echo "<h2>Step 2: Checking Storage Directory</h2>";

if (file_exists($storagePath)) {
    if (is_dir($storagePath)) {
        echo "<div class='success'>✅ <strong>Storage directory exists!</strong><br>";
        echo "Path: <code>$storagePath</code></div>";
        $success[] = "Storage directory found";

        // Check permissions
        $perms = fileperms($storagePath);
        $permsOctal = substr(sprintf('%o', $perms), -4);
        echo "<div class='info'>ℹ️ <strong>Permissions:</strong> $permsOctal</div>";

        if (is_writable($storagePath)) {
            echo "<div class='success'>✅ Storage directory is writable</div>";
            $success[] = "Storage directory is writable";
        } else {
            echo "<div class='error'>❌ Storage directory is NOT writable</div>";
            $errors[] = "Storage directory is not writable";
        }
    } else {
        echo "<div class='error'>❌ <strong>Storage path exists but is NOT a directory!</strong></div>";
        $errors[] = "Storage path is not a directory";
    }
} else {
    echo "<div class='error'>❌ <strong>Storage directory does NOT exist!</strong><br>";
    echo "Missing: <code>$storagePath</code></div>";
    $errors[] = "Storage directory does not exist";
}

// Step 3: Check for documents
echo "<h2>Step 3: Checking for Uploaded Documents</h2>";

$documentsPath = $storagePath . '/documents';
if (file_exists($documentsPath)) {
    $files = glob($documentsPath . '/**/*.*');
    $fileCount = count($files);
    echo "<div class='success'>✅ Found <strong>$fileCount</strong> document files in storage</div>";

    if ($fileCount > 0) {
        echo "<div class='info'><strong>Sample files:</strong><ul>";
        foreach (array_slice($files, 0, 5) as $file) {
            $relativePath = str_replace($storagePath . '/', '', $file);
            echo "<li><code>$relativePath</code></li>";
        }
        if ($fileCount > 5) echo "<li><em>... and " . ($fileCount - 5) . " more</em></li>";
        echo "</ul></div>";
    }
} else {
    echo "<div class='warning'>⚠️ No documents folder found at <code>$documentsPath</code></div>";
}

// Step 4: Try to create symlink
echo "<h2>Step 4: Attempting to Fix Symlink</h2>";

if (!empty($errors)) {
    echo "<form method='POST'>";
    echo "<input type='hidden' name='action' value='fix'>";
    echo "<button type='submit' class='btn'>🔧 Fix Symlink Now</button>";
    echo "</form>";

    if (isset($_POST['action']) && $_POST['action'] === 'fix') {
        echo "<div class='info'>🔄 Attempting to fix symlink...</div>";

        // Remove existing symlink/directory if it exists
        if (file_exists($symlinkPath)) {
            if (is_link($symlinkPath)) {
                if (@unlink($symlinkPath)) {
                    echo "<div class='success'>✅ Removed old symlink</div>";
                } else {
                    echo "<div class='error'>❌ Failed to remove old symlink: " . error_get_last()['message'] . "</div>";
                }
            } else if (is_dir($symlinkPath)) {
                echo "<div class='error'>❌ Cannot remove existing directory automatically. Please delete <code>$symlinkPath</code> via File Manager first.</div>";
            }
        }

        // Create new symlink
        if (!file_exists($symlinkPath)) {
            if (@symlink($storagePath, $symlinkPath)) {
                echo "<div class='success'>✅✅✅ <strong>SUCCESS! Symlink created successfully!</strong><br>";
                echo "From: <code>$symlinkPath</code><br>";
                echo "To: <code>$storagePath</code></div>";
                $success[] = "Symlink created successfully";
                $errors = []; // Clear errors
            } else {
                $error = error_get_last();
                echo "<div class='error'>❌ Failed to create symlink: " . $error['message'] . "</div>";

                // Suggest alternative
                echo "<div class='warning'><strong>Alternative Fix (Manual via File Manager):</strong><br>";
                echo "1. Open hPanel File Manager<br>";
                echo "2. Navigate to: <code>$publicPath</code><br>";
                echo "3. Delete the 'storage' folder if it exists<br>";
                echo "4. Contact Hostinger support to create symlink from:<br>";
                echo "&nbsp;&nbsp;&nbsp;<code>$symlinkPath</code> → <code>$storagePath</code></div>";
            }
        }
    }
} else {
    echo "<div class='success'>✅ <strong>No fixes needed! Symlink is working correctly.</strong></div>";
}

// Step 5: Test file access
echo "<h2>Step 5: Testing File Access</h2>";

if (!empty($errors)) {
    echo "<div class='warning'>⚠️ Skipping file access test until symlink is fixed</div>";
} else {
    // Create a test file
    $testDir = $storagePath . '/test-' . time();
    $testFile = 'test.txt';
    $testContent = 'FarmVax Storage Test - ' . date('Y-m-d H:i:s');

    if (@mkdir($testDir)) {
        if (@file_put_contents($testDir . '/' . $testFile, $testContent)) {
            $testUrl = '/storage/test-' . basename($testDir) . '/' . $testFile;

            echo "<div class='info'>📝 Created test file: <code>$testUrl</code><br>";
            echo "<a href='$testUrl' target='_blank' class='btn'>🔗 Click to Test File Access</a></div>";

            echo "<div class='warning'>⚠️ <strong>After testing, delete the test folder via File Manager:</strong><br>";
            echo "<code>$testDir</code></div>";
        }
    }
}

// Summary
echo "<h2>📊 Summary</h2>";

echo "<table>";
echo "<tr><th>Check</th><th>Status</th></tr>";

if (!empty($success)) {
    foreach ($success as $item) {
        echo "<tr><td>$item</td><td><span class='badge badge-success'>PASS</span></td></tr>";
    }
}

if (!empty($errors)) {
    foreach ($errors as $item) {
        echo "<tr><td>$item</td><td><span class='badge badge-danger'>FAIL</span></td></tr>";
    }
}

echo "</table>";

// Environment details
echo "<h2>🖥️ Environment Information</h2>";
echo "<table>";
foreach ($results as $key => $value) {
    echo "<tr><td><strong>$key</strong></td><td><code>$value</code></td></tr>";
}
echo "</table>";

// Final instructions
echo "<h2>📋 Next Steps</h2>";

if (empty($errors)) {
    echo "<div class='success'>";
    echo "<h3>✅ Storage is configured correctly!</h3>";
    echo "<p>Documents should now be accessible at URLs like:</p>";
    echo "<code>https://farmvax.com/storage/documents/licenses/filename.pdf</code><br><br>";
    echo "<p><strong>IMPORTANT: For security, delete this diagnostic file now!</strong></p>";
    echo "<p>Delete: <code>" . __FILE__ . "</code></p>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<h3>⚠️ Action Required</h3>";
    echo "<ol>";
    echo "<li>Click the 'Fix Symlink Now' button above to attempt automatic fix</li>";
    echo "<li>If automatic fix fails, contact Hostinger support with this information:</li>";
    echo "<ul>";
    echo "<li>Request: Create symbolic link for Laravel storage</li>";
    echo "<li>From: <code>$symlinkPath</code></li>";
    echo "<li>To: <code>$storagePath</code></li>";
    echo "</ul>";
    echo "<li>After fixing, refresh this page to verify</li>";
    echo "<li>Delete this diagnostic file for security</li>";
    echo "</ol>";
    echo "</div>";
}

// Alternative: PHP code to add to a route
echo "<h2>💻 Alternative: Laravel Route Method</h2>";
echo "<div class='info'>";
echo "<p>If you can't create symlink via this tool or File Manager, add this temporary route to <code>routes/web.php</code>:</p>";
echo "<pre>Route::get('/create-storage-link', function() {
    \$target = storage_path('app/public');
    \$link = public_path('storage');

    if (file_exists(\$link)) {
        return 'Link already exists';
    }

    symlink(\$target, \$link);
    return 'Storage link created successfully!';
});</pre>";
echo "<p>Then visit: <code>https://farmvax.com/create-storage-link</code></p>";
echo "<p><strong>Remember to remove this route after using it!</strong></p>";
echo "</div>";

echo "
        <div style='margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd; text-align: center; color: #666;'>
            <p>FarmVax Storage Diagnostic Tool v1.0 | Generated: " . date('Y-m-d H:i:s') . "</p>
            <p><strong style='color: red;'>⚠️ DELETE THIS FILE AFTER USE FOR SECURITY!</strong></p>
        </div>
    </div>
</body>
</html>";
