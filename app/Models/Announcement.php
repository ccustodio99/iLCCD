<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ClearsDashboardCache;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, ClearsDashboardCache;

    const DELETED_AT = 'archived_at';

    protected $fillable = [
        'title',
        'content',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }
}
