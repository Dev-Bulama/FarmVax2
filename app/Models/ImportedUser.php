<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ImportedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id',
        'user_id',
        'generated_password',
        'welcome_email_sent',
        'welcome_email_sent_at',
        'email_resend_count',
        'last_email_sent_at',
    ];

    protected $casts = [
        'welcome_email_sent' => 'boolean',
        'welcome_email_sent_at' => 'datetime',
        'last_email_sent_at' => 'datetime',
    ];

    /**
     * Get the import batch this user belongs to
     */
    public function import()
    {
        return $this->belongsTo(UserImport::class, 'import_id');
    }

    /**
     * Get the user record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Set encrypted password
     */
    public function setGeneratedPasswordAttribute($value)
    {
        $this->attributes['generated_password'] = Crypt::encryptString($value);
    }

    /**
     * Get decrypted password
     */
    public function getDecryptedPasswordAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['generated_password']);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if welcome email was sent
     */
    public function hasReceivedWelcomeEmail()
    {
        return $this->welcome_email_sent;
    }

    /**
     * Check if user can receive another email (resend)
     */
    public function canResendEmail()
    {
        // Allow resend if:
        // 1. Email was never sent, OR
        // 2. Last email was sent more than 1 hour ago, OR
        // 3. Resend count is less than 5 (max 5 resends)
        
        if (!$this->welcome_email_sent) {
            return true;
        }

        if ($this->email_resend_count >= 5) {
            return false;
        }

        if ($this->last_email_sent_at && $this->last_email_sent_at->diffInHours(now()) < 1) {
            return false;
        }

        return true;
    }

    /**
     * Mark email as sent
     */
    public function markEmailAsSent()
    {
        $this->update([
            'welcome_email_sent' => true,
            'welcome_email_sent_at' => $this->welcome_email_sent_at ?? now(),
            'last_email_sent_at' => now(),
            'email_resend_count' => $this->welcome_email_sent ? $this->email_resend_count + 1 : 0,
        ]);
    }

    /**
     * Get time since last email
     */
    public function getTimeSinceLastEmailAttribute()
    {
        if (!$this->last_email_sent_at) {
            return 'Never';
        }

        return $this->last_email_sent_at->diffForHumans();
    }

    /**
     * Get email status badge color
     */
    public function getEmailStatusColorAttribute()
    {
        if (!$this->welcome_email_sent) {
            return 'red';
        }
        
        if ($this->email_resend_count > 0) {
            return 'orange';
        }
        
        return 'green';
    }

    /**
     * Get email status text
     */
    public function getEmailStatusTextAttribute()
    {
        if (!$this->welcome_email_sent) {
            return 'Not Sent';
        }
        
        if ($this->email_resend_count > 0) {
            return 'Resent ' . $this->email_resend_count . 'x';
        }
        
        return 'Sent';
    }

    /**
     * Scope to get users with pending emails
     */
    public function scopePendingEmail($query)
    {
        return $query->where('welcome_email_sent', false);
    }

    /**
     * Scope to get users with sent emails
     */
    public function scopeEmailSent($query)
    {
        return $query->where('welcome_email_sent', true);
    }

    /**
     * Scope to get users by import batch
     */
    public function scopeByImport($query, $importId)
    {
        return $query->where('import_id', $importId);
    }

    /**
     * Scope to get users who can receive resend
     */
    public function scopeCanResend($query)
    {
        return $query->where(function($q) {
            $q->where('welcome_email_sent', false)
              ->orWhere(function($q2) {
                  $q2->where('email_resend_count', '<', 5)
                     ->where(function($q3) {
                         $q3->whereNull('last_email_sent_at')
                            ->orWhere('last_email_sent_at', '<=', now()->subHour());
                     });
              });
        });
    }

    /**
     * Get remaining resend attempts
     */
    public function getRemainingResendsAttribute()
    {
        return max(0, 5 - $this->email_resend_count);
    }

    /**
     * Check if max resends reached
     */
    public function hasReachedMaxResends()
    {
        return $this->email_resend_count >= 5;
    }
}