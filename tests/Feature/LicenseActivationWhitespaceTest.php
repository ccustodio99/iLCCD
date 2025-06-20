<?php

use App\Models\License;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

it('activates license with trailing whitespace', function () {
    $key = (string) Str::uuid();
    $expires = Carbon::now()->addDay();
    $signature = hash_hmac('sha256', "{$key}|{$expires->timestamp}", config('license.secret'));
    $encoded = base64_encode("{$key}|{$expires->timestamp}|{$signature}");

    $response = $this->post('/license/activate', [
        'license' => "  {$encoded} \n",
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('status', 'License activated');

    expect(License::where('key', $key)->where('active', true)->exists())->toBeTrue();
});
