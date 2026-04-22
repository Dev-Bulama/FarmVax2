<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TelemedicineRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'requester_id', 'professional_id', 'livestock_id',
        'room_code', 'status', 'reason', 'notes',
        'professional_notes', 'admin_notes', 'priority',
        'scheduled_at', 'started_at', 'ended_at', 'duration_minutes',
        'is_follow_up', 'parent_request_id',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at'   => 'datetime',
            'started_at'     => 'datetime',
            'ended_at'       => 'datetime',
            'is_follow_up'   => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->room_code)) {
                $model->room_code = 'farmvax-' . Str::random(12);
            }
        });
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function parentRequest()
    {
        return $this->belongsTo(TelemedicineRequest::class, 'parent_request_id');
    }

    public function followUps()
    {
        return $this->hasMany(TelemedicineRequest::class, 'parent_request_id');
    }

    public function isPending()   { return $this->status === 'pending'; }
    public function isAssigned()  { return $this->status === 'assigned'; }
    public function isActive()    { return $this->status === 'in_progress'; }
    public function isCompleted() { return $this->status === 'completed'; }
    public function isCancelled() { return $this->status === 'cancelled'; }

    public function getJitsiUrlAttribute(): string
    {
        return 'https://meet.jit.si/' . $this->room_code;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'     => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'assigned'    => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">Assigned</span>',
            'in_progress' => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">In Progress</span>',
            'completed'   => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">Completed</span>',
            'cancelled'   => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Cancelled</span>',
            default       => '<span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">' . ucfirst($this->status) . '</span>',
        };
    }
}
