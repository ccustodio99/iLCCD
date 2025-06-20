<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Once;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('format_date')) {
    function format_date(\DateTimeInterface $date, ?string $format = null): string
    {
        $format = $format ?? setting('date_format', 'Y-m-d');

        return $date->format($format);
    }
}

if (! function_exists('license_table_exists')) {
    function license_table_exists(): bool
    {
        return once(fn () => Schema::hasTable('licenses'));
    }
}

if (! function_exists('license_table_cache_clear')) {
    function license_table_cache_clear(): void
    {
        Once::flush();
    }
}
