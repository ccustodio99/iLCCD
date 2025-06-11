<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsAudit;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, LogsAudit;

    const DELETED_AT = 'archived_at';

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'description',
        'status',
        'due_at',
        'escalated_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobOrder()
    {
        return $this->hasOne(JobOrder::class);
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }
}
