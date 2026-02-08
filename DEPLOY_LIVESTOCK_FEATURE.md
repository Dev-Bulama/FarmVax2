# Deploy Livestock Management Feature to Production

## Problem
You're seeing 404 errors at `/admin/livestock` and the Livestock link is missing from the admin sidebar. This is because your production server doesn't have the latest code.

## Verification First
1. Visit: **https://farmvax.com/version-check.php**
2. This diagnostic page will show what's missing on your production server
3. All checks should show ✅ green. If you see ❌ red marks, continue below.

## Deployment Steps (Run on Production Server)

### Step 1: SSH into Production
```bash
ssh your-user@your-production-server
cd /path/to/farmvax  # Navigate to your FarmVax installation
```

### Step 2: Backup Current State
```bash
# Create a backup branch (just in case)
git branch backup-$(date +%Y%m%d-%H%M%S)

# Check current status
git status
git log --oneline -1
```

### Step 3: Pull Latest Code
```bash
# Fetch all changes
git fetch origin

# Pull the livestock feature branch
git pull origin claude/farmvax-production-fixes-P2RFL

# OR if you need to merge into main/master:
# git checkout main
# git merge claude/farmvax-production-fixes-P2RFL
```

### Step 4: Clear All Caches
```bash
# Clear Laravel caches
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# If using opcache, restart PHP-FPM
sudo systemctl restart php8.2-fpm
# OR
sudo systemctl restart php-fpm
```

### Step 5: Verify Deployment
```bash
# Check that files exist
ls -l app/Http/Controllers/Admin/LivestockController.php
ls -l resources/views/admin/livestock/

# Should show:
# LivestockController.php exists
# livestock/ directory with index.blade.php and show.blade.php
```

### Step 6: Check Version Page Again
Visit: **https://farmvax.com/version-check.php**

All checks should now be ✅ green.

### Step 7: Access Livestock Management
Go to: **https://farmvax.com/admin/livestock**

You should see the livestock management page with:
- Stats cards (Total, By Type, Health Status)
- Search and filter options
- Livestock table with owner details
- Import button for CSV uploads

The "Livestock" link will appear in your admin sidebar under the **Records** dropdown.

## What This Feature Includes

### Admin Side (`/admin/livestock`)
- View all livestock across all farmers
- Search by tag number, name, breed, owner
- Filter by livestock type and health status
- See owner details (farmer name, email, phone)
- Import livestock from CSV file
- Download CSV template for imports
- View individual livestock with full details

### Farmer Side (Bug Fixes)
- Fixed livestock type enum values (goat, pig, chicken, etc.)
- Fixed health status options (recovering instead of under_treatment)
- Fixed field mapping (weight_kg→weight, color_markings→color)
- Made tag_number optional (not required)
- Added "unknown" gender option
- Fixed RoleMiddleware to properly handle farmer role

## Files Deployed

### New Files:
- `app/Http/Controllers/Admin/LivestockController.php`
- `resources/views/admin/livestock/index.blade.php`
- `resources/views/admin/livestock/show.blade.php`
- `public/version-check.php` (diagnostic tool)

### Modified Files:
- `app/Http/Controllers/Farmer/LivestockController.php`
- `app/Http/Controllers/Individual/LivestockController.php`
- `app/Http/Middleware/RoleMiddleware.php`
- `resources/views/admin/partials/sidebar.blade.php`
- `resources/views/farmer/livestock/create.blade.php`
- `resources/views/individual/livestock/create.blade.php`
- `resources/views/individual/livestock/edit.blade.php`
- `routes/web.php`

## Commits Included
```
1caccbd - Add deployment version check diagnostic tool
ad3b872 - Fix farmer livestock addition - enum values, validation, and middleware
d133ed7 - Fix Records dropdown to auto-expand when on livestock pages
82734fe - Add admin livestock management and fix farmer livestock bugs
```

## Troubleshooting

### Still seeing 404?
```bash
# Check routes are registered
php artisan route:list | grep livestock

# Should show:
# admin.livestock.index
# admin.livestock.show
# admin.livestock.import
# admin.livestock.import.template
```

### Link still not in sidebar?
```bash
# Check sidebar file content
grep -n "admin.livestock.index" resources/views/admin/partials/sidebar.blade.php

# Should show line number with the route
```

### Permission issues?
```bash
# Fix ownership (adjust user:group as needed)
chown -R www-data:www-data .
chmod -R 755 storage bootstrap/cache
```

## Clean Up After Deployment
Once everything works:
```bash
# Remove diagnostic file for security
rm public/version-check.php
```

## Need Help?
If you still see 404 or missing links after following all steps:
1. Share the output of: `git log --oneline -5`
2. Share the output of: `php artisan route:list | grep livestock`
3. Check Laravel logs: `tail -50 storage/logs/laravel.log`
