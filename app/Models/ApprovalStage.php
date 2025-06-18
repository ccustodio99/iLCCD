<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_process_id',
        'name',
        'position',
        'assigned_user_id',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(ApprovalProcess::class, 'approval_process_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
