<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationDocument extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verification_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'data_collector_profile_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'document_number',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        'category',
        'is_primary',
        'is_visible_to_admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'verified_at' => 'datetime',
            'is_primary' => 'boolean',
            'is_visible_to_admin' => 'boolean',
        ];
    }

    /**
     * Relationships
     */

    // User who owns this document (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Data Collector Profile (Belongs To)
    public function dataCollectorProfile()
    {
        return $this->belongsTo(DataCollectorProfile::class);
    }

    // Admin who verified this document (Belongs To)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Status Checking Methods
     */

    public function isPending()
    {
        return $this->verification_status === 'pending';
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function isRejected()
    {
        return $this->verification_status === 'rejected';
    }

    public function isExpired()
    {
        return $this->verification_status === 'expired' || 
               ($this->expiry_date && $this->expiry_date->isPast());
    }

    /**
     * Category Checking Methods
     */

    public function isIdentification()
    {
        return $this->category === 'identification';
    }

    public function isEducational()
    {
        return $this->category === 'educational';
    }

    public function isProfessional()
    {
        return $this->category === 'professional';
    }

    public function isAuthorization()
    {
        return $this->category === 'authorization';
    }

    /**
     * Get document URL
     */
    public function getDocumentUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }

        return now()->diffInDays($this->expiry_date, false);
    }

    /**
     * Check if expiring soon (within 30 days)
     */
    public function isExpiringSoon()
    {
        if (!$this->expiry_date) {
            return false;
        }

        $daysUntilExpiry = $this->getDaysUntilExpiryAttribute();
        return $daysUntilExpiry !== null && $daysUntilExpiry > 0 && $daysUntilExpiry <= 30;
    }

    /**
     * Get verification status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->verification_status) {
            'pending' => 'yellow',
            'verified' => 'green',
            'rejected' => 'red',
            'expired' => 'gray',
            default => 'blue',
        };
    }

    /**
     * Get category icon
     */
    public function getCategoryIconAttribute()
    {
        return match($this->category) {
            'identification' => 'id-card',
            'educational' => 'academic-cap',
            'professional' => 'briefcase',
            'authorization' => 'document-check',
            'reference' => 'user-group',
            default => 'document',
        };
    }

    /**
     * Scope Queries
     */

    // Get pending documents
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    // Get verified documents
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    // Get rejected documents
    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    // Get expired documents
    public function scopeExpired($query)
    {
        return $query->where('verification_status', 'expired')
                     ->orWhere(function($q) {
                         $q->whereNotNull('expiry_date')
                           ->where('expiry_date', '<', now());
                     });
    }

    // Get documents expiring soon (within 30 days)
    public function scopeExpiringSoon($query)
    {
        return $query->whereNotNull('expiry_date')
                     ->where('expiry_date', '>', now())
                     ->where('expiry_date', '<=', now()->addDays(30));
    }

    // Get by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Get primary documents only
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Get visible to admin
    public function scopeVisibleToAdmin($query)
    {
        return $query->where('is_visible_to_admin', true);
    }

    /**
     * Verification Methods
     */

    public function verify($adminId, $notes = null)
    {
        $this->verification_status = 'verified';
        $this->verification_notes = $notes;
        $this->verified_at = now();
        $this->verified_by = $adminId;
        $this->save();

        return $this;
    }

    public function reject($adminId, $notes = null)
    {
        $this->verification_status = 'rejected';
        $this->verification_notes = $notes;
        $this->verified_at = now();
        $this->verified_by = $adminId;
        $this->save();

        return $this;
    }

    public function markAsExpired()
    {
        $this->verification_status = 'expired';
        $this->save();

        return $this;
    }

    /**
     * Check if document needs renewal
     */
    public function needsRenewal()
    {
        if (!$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast() || $this->isExpiringSoon();
    }

    /**
     * Get full document information
     */
    public function getFullInfoAttribute()
    {
        $info = [
            'type' => $this->document_type,
            'name' => $this->document_name,
            'category' => ucfirst($this->category),
            'status' => ucfirst($this->verification_status),
        ];

        if ($this->document_number) {
            $info['number'] = $this->document_number;
        }

        if ($this->issuing_authority) {
            $info['issuer'] = $this->issuing_authority;
        }

        if ($this->expiry_date) {
            $info['expiry'] = $this->expiry_date->format('M d, Y');
        }

        return $info;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a document, set default values
        static::creating(function ($document) {
            if (!$document->verification_status) {
                $document->verification_status = 'pending';
            }

            if (!$document->category) {
                $document->category = 'other';
            }

            if (!isset($document->is_visible_to_admin)) {
                $document->is_visible_to_admin = true;
            }
        });

        // After updating, check if document has expired
        static::updating(function ($document) {
            if ($document->expiry_date && $document->expiry_date->isPast() && $document->verification_status === 'verified') {
                $document->verification_status = 'expired';
            }
        });
    }
}