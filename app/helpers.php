<?php
use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null) {
        return Setting::get($key, $default);
    }
}

if (! function_exists('format_date')) {
    function format_date(\DateTimeInterface $date, ?string $format = null): string {
        $format = $format ?? setting('date_format', 'Y-m-d');

        return $date->format($format);
    }
}
