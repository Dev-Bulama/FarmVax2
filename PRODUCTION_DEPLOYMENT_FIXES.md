# Production Deployment Critical Fixes

## Issue: Document Files Showing 404 Error

**Problem:** When viewing uploaded professional documents, URLs like `https://farmvax.com/storage/documents/licenses/xyz.pdf` return 404 errors.

**Root Cause:** The symbolic link from `public/storage` to `storage/app/public` doesn't exist on the production server.

### Solution:

SSH into your production server and run:

```bash
cd /home/u440055003/domains/farmvax.com/public_html

# Create the storage symlink
php artisan storage:link

# Verify the symlink was created
ls -la public/storage
```

**Expected Output:**
```
lrwxrwxrwx 1 user user 45 Jan 31 12:00 storage -> /home/u440055003/domains/farmvax.com/public_html/storage/app/public
```

### Verify Fix:

After creating the symlink, uploaded documents should be accessible at:
- https://farmvax.com/storage/documents/licenses/filename.pdf
- https://farmvax.com/storage/documents/certificates/filename.pdf
- https://farmvax.com/storage/documents/id-cards/filename.pdf

---

## Pull Latest Code Changes

To get all the fixes pushed in this session:

```bash
cd /home/u440055003/domains/farmvax.com/public_html

# Pull latest changes
git pull origin claude/farmvax-production-fixes-P2RFL

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

---

## Email & SMS Configuration Issues

Based on the error screenshots provided, you also need to configure:

### 1. SMTP Email Configuration

**Error:** `Failed to authenticate on SMTP server with username "email@farmvax.com"`

**Solution:** Update `.env` file with correct SMTP credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@farmvax.com
MAIL_PASSWORD=your-actual-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmvax.com
MAIL_FROM_NAME="FarmVax"
```

After updating, run:
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. SMS Configuration

**Error:** `Failed to send SMS: ("error":"Sender ID not registered.","errno":130)`

**Solution:** Register your sender ID with your SMS provider or use a registered sender ID:

Update `.env`:
```env
SMS_PROVIDER=your_provider
SMS_SENDER_ID=RegisteredSenderID
SMS_API_KEY=your_api_key
```

---

## Verification Checklist

After applying all fixes, verify:

- [ ] Documents can be viewed/downloaded from admin professional review page
- [ ] Location auto-detect prefills Country, State, and LGA dropdowns
- [ ] Bulk user selection shows count and conversion options
- [ ] Email notifications are being sent successfully
- [ ] SMS notifications are being sent successfully
- [ ] System updates can be uploaded and applied
- [ ] No ParseError in routes/web.php

---

## Support

If you encounter any issues after applying these fixes, check:

1. **Error logs:** `storage/logs/laravel.log`
2. **Web server logs:** Apache/Nginx error logs
3. **File permissions:** Ensure `storage/` and `bootstrap/cache/` are writable (775)

```bash
# Fix permissions if needed
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```
