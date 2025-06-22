<?php

namespace App\Models;

use App\Traits\ClearsDashboardCache;
use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends Model
{
    use ClearsDashboardCache, HasFactory, LogsAudit;

    /** Requisition status values */
    public const STATUS_PENDING_HEAD = 'pending_head';

    public const STATUS_PENDING_PRESIDENT = 'pending_president';

    public const STATUS_PENDING_FINANCE = 'pending_finance';

    public const STATUS_APPROVED = 'approved';

    /**
     * All possible requisition statuses in workflow order.
     *
     * @var string[]
     */
    public const STATUSES = [
        self::STATUS_PENDING_HEAD,
        self::STATUS_PENDING_PRESIDENT,
        self::STATUS_PENDING_FINANCE,
        self::STATUS_APPROVED,
    ];

    protected $fillable = [
        'user_id',
        'ticket_id',
        'job_order_id',
        'department_id',
        'purpose',
        'attachment_path',
        'status',
        'remarks',
        'approved_by_id',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'attachment_path' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RequisitionItem::class);
    }
}
