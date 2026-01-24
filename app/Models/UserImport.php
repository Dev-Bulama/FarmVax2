<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'imported_by',
        'original_filename',
        'stored_filename',
        'user_type',
        'total_records',
        'successful_imports',
        'failed_imports',
        'duplicate_emails',
        'status',
        'column_mapping',
        'errors',
        'imported_user_ids',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'column_mapping' => 'array',
        'errors' => 'array',
        'imported_user_ids' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the admin who performed the import
     */
    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    /**
     * Get all imported users from this import batch
     */
    public function importedUsers()
    {
        return $this->hasMany(ImportedUser::class, 'import_id');
    }

    /**
     * Get users who haven't received welcome email
     */
    public function pendingEmails()
    {
        return $this->importedUsers()->where('welcome_email_sent', false);
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_records == 0) {
            return 0;
        }
        return round(($this->successful_imports / $this->total_records) * 100, 2);
    }

    /**
     * Get failure rate percentage
     */
    public function getFailureRateAttribute()
    {
        if ($this->total_records == 0) {
            return 0;
        }
        return round(($this->failed_imports / $this->total_records) * 100, 2);
    }

    /**
     * Check if import is complete
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if import is still processing
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Check if import failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Mark import as started
     */
    public function markAsStarted()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark import as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark import as failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $errors = $this->errors ?? [];
        
        if ($errorMessage) {
            $errors[] = [
                'message' => $errorMessage,
                'timestamp' => now()->toDateTimeString(),
            ];
        }

        $this->update([
            'status' => 'failed',
            'errors' => $errors,
            'completed_at' => now(),
        ]);
    }

    /**
     * Add error to import record
     */
    public function addError($row, $field, $message)
    {
        $errors = $this->errors ?? [];
        
        $errors[] = [
            'row' => $row,
            'field' => $field,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->update(['errors' => $errors]);
    }

    /**
     * Increment successful imports count
     */
    public function incrementSuccess($userId = null)
    {
        $this->increment('successful_imports');
        
        if ($userId) {
            $importedIds = $this->imported_user_ids ?? [];
            $importedIds[] = $userId;
            $this->update(['imported_user_ids' => $importedIds]);
        }
    }

    /**
     * Increment failed imports count
     */
    public function incrementFailed()
    {
        $this->increment('failed_imports');
    }

    /**
     * Increment duplicate emails count
     */
    public function incrementDuplicates()
    {
        $this->increment('duplicate_emails');
    }

    /**
     * Get duration of import in seconds
     */
    public function getDurationAttribute()
    {
        if (!$this->started_at) {
            return 0;
        }

        $end = $this->completed_at ?? now();
        return $this->started_at->diffInSeconds($end);
    }

    /**
     * Get human-readable duration
     */
    public function getHumanDurationAttribute()
    {
        if ($this->duration < 60) {
            return $this->duration . ' seconds';
        } elseif ($this->duration < 3600) {
            return round($this->duration / 60, 1) . ' minutes';
        } else {
            return round($this->duration / 3600, 1) . ' hours';
        }
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'gray',
            'processing' => 'yellow',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get user type display name
     */
    public function getUserTypeDisplayAttribute()
    {
        return match($this->user_type) {
            'farmer' => 'Farmers',
            'volunteer' => 'Volunteers',
            'animal_health_professional' => 'Professionals',
            default => ucfirst($this->user_type),
        };
    }

    /**
     * Scope to get recent imports
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get completed imports
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get failed imports
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to get imports by user type
     */
    public function scopeByUserType($query, $type)
    {
        return $query->where('user_type', $type);
    }
}