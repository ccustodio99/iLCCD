<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\InventoryTransaction;
use App\Models\InventoryCategory;
use App\Traits\LogsAudit;

class InventoryItem extends Model
{
    /** @use HasFactory<\\Database\\Factories\\InventoryItemFactory> */
    use HasFactory, LogsAudit;

    /** Inventory item status values */
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_RETIRED = 'retired';

    /**
     * All valid inventory item statuses.
     *
     * @var string[]
     */
    public const STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_RESERVED,
        self::STATUS_MAINTENANCE,
        self::STATUS_RETIRED,
    ];

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'inventory_category_id',
        'department',
        'location',
        'supplier',
        'purchase_date',
        'quantity',
        'minimum_stock',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class)->latest();
    }

    public function inventoryCategory(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class);
    }
}
