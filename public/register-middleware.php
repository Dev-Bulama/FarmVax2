<?php
require __DIR__ . '/../vendor/autoload.php';

echo "<h2>Middleware Registration</h2>";
echo "<hr>";

$kernelPath = __DIR__ . '/../app/Http/Kernel.php';
$bootstrapPath = __DIR__ . '/../bootstrap/app.php';

if (file_exists($kernelPath)) {
    echo "<h3>Laravel 10 Detected</h3>";
    echo "<p>Add this to <code>app/Http/Kernel.php</code> in the <code>\$routeMiddleware</code> array:</p>";
    echo "<pre>'verified.professional' => \App\Http\Middleware\VerifiedProfessionalMiddleware::class,</pre>";
    
    $content = file_get_contents($kernelPath);
    if (strpos($content, 'verified.professional') !== false) {
        echo "<p style='color: green;'>✅ Middleware already registered!</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Middleware not found - please add manually</p>";
    }
} elseif (file_exists($bootstrapPath)) {
    echo "<h3>Laravel 11 Detected</h3>";
    echo "<p>Add this to <code>bootstrap/app.php</code>:</p>";
    echo "<pre>->withMiddleware(function (Middleware \$middleware) {
    \$middleware->alias([
        'verified.professional' => \App\Http\Middleware\VerifiedProfessionalMiddleware::class,
    ]);
})</pre>";
} else {
    echo "<p style='color: red;'>❌ Could not detect Laravel version</p>";
}

echo "<br><strong style='color: red;'>DELETE THIS FILE!</strong>";