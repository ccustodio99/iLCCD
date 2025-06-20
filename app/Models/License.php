<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'signature',
        'expires_at',
        'active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'active' => 'boolean',
    ];

    public static function current(): ?self
    {
        return static::where('active', true)->first();
    }

    public function isValid(): bool
    {
        return $this->active && $this->expires_at?->isFuture();
    }
}
