<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\TicketComment;
use App\Traits\LogsAudit;
use App\Traits\ClearsDashboardCache;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, LogsAudit, ClearsDashboardCache;

    const DELETED_AT = 'archived_at';

    protected $fillable = [
        'user_id',
        'assigned_to_id',
        'ticket_category_id',
        'subject',
        'description',
        'attachment_path',
        'status',
        'due_at',
        'escalated_at',
        'resolved_at',
        'edit_request_reason',
        'edit_requested_at',
        'edit_requested_by',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
            'archived_at' => 'datetime',
            'edit_requested_at' => 'datetime',
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

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * Get the formatted subject in "Category - Issue Summary - ID" form.
     */
    public function getFormattedSubjectAttribute(): string
    {
        return $this->ticketCategory->name.' - '.$this->subject.' - '.$this->id;
    }
}
