<?php
/**
 * FarmVax - Manual Cache Clear (No Laravel Required)
 * Access via: https://farmvax.com/manual-cache-clear.php
 * Use this if Laravel bootstrap is failing
 */

echo "<h1>FarmVax - Manual Cache Clear</h1>";
echo "<hr>";
echo "<p>This script manually deletes cache files without loading Laravel...</p>";

$baseDir = __DIR__ . '/..';
$deleted = 0;
$errors = 0;

// Function to recursively delete files in a directory
function deleteFiles($dir, $pattern = '*', $exclude = ['.gitignore']) {
    global $deleted, $errors;

    if (!is_dir($dir)) {
        echo "<p style='color: orange;'>⚠️ Directory not found: $dir</p>";
        return;
    }

    $files = glob($dir . '/' . $pattern);
    if ($files === false) {
        return;
    }

    foreach ($files as $file) {
        $filename = basename($file);

        // Skip excluded files
        if (in_array($filename, $exclude)) {
            continue;
        }

        if (is_file($file)) {
            if (unlink($file)) {
                $deleted++;
                echo "<span style='color: green; font-size: 11px;'>✅ Deleted: $file</span><br>";
            } else {
                $errors++;
                echo "<span style='color: red; font-size: 11px;'>❌ Failed to delete: $file</span><br>";
            }
        }
    }
}

// Function to recursively delete directory contents
function deleteDirectoryContents($dir, $exclude = ['.gitignore']) {
    global $deleted, $errors;

    if (!is_dir($dir)) {
        echo "<p style='color: orange;'>⚠️ Directory not found: $dir</p>";
        return;
    }

    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || in_array($item, $exclude)) {
            continue;
        }

        $path = $dir . '/' . $item;

        if (is_dir($path)) {
            deleteDirectoryContents($path, $exclude);
            if (rmdir($path)) {
                echo "<span style='color: green; font-size: 11px;'>✅ Removed directory: $path</span><br>";
            }
        } else {
            if (unlink($path)) {
                $deleted++;
                echo "<span style='color: green; font-size: 11px;'>✅ Deleted: $path</span><br>";
            } else {
                $errors++;
                echo "<span style='color: red; font-size: 11px;'>❌ Failed to delete: $path</span><br>";
            }
        }
    }
}

echo "<h3>1. Clearing Bootstrap Cache...</h3>";
deleteFiles($baseDir . '/bootstrap/cache', '*.php', ['.gitignore']);

echo "<h3>2. Clearing Framework Cache...</h3>";
deleteDirectoryContents($baseDir . '/storage/framework/cache/data', ['.gitignore']);

echo "<h3>3. Clearing Views Cache...</h3>";
deleteDirectoryContents($baseDir . '/storage/framework/views', ['.gitignore']);

echo "<h3>4. Clearing Sessions...</h3>";
deleteDirectoryContents($baseDir . '/storage/framework/sessions', ['.gitignore']);

echo "<h3>5. Clearing Config Cache...</h3>";
$configCache = $baseDir . '/bootstrap/cache/config.php';
if (file_exists($configCache)) {
    if (unlink($configCache)) {
        $deleted++;
        echo "<p style='color: green;'>✅ Deleted config cache</p>";
    }
}

echo "<h3>6. Clearing Route Cache...</h3>";
$routeCache = $baseDir . '/bootstrap/cache/routes-v7.php';
if (file_exists($routeCache)) {
    if (unlink($routeCache)) {
        $deleted++;
        echo "<p style='color: green;'>✅ Deleted route cache</p>";
    }
}

$routeCacheAlt = $baseDir . '/bootstrap/cache/routes.php';
if (file_exists($routeCacheAlt)) {
    if (unlink($routeCacheAlt)) {
        $deleted++;
        echo "<p style='color: green;'>✅ Deleted route cache (alt)</p>";
    }
}

echo "<hr>";
echo "<h2 style='color: green;'>✅ Manual Cache Clear Complete!</h2>";
echo "<p><strong>Statistics:</strong></p>";
echo "<ul>";
echo "<li>Files Deleted: <strong>$deleted</strong></li>";
echo "<li>Errors: <strong>$errors</strong></li>";
echo "</ul>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Try accessing your site now: <a href='/' target='_blank'>Homepage</a> | <a href='/admin/dashboard' target='_blank'>Admin Dashboard</a></li>";
echo "<li>If still showing 500 error, run: <a href='check-error.php' target='_blank'>check-error.php</a> for detailed diagnostics</li>";
echo "<li>Check file permissions in cPanel (storage/ and bootstrap/cache/ should be 755)</li>";
echo "<li><strong>Delete this file after fixing:</strong> public/manual-cache-clear.php</li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color: gray; font-size: 12px;'>Generated: " . date('Y-m-d H:i:s') . "</p>";
?>
