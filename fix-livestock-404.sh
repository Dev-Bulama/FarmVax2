#!/bin/bash
# Fix Livestock 404 Error on Production
# Run this script on your production server

echo "🔧 FarmVax Livestock 404 Fix"
echo "=============================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Are you in the FarmVax directory?"
    echo "   Run: cd /path/to/farmvax"
    exit 1
fi

echo "✅ Found artisan file"
echo ""

# Step 1: Check if controller exists
echo "📁 Checking if LivestockController exists..."
if [ -f "app/Http/Controllers/Admin/LivestockController.php" ]; then
    echo "✅ LivestockController found"
else
    echo "❌ LivestockController NOT found!"
    echo "   You need to pull the latest code first:"
    echo "   git pull origin claude/farmvax-production-fixes-P2RFL"
    exit 1
fi
echo ""

# Step 2: Check if views exist
echo "📁 Checking if livestock views exist..."
if [ -d "resources/views/admin/livestock" ]; then
    echo "✅ Livestock views directory found"
    ls -la resources/views/admin/livestock/
else
    echo "❌ Livestock views NOT found!"
    echo "   You need to pull the latest code first:"
    echo "   git pull origin claude/farmvax-production-fixes-P2RFL"
    exit 1
fi
echo ""

# Step 3: Regenerate composer autoload
echo "🔄 Regenerating composer autoload..."
composer dump-autoload
if [ $? -eq 0 ]; then
    echo "✅ Autoload regenerated"
else
    echo "❌ Autoload failed - continuing anyway..."
fi
echo ""

# Step 4: Clear ALL caches (including route cache)
echo "🗑️  Clearing all Laravel caches..."
php artisan route:cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Also try clearing the route cache explicitly
php artisan route:clear

echo "✅ All caches cleared"
echo ""

# Step 5: List routes to verify
echo "📋 Checking if admin.livestock routes are registered..."
php artisan route:list --name=livestock | grep -i admin

if [ $? -eq 0 ]; then
    echo "✅ Admin livestock routes found!"
else
    echo "❌ Admin livestock routes NOT found in route list"
    echo "   This might be a routes file syntax error"
fi
echo ""

# Step 6: Check file permissions
echo "🔒 Checking file permissions..."
ls -l app/Http/Controllers/Admin/LivestockController.php

echo ""
echo "=============================="
echo "🎯 Fix Complete!"
echo ""
echo "Now test:"
echo "1. Visit: https://farmvax.com/admin/livestock"
echo "2. Check admin sidebar for 'Livestock' under 'Records'"
echo ""
echo "If still getting 404, share the output of:"
echo "  php artisan route:list | grep livestock"
