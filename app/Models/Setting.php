<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use LogsAudit;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $cacheKey = "setting:{$key}";

        if (Cache::has($cacheKey)) {
            $value = Cache::get($cacheKey);
        } else {
            $value = static::query()->where('key', $key)->value('value');
            if ($value !== null) {
                Cache::forever($cacheKey, $value);
            } else {
                return $default;
            }
        }

        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($bool !== null) {
            return $bool;
        }

        if (is_numeric($value) && ctype_digit((string) $value)) {
            return (int) $value;
        }

        return $value;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");
    }
}
