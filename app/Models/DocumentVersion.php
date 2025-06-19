<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentVersion extends Model
{
    use HasFactory, LogsAudit, SoftDeletes;

    protected $fillable = [
        'document_id',
        'version',
        'path',
        'uploaded_by',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
