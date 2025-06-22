<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, LogsAudit, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'document_category_id',
        'department_id',
        'current_version',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DocumentLog::class);
    }

    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class)->withTrashed();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function delete()
    {
        $this->versions()->get()->each(function (DocumentVersion $version) {
            $version->delete();
        });

        $this->logs()->get()->each(function (DocumentLog $log) {
            $log->delete();
        });

        return parent::delete();
    }

    public function restore()
    {
        $this->versions()->withTrashed()->get()->each(function (DocumentVersion $version) {
            $version->restore();
        });

        $this->logs()->withTrashed()->get()->each(function (DocumentLog $log) {
            $log->restore();
        });

        return parent::restore();
    }
}
