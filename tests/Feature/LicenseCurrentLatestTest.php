<?php

use App\Models\License;
use Illuminate\Support\Carbon;

it('uses the latest active license as current', function () {
    Carbon::setTestNow('2025-01-01 00:00:00');

    $old = License::create([
        'key' => 'old',
        'signature' => 'sig1',
        'expires_at' => now()->addDay(),
        'active' => true,
        'created_at' => now()->subDay(),
    ]);

    $new = License::create([
        'key' => 'new',
        'signature' => 'sig2',
        'expires_at' => now()->addDay(),
        'active' => true,
        'created_at' => now(),
    ]);

    expect(License::current()->id)->toBe($new->id);
});
