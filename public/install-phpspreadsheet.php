<?php
set_time_limit(300);

echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);}
h1{color:#11455b;border-bottom:3px solid #2fcb6e;padding-bottom:10px;}
.success{background:#d4edda;border-left:4px solid #28a745;padding:15px;margin:10px 0;border-radius:4px;color:#155724;}
.error{background:#f8d7da;border-left:4px solid #dc3545;padding:15px;margin:10px 0;border-radius:4px;color:#721c24;}
.info{background:#d1ecf1;border-left:4px solid #17a2b8;padding:15px;margin:10px 0;border-radius:4px;color:#0c5460;}
.warning{background:#fff3cd;border-left:4px solid #ffc107;padding:15px;margin:10px 0;border-radius:4px;color:#856404;}
pre{background:#f4f4f4;padding:10px;border-radius:4px;overflow-x:auto;font-size:12px;}
.btn{display:inline-block;padding:10px 20px;background:#2fcb6e;color:white;text-decoration:none;border-radius:5px;margin:10px 5px;border:none;cursor:pointer;}
.btn:hover{background:#25a356;}
</style>";

echo "<div class='container'>";
echo "<h1>📦 Install PhpSpreadsheet Package</h1>";

$projectRoot = realpath(__DIR__ . '/..');
$composerJson = $projectRoot . '/composer.json';
$vendorDir = $projectRoot . '/vendor';
$composerBin = $projectRoot . '/composer.phar';

// Check if already installed
if (is_dir($vendorDir . '/phpoffice/phpspreadsheet')) {
    echo "<div class='success'><strong>✓ PhpSpreadsheet Already Installed!</strong></div>";
    echo "<div class='warning'><strong>⚠️ DELETE THIS FILE NOW!</strong><br>";
    echo "Delete <code>public/install-phpspreadsheet.php</code></div>";
    echo "<a href='/admin/import' class='btn'>Go to Import Page →</a>";
    exit;
}

// Add package to composer.json if not there
$json = json_decode(file_get_contents($composerJson), true);
if (!isset($json['require']['phpoffice/phpspreadsheet'])) {
    $json['require']['phpoffice/phpspreadsheet'] = '^2.0';
    file_put_contents($composerJson, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    echo "<div class='success'>✓ Added package to composer.json</div>";
}

if (isset($_POST['install_method'])) {
    $method = $_POST['install_method'];
    
    if ($method === 'composer') {
        echo "<div class='info'><strong>Installing via Composer...</strong></div>";
        
        // Download composer if needed
        if (!file_exists($composerBin)) {
            echo "<p>Downloading Composer...</p>";
            $composer = file_get_contents('https://getcomposer.org/composer-stable.phar');
            if ($composer) {
                file_put_contents($composerBin, $composer);
                chmod($composerBin, 0755);
                echo "<div class='success'>✓ Composer downloaded</div>";
            }
        }
        
        if (file_exists($composerBin)) {
            // Set environment variables
            putenv("HOME={$projectRoot}");
            putenv("COMPOSER_HOME={$projectRoot}/.composer");
            putenv("COMPOSER_CACHE_DIR={$projectRoot}/.composer/cache");
            
            // Create .composer directory
            @mkdir($projectRoot . '/.composer', 0755, true);
            @mkdir($projectRoot . '/.composer/cache', 0755, true);
            
            echo "<p>Running composer install...</p>";
            echo "<pre>";
            
            chdir($projectRoot);
            
            $output = [];
            $return = 0;
            exec("HOME={$projectRoot} COMPOSER_HOME={$projectRoot}/.composer php composer.phar install --no-dev --no-interaction 2>&1", $output, $return);
            
            echo implode("\n", $output);
            echo "</pre>";
            
            if ($return === 0 && is_dir($vendorDir . '/phpoffice/phpspreadsheet')) {
                echo "<div class='success'><strong>✓ Installation Successful!</strong></div>";
                echo "<div class='warning'><strong>⚠️ DELETE THIS FILE NOW!</strong></div>";
                echo "<a href='/admin/import' class='btn'>Go to Import Page →</a>";
            } else {
                echo "<div class='error'><strong>Composer installation failed. Trying alternative method...</strong></div>";
                echo "<p>Redirecting to manual download method...</p>";
                echo "<meta http-equiv='refresh' content='3;url=?manual=1'>";
            }
        }
    }
    
    if ($method === 'manual' || !is_dir($vendorDir . '/phpoffice/phpspreadsheet')) {
        echo "<div class='info'><strong>Manual Installation Method</strong></div>";
        echo "<div class='warning'>";
        echo "<h3>Follow these steps:</h3>";
        echo "<ol>";
        echo "<li>Download PhpSpreadsheet from: <a href='https://github.com/PHPOffice/PhpSpreadsheet/archive/refs/tags/2.0.0.zip' target='_blank'>GitHub Release</a></li>";
        echo "<li>Extract the ZIP file</li>";
        echo "<li>Upload the <code>PhpSpreadsheet-2.0.0/src</code> folder to <code>vendor/phpoffice/phpspreadsheet/src</code></li>";
        echo "<li>Create <code>vendor/autoload.php</code> if it doesn't exist</li>";
        echo "</ol>";
        echo "<p><strong>OR</strong> contact your hosting support to run: <code>composer install</code></p>";
        echo "</div>";
        
        // Provide autoload fallback
        echo "<h3>Create Temporary Autoloader</h3>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='create_autoload' value='1'>";
        echo "<button type='submit' class='btn'>Create Autoload File</button>";
        echo "</form>";
    }
}

if (isset($_POST['create_autoload'])) {
    $autoloadContent = <<<'PHP'
<?php
// Temporary autoloader for PhpSpreadsheet
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $base_dir = __DIR__ . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
PHP;
    
    @mkdir($vendorDir, 0755, true);
    file_put_contents($vendorDir . '/autoload.php', $autoloadContent);
    echo "<div class='success'>✓ Created temporary autoload file at vendor/autoload.php</div>";
    echo "<div class='info'>Now manually upload PhpSpreadsheet files as described above.</div>";
}

// Show installation options if not installed yet
if (!isset($_POST['install_method']) && !isset($_POST['create_autoload'])) {
    echo "<div class='info'>";
    echo "<h3>Choose Installation Method:</h3>";
    echo "</div>";
    
    echo "<form method='POST' style='margin:20px 0;'>";
    echo "<input type='hidden' name='install_method' value='composer'>";
    echo "<button type='submit' class='btn'>🚀 Try Automatic Installation (Composer)</button>";
    echo "<p style='font-size:12px;color:#666;'>Attempts to install using Composer with fixed environment variables</p>";
    echo "</form>";
    
    echo "<div style='text-align:center;margin:20px 0;font-weight:bold;color:#999;'>--- OR ---</div>";
    
    echo "<a href='?manual=1' class='btn' style='background:#17a2b8;'>📥 Use Manual Installation Method</a>";
    echo "<p style='font-size:12px;color:#666;'>Download and upload files manually via File Manager</p>";
}

if (isset($_GET['manual'])) {
    echo "<div class='warning'>";
    echo "<h3>📥 Manual Installation Steps:</h3>";
    echo "<ol style='line-height:2;'>";
    echo "<li><strong>Download Package:</strong><br>";
    echo "<a href='https://github.com/PHPOffice/PhpSpreadsheet/releases/download/2.0.0/PhpSpreadsheet-2.0.0.zip' target='_blank' class='btn'>Download PhpSpreadsheet 2.0.0</a></li>";
    echo "<li><strong>Extract ZIP:</strong> Extract the downloaded file on your computer</li>";
    echo "<li><strong>Upload via File Manager:</strong><br>";
    echo "- Go to hPanel → File Manager<br>";
    echo "- Navigate to: <code>vendor/</code> (create if doesn't exist)<br>";
    echo "- Create folder: <code>phpoffice/</code><br>";
    echo "- Create folder: <code>phpoffice/phpspreadsheet/</code><br>";
    echo "- Upload the entire <code>src/</code> folder from the extracted ZIP to <code>vendor/phpoffice/phpspreadsheet/</code></li>";
    echo "<li><strong>Run Composer Dump:</strong><br>";
    echo "If you have access to PHP CLI, run: <code>composer dump-autoload</code><br>";
    echo "Otherwise, use the button below:</li>";
    echo "</ol>";
    
    echo "<form method='POST'>";
    echo "<input type='hidden' name='create_autoload' value='1'>";
    echo "<button type='submit' class='btn'>Create Autoload File</button>";
    echo "</form>";
    echo "</div>";
    
    echo "<div class='info' style='margin-top:20px;'>";
    echo "<h4>Alternative: Contact Hostinger Support</h4>";
    echo "<p>Ask Hostinger support to run this command for you:</p>";
    echo "<pre>cd /home/your-username/public_html && composer install</pre>";
    echo "<p>They should be able to do this quickly via their backend terminal.</p>";
    echo "</div>";
}

echo "</div>";
