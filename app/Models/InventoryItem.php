<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsAudit;

class InventoryItem extends Model
{
    /** @use HasFactory<\\Database\\Factories\\InventoryItemFactory> */
    use HasFactory, LogsAudit;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
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
}
