<?php

namespace App\Models;

use App\Traits\ClearsDashboardCache;
use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use ClearsDashboardCache, HasFactory, LogsAudit, SoftDeletes;

    /** Ticket status values */
    public const STATUS_PENDING_HEAD = 'pending_head';

    public const STATUS_OPEN = 'open';

    public const STATUS_ESCALATED = 'escalated';

    public const STATUS_CONVERTED = 'converted';

    public const STATUS_CLOSED = 'closed';

    /** All possible statuses */
    public const STATUSES = [
        self::STATUS_PENDING_HEAD,
        self::STATUS_OPEN,
        self::STATUS_ESCALATED,
        self::STATUS_CONVERTED,
        self::STATUS_CLOSED,
    ];

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
        'approved_by_id',
        'approved_at',
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
            'approved_at' => 'datetime',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_watchers')->withTimestamps();
    }

    public function archivedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_user_archives')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class)->withTrashed();
    }

    /**
     * Get the formatted subject in "Category - Issue Summary - ID" form.
     */
    public function getFormattedSubjectAttribute(): string
    {
        $category = $this->ticketCategory?->name ?? 'N/A';

        return $category.' - '.$this->subject.' - '.$this->id;
    }
}
