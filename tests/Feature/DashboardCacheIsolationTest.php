<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Cache;

it('preserves non-dashboard cache entries when clearing dashboard cache', function () {
    Cache::put('dashboard:test', 'foo');
    Cache::put('other:test', 'bar');

    Ticket::factory()->create();

    expect(Cache::has('dashboard:test'))->toBeFalse();
    expect(Cache::get('other:test'))->toBe('bar');
});
