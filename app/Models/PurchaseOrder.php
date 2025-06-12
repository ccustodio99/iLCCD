<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsAudit;
use App\Traits\ClearsDashboardCache;

class PurchaseOrder extends Model
{
    use HasFactory, LogsAudit, ClearsDashboardCache;

    /** Purchase order status values */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * All possible purchase order statuses in workflow order.
     *
     * @var string[]
     */
    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_APPROVAL,
        self::STATUS_APPROVED,
        self::STATUS_ORDERED,
        self::STATUS_RECEIVED,
        self::STATUS_CLOSED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'user_id',
        'requisition_id',
        'inventory_item_id',
        'supplier',
        'item',
        'quantity',
        'status',
        'attachment_path',
        'ordered_at',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'ordered_at' => 'datetime',
            'received_at' => 'datetime',
            'attachment_path' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
