<?php

use App\Http\Middleware\CheckLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class);

it('allows request when licenses table is missing', function () {
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
