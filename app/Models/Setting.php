<?php

namespace App\Models;

use App\Traits\LogsAudit;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use LogsAudit;
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
