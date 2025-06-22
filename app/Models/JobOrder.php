<?php

namespace App\Models;

use App\Traits\ClearsDashboardCache;
use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobOrder extends Model
{
    use ClearsDashboardCache, HasFactory, LogsAudit;

    /** Job order status values */
    public const STATUS_PENDING_HEAD = 'pending_head';

    public const STATUS_PENDING_PRESIDENT = 'pending_president';

    public const STATUS_PENDING_FINANCE = 'pending_finance';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_ASSIGNED = 'assigned';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CLOSED = 'closed';

    /** All valid status values */
    public const STATUSES = [
        self::STATUS_PENDING_HEAD,
        self::STATUS_PENDING_PRESIDENT,
        self::STATUS_PENDING_FINANCE,
        self::STATUS_APPROVED,
        self::STATUS_ASSIGNED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_COMPLETED,
        self::STATUS_CLOSED,
    ];

    protected $fillable = [
        'user_id',
        'ticket_id',
        'job_order_type_id',
        'description',
        'attachment_path',
        'status',
        'assigned_to_id',
        'approved_at',
        'started_at',
        'start_notes',
        'completed_at',
        'completion_notes',
        'closed_at',
    ];

    protected $appends = ['job_type'];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
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

    public function jobOrderType(): BelongsTo
    {
        return $this->belongsTo(JobOrderType::class);
    }

    public function getJobTypeAttribute(): ?string
    {
        return $this->jobOrderType->name ?? null;
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
