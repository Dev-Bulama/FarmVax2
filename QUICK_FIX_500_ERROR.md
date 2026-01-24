# 🚨 QUICK FIX - 500 Error on hPanel

**Problem:** Getting "HTTP ERROR 500" after uploading new files

**Cause:** Laravel cache needs to be cleared after file updates

---

## ✅ SOLUTION (3 Steps - Takes 2 Minutes)

### **Step 1: Clear Cache Automatically**

Visit this URL in your browser:
```
https://farmvax.com/manual-cache-clear.php
```

**What it does:**
- Deletes all cached files
- Clears compiled views
- Removes old route cache
- No Laravel loading required

**Expected Result:**
- Shows "Manual Cache Clear Complete!"
- Lists number of files deleted

---

### **Step 2: Check What's Wrong**

If Step 1 didn't fix it, visit:
```
https://farmvax.com/check-error.php
```

**What it shows:**
- PHP version check
- Required extensions status
- File permissions
- Database connection test
- **Detailed error messages from Laravel log**

**Look for:**
- Red "❌" marks showing what's failing
- Error messages at the bottom (Laravel log)
- Missing PHP extensions

---

### **Step 3: Fix Laravel Bootstrap (If Needed)**

If Laravel is loading but still errors, visit:
```
https://farmvax.com/fix-500-error.php
```

**What it does:**
- Runs all Laravel cache clear commands
- Shows recent error logs
- Provides specific error details

---

## 🔍 COMMON CAUSES & FIXES

### **1. Cache Issue (Most Common)**
**Symptom:** Site worked before, now 500 error after file update
**Fix:** Run Step 1 above (manual-cache-clear.php)

### **2. File Permissions**
**Symptom:** "Permission denied" in error log
**Fix via cPanel:**
1. Go to File Manager
2. Navigate to FarmVax folder
3. Right-click `storage` folder → Change Permissions → Set to 755
4. Right-click `bootstrap/cache` → Change Permissions → Set to 755
5. Check "Apply to subdirectories"

### **3. PHP Version Too Old**
**Symptom:** check-error.php shows PHP < 8.1
**Fix via cPanel:**
1. Go to cPanel → "Select PHP Version" or "MultiPHP Manager"
2. Select PHP 8.1 or 8.2
3. Click Apply
4. Clear cache again (Step 1)

### **4. Missing PHP Extensions**
**Symptom:** check-error.php shows missing extensions
**Fix via cPanel:**
1. Go to "Select PHP Version"
2. Click "Extensions" or "PHP Extensions"
3. Enable: mbstring, openssl, pdo, pdo_mysql, curl, zip, xml
4. Save
5. Clear cache again (Step 1)

### **5. Database Connection Failed**
**Symptom:** check-error.php shows "Database Error"
**Fix:**
1. Verify .env file has correct credentials:
   ```
   DB_HOST=localhost
   DB_DATABASE=u440055003_farmvaxDB
   DB_USERNAME=u440055003_farmvaxDB
   DB_PASSWORD=myasswr
   ```
2. Test database connection in cPanel → phpMyAdmin
3. If database doesn't exist, create it in cPanel → MySQL Databases

### **6. Composer Autoload Issue**
**Symptom:** "Class not found" errors
**Fix (Requires SSH):**
```bash
cd /home/u440055003/domains/farmvax.com/public_html
composer dump-autoload
```

**Fix (Without SSH):**
- Contact your hosting provider to run: `composer dump-autoload`
- Or use cPanel Terminal (if available)

---

## 📋 STEP-BY-STEP TROUBLESHOOTING

**Start here:**
1. ✅ Visit: `https://farmvax.com/manual-cache-clear.php`
2. ✅ Click refresh on your site
3. ✅ Does it work now?
   - **YES** → You're done! Delete the diagnostic files.
   - **NO** → Continue to step 4

4. ✅ Visit: `https://farmvax.com/check-error.php`
5. ✅ Scroll to "Recent Laravel Log" section
6. ✅ Look for error messages (usually start with "[" or contain "Exception")
7. ✅ Read the error message to identify the issue
8. ✅ Apply the fix from "Common Causes" section above

**Still stuck?**
9. ✅ Visit: `https://farmvax.com/fix-500-error.php`
10. ✅ Read the error details carefully
11. ✅ Copy the error message
12. ✅ Share the error message for help

---

## 🎯 MOST LIKELY FIX (Try This First)

Based on your situation, the 500 error is almost certainly a **cache issue**.

**Do this:**

1. Open browser
2. Visit: `https://farmvax.com/manual-cache-clear.php`
3. Wait for "Complete!" message
4. Visit: `https://farmvax.com`
5. **Should work now!**

If not, check PHP version:
6. Visit: `https://farmvax.com/check-error.php`
7. Look for "PHP Version" row
8. Must be 8.1 or higher
9. If lower, change in cPanel → MultiPHP Manager

---

## 🧹 CLEANUP (After Fix)

**Once your site is working, delete these files for security:**

Via cPanel File Manager, delete:
```
public/manual-cache-clear.php
public/check-error.php
public/fix-500-error.php
public/create-system-versions-table.php (if not already deleted)
```

---

## 📞 QUICK REFERENCE

| Issue | File to Run | What It Does |
|-------|-------------|--------------|
| Site down after update | manual-cache-clear.php | Clears all cache files |
| Need to see error | check-error.php | Shows detailed diagnostics |
| Laravel specific issue | fix-500-error.php | Runs Laravel cache clear |
| New database table | create-system-versions-table.php | Creates system_versions table |

---

## ✅ SUCCESS CHECKLIST

After fixing, verify these are working:

- [ ] Homepage loads: `https://farmvax.com`
- [ ] Login page works: `https://farmvax.com/login`
- [ ] Admin dashboard: `https://farmvax.com/admin/dashboard`
- [ ] Farmer registration: `https://farmvax.com/register/farmer`
- [ ] No 500 errors anywhere

---

## 🔒 SECURITY NOTE

**After your site is working:**
1. Delete all diagnostic files (listed in Cleanup section)
2. These files show sensitive system information
3. Should only be used temporarily for debugging

---

**Need More Help?**

If still getting 500 error after trying all steps:
1. Run check-error.php
2. Scroll to bottom (Laravel Log section)
3. Copy the last 20 lines that show errors
4. Share those error lines for specific help

---

*Generated: 2026-01-24*
*FarmVax Production Fixes*
