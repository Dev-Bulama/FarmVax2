<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'farm_record_id',
        'livestock_id',
        'requested_by_role',
        'service_type',
        'other_service_type',
        'service_title',
        'service_description',
        'livestock_type',
        'number_of_animals',
        'affected_animals',
        'symptoms',
        'symptoms_description',
        'symptoms_start_date',
        'is_contagious',
        'affected_count',
        'urgency_level',
        'priority',
        'requires_immediate_attention',
        'urgency_reason',
        'preferred_date',
        'preferred_time',
        'alternative_date',
        'alternative_time',
        'time_preference',
        'scheduling_notes',
        'service_location',
        'location_type',
        'latitude',
        'longitude',
        'location_instructions',
        'assigned_to',
        'assigned_at',
        'assigned_by',
        'assigned_veterinarian_name',
        'assigned_veterinarian_phone',
        'status',
        'status_notes',
        'acknowledged_at',
        'scheduled_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'actual_service_date',
        'actual_service_time',
        'duration_minutes',
        'service_notes',
        'diagnosis',
        'treatment_provided',
        'medications_prescribed',
        'recommendations',
        'requires_followup',
        'followup_date',
        'followup_instructions',
        'followup_completed',
        'followup_completed_date',
        'estimated_cost',
        'actual_cost',
        'service_fee',
        'medication_cost',
        'transport_cost',
        'total_cost',
        'currency',
        'payment_status',
        'payment_date',
        'payment_method',
        'payment_reference',
        'outcome',
        'outcome_description',
        'outcome_data',
        'animal_recovered',
        'recovery_date',
        'documents',
        'images',
        'prescription_document',
        'service_report',
        'rating',
        'feedback',
        'feedback_date',
        'would_recommend',
        'contact_phone',
        'contact_email',
        'alternative_contact',
        'preferred_contact_method',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'cancellation_reason',
        'requester_notified',
        'requester_notified_at',
        'provider_notified',
        'provider_notified_at',
        'reminder_sent',
        'reminder_sent_at',
        'reminder_count',
        'reference_number',
        'external_reference',
        'notes',
        'custom_fields',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'symptoms_start_date' => 'date',
            'preferred_date' => 'date',
            'preferred_time' => 'datetime',
            'alternative_date' => 'date',
            'alternative_time' => 'datetime',
            'assigned_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'actual_service_date' => 'date',
            'actual_service_time' => 'datetime',
            'followup_date' => 'date',
            'followup_completed_date' => 'date',
            'payment_date' => 'date',
            'recovery_date' => 'date',
            'feedback_date' => 'datetime',
            'reviewed_at' => 'datetime',
            'requester_notified_at' => 'datetime',
            'provider_notified_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
            'affected_animals' => 'array',
            'symptoms' => 'array',
            'medications_prescribed' => 'array',
            'outcome_data' => 'array',
            'documents' => 'array',
            'images' => 'array',
            'custom_fields' => 'array',
            'is_contagious' => 'boolean',
            'requires_immediate_attention' => 'boolean',
            'requires_followup' => 'boolean',
            'followup_completed' => 'boolean',
            'animal_recovered' => 'boolean',
            'would_recommend' => 'boolean',
            'requester_notified' => 'boolean',
            'provider_notified' => 'boolean',
            'reminder_sent' => 'boolean',
            'estimated_cost' => 'decimal:2',
            'actual_cost' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'medication_cost' => 'decimal:2',
            'transport_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Relationships
     */

    // User who created the request (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Farm record (Belongs To)
    public function farmRecord()
    {
        return $this->belongsTo(FarmRecord::class);
    }

    // Livestock (Belongs To)
    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    // Service provider assigned to (Belongs To)
    public function assignedProvider()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Admin who assigned (Belongs To)
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Admin who reviewed (Belongs To)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Status Checking Methods
     */

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAcknowledged()
    {
        return $this->status === 'acknowledged';
    }

    public function isAssigned()
    {
        return $this->status === 'assigned';
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Priority and Urgency Methods
     */

    public function isEmergency()
    {
        return $this->urgency_level === 'emergency';
    }

    public function isHighPriority()
    {
        return $this->priority === 'critical' || $this->urgency_level === 'emergency';
    }

    public function needsImmediateAttention()
    {
        return $this->requires_immediate_attention === true;
    }

    /**
     * Get urgency badge color
     */
    public function getUrgencyBadgeColorAttribute()
    {
        return match($this->urgency_level) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'emergency' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'acknowledged' => 'blue',
            'assigned' => 'indigo',
            'scheduled' => 'purple',
            'in_progress' => 'orange',
            'completed' => 'green',
            'cancelled' => 'gray',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentBadgeColorAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'green',
            'partial' => 'yellow',
            'unpaid' => 'red',
            'waived' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get service location URL
     */
    public function getLocationUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    /**
     * Get document URLs
     */
    public function getDocumentUrlsAttribute()
    {
        if (!$this->documents || !is_array($this->documents)) {
            return [];
        }

        return array_map(function ($doc) {
            return asset('storage/' . $doc);
        }, $this->documents);
    }

    /**
     * Get image URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    /**
     * Get duration in human readable format
     */
    public function getDurationHumanAttribute()
    {
        if (!$this->duration_minutes) {
            return null;
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours == 0) {
            return $minutes . ' minutes';
        }

        if ($minutes == 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        }

        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minutes';
    }

    /**
     * Check if overdue
     */
    public function isOverdue()
    {
        if (!$this->preferred_date || $this->isCompleted() || $this->isCancelled()) {
            return false;
        }

        return now()->greaterThan($this->preferred_date);
    }

    /**
     * Scope Queries
     */

    // Get by status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Get by urgency
    public function scopeEmergency($query)
    {
        return $query->where('urgency_level', 'emergency');
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency_level', ['high', 'emergency'])
                     ->orWhere('requires_immediate_attention', true);
    }

    // Get by service type
    public function scopeByServiceType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    // Get by payment status
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Get assigned to specific provider
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Get overdue requests
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('preferred_date')
                     ->where('preferred_date', '<', now())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // Get requiring follow-up
    public function scopeRequiringFollowup($query)
    {
        return $query->where('requires_followup', true)
                     ->where('followup_completed', false);
    }

    // Get today's scheduled services
    public function scopeTodayScheduled($query)
    {
        return $query->whereDate('preferred_date', now()->toDateString())
                     ->whereIn('status', ['scheduled', 'assigned']);
    }

    // Get by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('preferred_date', [$startDate, $endDate]);
    }

    // Get recent requests (last 7 days)
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    // Get highly rated services
    public function scopeHighlyRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Workflow Methods
     */

    public function acknowledge($adminId = null)
    {
        $this->status = 'acknowledged';
        $this->acknowledged_at = now();
        
        if ($adminId) {
            $this->reviewed_by = $adminId;
            $this->reviewed_at = now();
        }
        
        $this->save();

        return $this;
    }

    public function assign($providerId, $adminId = null)
    {
        $this->status = 'assigned';
        $this->assigned_to = $providerId;
        $this->assigned_at = now();
        $this->assigned_by = $adminId;
        $this->save();

        // Notify provider
        $this->notifyProvider();

        return $this;
    }

    public function schedule($date, $time = null)
    {
        $this->status = 'scheduled';
        $this->scheduled_at = now();
        $this->actual_service_date = $date;
        
        if ($time) {
            $this->actual_service_time = $time;
        }
        
        $this->save();

        // Notify requester
        $this->notifyRequester();

        return $this;
    }

    public function start()
    {
        $this->status = 'in_progress';
        $this->started_at = now();
        $this->save();

        return $this;
    }

    public function complete($diagnosis = null, $treatment = null, $notes = null)
    {
        $this->status = 'completed';
        $this->completed_at = now();
        
        if ($diagnosis) {
            $this->diagnosis = $diagnosis;
        }
        
        if ($treatment) {
            $this->treatment_provided = $treatment;
        }
        
        if ($notes) {
            $this->service_notes = $notes;
        }

        // Calculate duration if started_at exists
        if ($this->started_at) {
            $this->duration_minutes = $this->started_at->diffInMinutes(now());
        }
        
        $this->save();

        return $this;
    }

    public function cancel($reason = null, $adminId = null)
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        
        if ($adminId) {
            $this->reviewed_by = $adminId;
        }
        
        $this->save();

        return $this;
    }

    public function reject($reason, $adminId)
    {
        $this->status = 'rejected';
        $this->rejection_reason = $reason;
        $this->reviewed_by = $adminId;
        $this->reviewed_at = now();
        $this->save();

        return $this;
    }

    /**
     * Notification Methods
     */

    public function notifyRequester()
    {
        $this->requester_notified = true;
        $this->requester_notified_at = now();
        $this->save();

        // TODO: Implement actual notification logic (SMS, Email, etc.)

        return $this;
    }

    public function notifyProvider()
    {
        $this->provider_notified = true;
        $this->provider_notified_at = now();
        $this->save();

        // TODO: Implement actual notification logic

        return $this;
    }

    public function sendReminder()
    {
        $this->reminder_sent = true;
        $this->reminder_sent_at = now();
        $this->reminder_count++;
        $this->save();

        // TODO: Implement actual reminder logic

        return $this;
    }

    /**
     * Payment Methods
     */

    public function markAsPaid($amount, $method, $reference = null)
    {
        $this->payment_status = 'paid';
        $this->actual_cost = $amount;
        $this->total_cost = $amount;
        $this->payment_date = now();
        $this->payment_method = $method;
        $this->payment_reference = $reference;
        $this->save();

        return $this;
    }

    public function waivePayment($adminId, $notes = null)
    {
        $this->payment_status = 'waived';
        $this->reviewed_by = $adminId;
        
        if ($notes) {
            $this->admin_notes = $notes;
        }
        
        $this->save();

        return $this;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a service request, set default values
        static::creating(function ($request) {
            if (!$request->status) {
                $request->status = 'pending';
            }

            if (!$request->urgency_level) {
                $request->urgency_level = 'medium';
            }

            if (!$request->priority) {
                $request->priority = 'routine';
            }

            if (!$request->currency) {
                $request->currency = 'NGN';
            }

            if (!$request->payment_status) {
                $request->payment_status = 'unpaid';
            }

            if (!$request->preferred_contact_method) {
                $request->preferred_contact_method = 'phone';
            }

            // Generate unique reference number
            if (!$request->reference_number) {
                $request->reference_number = 'SR-' . strtoupper(Str::random(8));
            }
        });
    }
}