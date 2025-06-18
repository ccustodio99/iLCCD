<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

it('caches setting values after first retrieval', function () {
    Setting::set('foo', 'bar');

    DB::enableQueryLog();
    $first = Setting::get('foo');
    $queriesAfterFirst = count(DB::getQueryLog());

    DB::flushQueryLog();
    $second = Setting::get('foo');
    $queriesAfterSecond = count(DB::getQueryLog());

    expect($first)->toBe('bar');
    expect($second)->toBe('bar');
    expect($queriesAfterFirst)->toBeGreaterThan(0);
    expect($queriesAfterSecond)->toBe(0);
});

it('invalidates cache when setting value is updated', function () {
    Setting::set('foo', 'bar');
    Setting::get('foo');

    Setting::set('foo', 'baz');

    DB::enableQueryLog();
    $valueAfterUpdate = Setting::get('foo');
    $queriesAfterUpdate = count(DB::getQueryLog());

    DB::flushQueryLog();
    $cachedValue = Setting::get('foo');
    $queriesAfterCached = count(DB::getQueryLog());

    expect($valueAfterUpdate)->toBe('baz');
    expect($queriesAfterUpdate)->toBeGreaterThan(0);
    expect($cachedValue)->toBe('baz');
    expect($queriesAfterCached)->toBe(0);
});
