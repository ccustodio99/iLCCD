<?php

use App\Http\Controllers\LicenseController;
use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('stores a license when the decoded string begins with zero', function () {
    $controller = new LicenseController;
    $method = new ReflectionMethod($controller, 'storeLicense');
    $method->setAccessible(true);

    $key = '0'.Str::uuid();
    $expires = Carbon::now()->addDay();
    $signature = hash_hmac('sha256', "{$key}|{$expires->timestamp}", config('license.secret'));
    $encoded = base64_encode("{$key}|{$expires->timestamp}|{$signature}");

    $result = $method->invoke($controller, $encoded);

    expect($result)->toBeTrue();
    expect(License::where('key', $key)->where('active', true)->exists())->toBeTrue();
});
