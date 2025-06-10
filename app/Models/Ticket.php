<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\KpiLog;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'description',
        'status',
        'due_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (Ticket $ticket) {
            KpiLog::create([
                'type' => 'ticket_created',
                'ticket_id' => $ticket->id,
            ]);
        });

        static::updated(function (Ticket $ticket) {
            if ($ticket->wasChanged('status') && $ticket->status === 'closed') {
                KpiLog::create([
                    'type' => 'ticket_closed',
                    'ticket_id' => $ticket->id,
                ]);
            }
        });
    }
}
