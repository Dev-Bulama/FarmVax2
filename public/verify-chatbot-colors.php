<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$primaryColor = \App\Models\Setting::get('chatbot_primary_color', '#9333ea');
$secondaryColor = \App\Models\Setting::get('chatbot_secondary_color', '#6366f1');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chatbot Color Verification</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #11455B; margin-bottom: 20px; }
        .color-box { width: 100%; height: 100px; border-radius: 8px; margin: 10px 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; }
        .gradient { background: linear-gradient(to right, <?php echo $primaryColor; ?>, <?php echo $secondaryColor; ?>); }
        .info { background: #e3f2fd; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #2196F3; }
        table { width: 100%; margin: 20px 0; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        td:first-child { font-weight: bold; width: 40%; }
        .warning { background: #fff3e0; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ff9800; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Chatbot Color Verification</h1>
        
        <div class="info">
            <strong>✅ These are the colors currently saved in your database:</strong>
        </div>

        <table>
            <tr>
                <td>Primary Color:</td>
                <td><strong><?php echo $primaryColor; ?></strong></td>
            </tr>
            <tr>
                <td>Secondary Color:</td>
                <td><strong><?php echo $secondaryColor; ?></strong></td>
            </tr>
        </table>

        <h2>Color Preview</h2>
        
        <div class="color-box" style="background: <?php echo $primaryColor; ?>;">
            Primary Color<br><?php echo $primaryColor; ?>
        </div>

        <div class="color-box" style="background: <?php echo $secondaryColor; ?>;">
            Secondary Color<br><?php echo $secondaryColor; ?>
        </div>

        <div class="color-box gradient">
            Gradient (Header/Button)<br><?php echo $primaryColor; ?> → <?php echo $secondaryColor; ?>
        </div>

        <div class="warning">
            <strong>⚠️ If you see purple in the chatbot on your site:</strong><br>
            It's a browser cache issue. The database has the correct colors above.<br><br>
            <strong>Solution:</strong> Clear your browser cache with Ctrl+Shift+Delete or try incognito mode.
        </div>

        <div class="info">
            <strong>Expected Colors (from your settings screenshot):</strong><br>
            Primary: #2ec86c (Green) <span style="display: inline-block; width: 30px; height: 30px; background: #2ec86c; border-radius: 4px; vertical-align: middle; margin-left: 10px;"></span><br>
            Secondary: #11455b (Dark Teal) <span style="display: inline-block; width: 30px; height: 30px; background: #11455b; border-radius: 4px; vertical-align: middle; margin-left: 10px;"></span>
        </div>

        <?php if ($primaryColor === '#2ec86c' && $secondaryColor === '#11455b'): ?>
            <div style="background: #e8f5e9; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #4caf50;">
                <strong>✅ PERFECT!</strong> Your database has the correct green and teal colors.<br>
                The purple you see is just browser cache. Clear it to see these colors.
            </div>
        <?php else: ?>
            <div style="background: #ffebee; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #f44336;">
                <strong>⚠️ Colors Don't Match!</strong><br>
                Go to Admin → Settings → Chatbot Appearance and save your colors again.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
