<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCollector extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'data_collectors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'organization',
        'experience_years',
        'assigned_territory',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'submitted_at',
        'application_notes',
        'verification_documents',
        'contact_phone',
        'contact_email',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'experience_years' => 'integer',
        'verification_documents' => 'array',
    ];

    /**
     * Get the user that owns the data collector profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved/rejected the application.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get farm records created by this data collector.
     */
    public function farmRecords()
    {
        return $this->hasMany(FarmRecord::class, 'collector_id');
    }

    /**
     * Scope to get pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope to get approved applications.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope to get rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    /**
     * Check if the application is pending.
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if the application is approved.
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if the application is rejected.
     */
    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->approval_status) {
            'approved' => 'green',
            'rejected' => 'red',
            'pending' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get the status display text.
     */
    public function getStatusTextAttribute()
    {
        return ucfirst($this->approval_status);
    }
}