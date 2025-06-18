<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class TimezoneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $timezone = Setting::get('timezone', config('app.timezone'));

            if ($timezone) {
                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);
            }
        }
    }
}
