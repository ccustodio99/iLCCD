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
        $value = static::query()->where('key', $key)->value('value');
        if ($value === null) {
            return $default;
        }
        if ($value === '1' || $value === 1) {
            return true;
        }
        if ($value === '0' || $value === 0) {
            return false;
        }
        return $value;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
