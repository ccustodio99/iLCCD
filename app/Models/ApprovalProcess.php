<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'department',
    ];

    public function stages(): HasMany
    {
        return $this->hasMany(ApprovalStage::class);
    }
}
