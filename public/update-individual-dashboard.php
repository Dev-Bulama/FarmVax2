<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>Update Individual Dashboard with Ads</h2>";
echo "<hr>";

$dashboardPath = base_path('resources/views/individual/dashboard.blade.php');

if (!file_exists($dashboardPath)) {
    echo "❌ File not found: {$dashboardPath}<br>";
    exit;
}

echo "✅ Found file: {$dashboardPath}<br>";

// Read current content
$content = file_get_contents($dashboardPath);

// Check if already updated
if (strpos($content, 'AdService') !== false) {
    echo "⚠️ File already has AdService code. No changes needed.<br>";
    exit;
}

echo "Updating file...<br><br>";

// Find the @php section and add AdService
$adServiceCode = "\n    // ✅ Ad Service for displaying ads\n    \$adService = new \\App\\Services\\AdService();\n    \$bannerAds = \$adService->getBannerAds(\$user);\n    \$sidebarAds = \$adService->getSidebarAds(\$user);\n    \$inlineAds = \$adService->getInlineAds(\$user);\n    ";

// Insert after @php and $user = auth()->user();
if (preg_match('/@php\s+\$user\s*=\s*auth\(\)->user\(\);/s', $content)) {
    $content = preg_replace(
        '/(@php\s+\$user\s*=\s*auth\(\)->user\(\);)/s',
        "$1" . $adServiceCode,
        $content,
        1
    );
    echo "✅ Added AdService initialization<br>";
} else {
    echo "⚠️ Could not find @php section. You may need to add manually.<br>";
}

// Add banner ad section after page header
$bannerAdHtml = <<<'HTML'

    <!-- ✅ BANNER AD (Full Width at Top) -->
    @if($bannerAds && $bannerAds->count() > 0)
        <div class="mb-6">
            @foreach($bannerAds as $ad)
                <div class="relative rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition bg-white">
                    <span class="absolute top-3 right-3 bg-gray-900 bg-opacity-75 text-white text-xs px-3 py-1 rounded-full z-10">
                        Sponsored
                    </span>
                    <a href="{{ $ad->link_url ? route('ad.click', $ad->id) : '#' }}" 
                       target="{{ $ad->link_url ? '_blank' : '_self' }}">
                        @if($ad->image_url)
                            <img src="{{ asset('storage/' . $ad->image_url) }}" 
                                 alt="{{ $ad->title }}" 
                                 class="w-full h-40 md:h-56 object-cover">
                        @else
                            <div class="w-full h-40 md:h-56 bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                                <div class="text-center text-white p-6">
                                    <h3 class="text-2xl font-bold mb-2">{{ $ad->title }}</h3>
                                    <p class="text-sm">{{ Str::limit($ad->description, 150) }}</p>
                                </div>
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    @endif

HTML;

// Insert banner ad after outbreak alerts or after welcome message
if (preg_match('/@endif\s*\n\s*<!-- Statistics Cards -->/s', $content)) {
    $content = preg_replace(
        '/(@endif\s*\n)\s*(<!-- Statistics Cards -->)/s',
        "$1" . $bannerAdHtml . "\n    $2",
        $content,
        1
    );
    echo "✅ Added banner ad section<br>";
}

// Backup original file
$backupPath = $dashboardPath . '.backup';
copy($dashboardPath, $backupPath);
echo "✅ Created backup: {$backupPath}<br>";

// Write updated content
file_put_contents($dashboardPath, $content);
echo "✅ Updated dashboard file<br>";

echo "<hr>";
echo "<h3>✅ Update Complete!</h3>";
echo "<p>Now test by visiting: <a href='/farmer/dashboard' target='_blank'>/farmer/dashboard</a></p>";
echo "<p>If you use a different route, try: <a href='/individual/dashboard' target='_blank'>/individual/dashboard</a></p>";

echo "<br><strong style='color: red;'>DELETE THIS FILE!</strong>";