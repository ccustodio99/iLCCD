<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\TicketComment;
use App\Traits\LogsAudit;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, LogsAudit;

    const DELETED_AT = 'archived_at';

    protected $fillable = [
        'user_id',
        'assigned_to_id',
        'category',
        'subject',
        'description',
        'attachment_path',
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

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_watchers')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Get the formatted subject in "Category - Issue Summary - ID" form.
     */
    public function getFormattedSubjectAttribute(): string
    {
        return $this->category.' - '.$this->subject.' - '.$this->id;
    }
}
