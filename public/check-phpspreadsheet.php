<?php
echo "<style>
body{font-family:Arial;padding:20px;background:#f5f5f5;}
.container{max-width:900px;margin:0 auto;background:white;padding:30px;border-radius:8px;}
h1{color:#11455b;border-bottom:3px solid #2fcb6e;padding-bottom:10px;}
.success{background:#d4edda;border-left:4px solid #28a745;padding:15px;margin:10px 0;border-radius:4px;}
.error{background:#f8d7da;border-left:4px solid #dc3545;padding:15px;margin:10px 0;border-radius:4px;}
.info{background:#d1ecf1;border-left:4px solid #17a2b8;padding:15px;margin:10px 0;border-radius:4px;}
pre{background:#f4f4f4;padding:10px;border-radius:4px;overflow-x:auto;font-size:12px;}
.btn{display:inline-block;padding:10px 20px;background:#2fcb6e;color:white;text-decoration:none;border-radius:5px;margin:10px 5px;border:none;cursor:pointer;}
</style>";

echo "<div class='container'>";
echo "<h1>🔍 PhpSpreadsheet Installation Check</h1>";

$vendorDir = __DIR__ . '/../vendor';
$autoloadFile = $vendorDir . '/autoload.php';

echo "<h2>1. Checking Directory Structure</h2>";

$paths = [
    'Vendor directory' => $vendorDir,
    'Autoload file' => $autoloadFile,
    'PhpSpreadsheet directory' => $vendorDir . '/phpoffice/phpspreadsheet',
    'PhpSpreadsheet src' => $vendorDir . '/phpoffice/phpspreadsheet/src',
    'IOFactory.php' => $vendorDir . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php',
];

foreach ($paths as $name => $path) {
    $exists = file_exists($path);
    $type = is_dir($path) ? 'directory' : 'file';
    
    if ($exists) {
        echo "<div class='success'>✓ {$name}: EXISTS ({$type})<br><small>{$path}</small></div>";
    } else {
        echo "<div class='error'>✗ {$name}: NOT FOUND<br><small>{$path}</small></div>";
    }
}

echo "<h2>2. Checking Autoloader</h2>";

if (file_exists($autoloadFile)) {
    echo "<div class='success'>✓ Autoload file exists</div>";
    
    // Try to load autoloader
    try {
        require_once $autoloadFile;
        echo "<div class='success'>✓ Autoloader loaded successfully</div>";
        
        // Check if class exists
        if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            echo "<div class='success'>✓ IOFactory class is autoloadable!</div>";
            
            echo "<h2>3. Test PhpSpreadsheet</h2>";
            try {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                echo "<div class='success'>✓ PhpSpreadsheet works perfectly!</div>";
                echo "<div class='info'><strong>Installation is CORRECT!</strong><br>The import feature should work now.</div>";
                
            } catch (Exception $e) {
                echo "<div class='error'>✗ Error creating Spreadsheet: " . $e->getMessage() . "</div>";
            }
            
        } else {
            echo "<div class='error'>✗ IOFactory class NOT found in autoloader</div>";
            echo "<div class='info'>Need to fix autoloader...</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>✗ Error loading autoloader: " . $e->getMessage() . "</div>";
    }
    
} else {
    echo "<div class='error'>✗ Autoload file missing!</div>";
}

echo "<h2>4. List PhpSpreadsheet Files</h2>";
$srcDir = $vendorDir . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet';
if (is_dir($srcDir)) {
    echo "<div class='info'>";
    echo "<strong>Files in PhpSpreadsheet/src/PhpSpreadsheet:</strong><br>";
    $files = scandir($srcDir);
    echo "<pre>";
    foreach (array_slice($files, 0, 20) as $file) {
        if ($file != '.' && $file != '..') {
            echo $file . "\n";
        }
    }
    echo "</pre>";
    echo "</div>";
}

// Check composer.json
echo "<h2>5. Composer Configuration</h2>";
$composerJson = __DIR__ . '/../composer.json';
if (file_exists($composerJson)) {
    $json = json_decode(file_get_contents($composerJson), true);
    if (isset($json['require']['phpoffice/phpspreadsheet'])) {
        echo "<div class='success'>✓ Package in composer.json: {$json['require']['phpoffice/phpspreadsheet']}</div>";
    } else {
        echo "<div class='error'>✗ Package NOT in composer.json</div>";
    }
}

// Provide fix if needed
if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
    echo "<h2>🔧 Fix Required</h2>";
    echo "<div class='error'>";
    echo "<p><strong>PhpSpreadsheet is not properly installed. Choose a fix:</strong></p>";
    echo "</div>";
    
    echo "<form method='POST'>";
    echo "<button type='submit' name='fix' value='regenerate_autoload' class='btn'>1. Regenerate Autoloader</button>";
    echo "<button type='submit' name='fix' value='manual_autoload' class='btn'>2. Create Manual Autoloader</button>";
    echo "</form>";
}

// Handle fixes
if (isset($_POST['fix'])) {
    if ($_POST['fix'] === 'regenerate_autoload') {
        echo "<h3>Regenerating Autoloader...</h3>";
        
        chdir(__DIR__ . '/..');
        putenv("HOME=" . __DIR__ . '/..');
        putenv("COMPOSER_HOME=" . __DIR__ . '/../.composer');
        
        $output = [];
        $return = 0;
        exec("php composer.phar dump-autoload 2>&1", $output, $return);
        
        echo "<pre>" . implode("\n", $output) . "</pre>";
        
        if ($return === 0) {
            echo "<div class='success'>✓ Autoloader regenerated! Refresh this page.</div>";
        } else {
            echo "<div class='error'>Failed. Try manual autoloader instead.</div>";
        }
    }
    
    if ($_POST['fix'] === 'manual_autoload') {
        echo "<h3>Creating Manual Autoloader...</h3>";
        
        $manualAutoload = <<<'PHP'
<?php
// Manual autoloader for PhpSpreadsheet
spl_autoload_register(function ($class) {
    // PhpOffice namespace
    if (strpos($class, 'PhpOffice\\PhpSpreadsheet\\') === 0) {
        $classPath = str_replace('PhpOffice\\PhpSpreadsheet\\', '', $class);
        $file = __DIR__ . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet/' . str_replace('\\', '/', $classPath) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // Psr namespace (SimpleCache)
    if (strpos($class, 'Psr\\SimpleCache\\') === 0) {
        $classPath = str_replace('Psr\\SimpleCache\\', '', $class);
        $file = __DIR__ . '/psr/simple-cache/src/' . str_replace('\\', '/', $classPath) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
});
PHP;
        
        // Create bootstrap file that includes Laravel autoload + manual autoload
        $bootstrapContent = <<<'PHP'
<?php
// Load Laravel's autoloader first
$laravelAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($laravelAutoload)) {
    require_once $laravelAutoload;
}

// Load manual PhpSpreadsheet autoloader
$manualAutoload = __DIR__ . '/../vendor/phpspreadsheet-autoload.php';
if (file_exists($manualAutoload)) {
    require_once $manualAutoload;
}
PHP;
        
        file_put_contents($vendorDir . '/phpspreadsheet-autoload.php', $manualAutoload);
        file_put_contents(__DIR__ . '/../bootstrap/autoload-fix.php', $bootstrapContent);
        
        echo "<div class='success'>✓ Created manual autoloader at vendor/phpspreadsheet-autoload.php</div>";
        echo "<div class='info'>";
        echo "<p><strong>Now add this line to public/index.php BEFORE the Laravel autoload:</strong></p>";
        echo "<pre>require __DIR__.'/../vendor/phpspreadsheet-autoload.php';</pre>";
        echo "<p>Or refresh this page to test if it works automatically.</p>";
        echo "</div>";
        
        // Try loading it immediately
        require_once $vendorDir . '/phpspreadsheet-autoload.php';
        
        if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            echo "<div class='success'>✓ Manual autoloader works! Import feature should work now.</div>";
        }
    }
}

echo "<br><p style='color:red;'><strong>DELETE THIS FILE after fixing!</strong></p>";
echo "</div>";