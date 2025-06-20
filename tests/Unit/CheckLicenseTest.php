<?php

use App\Http\Middleware\CheckLicense;
use App\Models\License;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows request when licenses table is missing', function () {
    $reflection = new ReflectionProperty(CheckLicense::class, 'hasTable');
    $reflection->setAccessible(true);
    $reflection->setValue(null);

    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }

    $middleware = new CheckLicense;
    $request = Request::create('/dummy', 'GET');
    $handled = false;

    $response = $middleware->handle($request, function () use (&$handled) {
        $handled = true;

        return response('ok');
    });

    expect($handled)->toBeTrue();
    expect($response->getContent())->toBe('ok');
});

it('allows request when a valid license exists', function () {
    $reflection = new ReflectionProperty(CheckLicense::class, 'hasTable');
    $reflection->setAccessible(true);
    $reflection->setValue(null);

    License::create([
        'key' => 'test',
        'signature' => 'sig',
        'expires_at' => now()->addDay(),
        'active' => true,
    ]);

    $middleware = new CheckLicense;
    $request = Request::create('/dummy', 'GET');
    $handled = false;

    $response = $middleware->handle($request, function () use (&$handled) {
        $handled = true;

        return response('ok');
    });

    expect($handled)->toBeTrue();
    expect($response->getContent())->toBe('ok');
});

it('redirects when the license is expired', function () {
    $reflection = new ReflectionProperty(CheckLicense::class, 'hasTable');
    $reflection->setAccessible(true);
    $reflection->setValue(null);

    Carbon::setTestNow('2025-01-01 00:00:00');

    License::create([
        'key' => 'expired',
        'signature' => 'sig',
        'expires_at' => now()->subDay(),
        'active' => true,
    ]);

    $middleware = new CheckLicense;
    $request = Request::create('/dummy', 'GET');

    $response = $middleware->handle($request, fn () => response('ok'));

    expect($response->getStatusCode())->toBe(302);
    expect($response->headers->get('Location'))->toBe(route('license.index'));
});
