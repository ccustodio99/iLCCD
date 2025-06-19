<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCategory extends Model
{
    use HasFactory, LogsAudit, SoftDeletes;

    /**
     * Default categories created during initial seeding.
     */
    public const DEFAULT_CATEGORIES = [
        'Policies & Procedures',
        'Forms & Templates',
        'Course Materials',
        'Student Records',
        'Financial & Accounting',
        'Research & Publications',
        'Marketing & Communications',
        'Meeting Minutes & Reports',
        'Archives & Historical',
        'Miscellaneous',
    ];

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
