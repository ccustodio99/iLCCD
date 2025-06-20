<?php

use App\Models\License;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

it('does not duplicate license key on renewal', function () {
    $key = (string) Str::uuid();
    $expires = Carbon::now()->addDay();
    $signature = hash_hmac('sha256', "{$key}|{$expires->timestamp}", config('license.secret'));
    $encoded = base64_encode("{$key}|{$expires->timestamp}|{$signature}");

    $this->post('/license/activate', ['license' => $encoded]);
    $this->post('/license/renew', ['license' => $encoded]);

    expect(License::where('key', $key)->count())->toBe(1);
});
