<?php

use App\Models\License;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

it('prevents multiple active licenses when activations run concurrently', function () {
    Carbon::setTestNow('2025-01-01 00:00:00');

    $expires = Carbon::now()->addDay();
    $key1 = (string) Str::uuid();
    $sig1 = hash_hmac('sha256', "{$key1}|{$expires->timestamp}", config('license.secret'));
    $key2 = (string) Str::uuid();
    $sig2 = hash_hmac('sha256', "{$key2}|{$expires->timestamp}", config('license.secret'));

    $pid = pcntl_fork();
    if ($pid === 0) {
        DB::transaction(function () use ($key1, $sig1, $expires) {
            License::where('active', true)->lockForUpdate()->get();
            License::query()->update(['active' => false]);
            License::create([
                'key' => $key1,
                'signature' => $sig1,
                'expires_at' => $expires,
                'active' => true,
            ]);
            usleep(100000);
        });
        exit(0);
    }

    usleep(50000);

    try {
        DB::transaction(function () use ($key2, $sig2, $expires) {
            License::where('active', true)->lockForUpdate()->get();
            License::query()->update(['active' => false]);
            License::create([
                'key' => $key2,
                'signature' => $sig2,
                'expires_at' => $expires,
                'active' => true,
            ]);
        });
    } catch (Throwable $e) {
        // ignore
    }

    pcntl_wait($status);

    expect(License::where('active', true)->count())->toBe(1);
});
