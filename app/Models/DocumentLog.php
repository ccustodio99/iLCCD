<?php

namespace App\Models;

use App\Traits\ClearsDashboardCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentLog extends Model
{
    use ClearsDashboardCache, HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'user_id',
        'action',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
