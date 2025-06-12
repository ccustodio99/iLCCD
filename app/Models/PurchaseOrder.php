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
