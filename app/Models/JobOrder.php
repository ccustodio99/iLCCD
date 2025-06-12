<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsAudit;

class JobOrder extends Model
{
    use HasFactory, LogsAudit;

    /** Job order status values */
    public const STATUS_PENDING_HEAD = 'pending_head';
    public const STATUS_PENDING_PRESIDENT = 'pending_president';
    public const STATUS_PENDING_FINANCE = 'pending_finance';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ASSIGNED = 'assigned';

    protected $fillable = [
        'user_id',
        'ticket_id',
        'job_type',
        'description',
        'attachment_path',
        'status',
        'assigned_to_id',
        'approved_at',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'attachment_path' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
