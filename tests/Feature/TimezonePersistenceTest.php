<?php

use App\Models\Setting;
use App\Providers\TimezoneServiceProvider;

it('applies timezone setting on each request', function () {
    Setting::set('timezone', 'UTC');
    app()->register(TimezoneServiceProvider::class, true);

    $this->get('/');
    expect(config('app.timezone'))->toBe('UTC');
    expect(date_default_timezone_get())->toBe('UTC');

    Setting::set('timezone', 'Asia/Manila');
    app()->register(TimezoneServiceProvider::class, true);

    $this->get('/');
    expect(config('app.timezone'))->toBe('Asia/Manila');
    expect(date_default_timezone_get())->toBe('Asia/Manila');
});
