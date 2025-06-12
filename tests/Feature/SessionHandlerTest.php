<?php

use Illuminate\Support\Facades\Session;

it('expires sessions after configured lifetime', function () {
    $handler = Session::getHandler();
    $ref = new ReflectionClass($handler);
    $method = $ref->getMethod('expired');
    $method->setAccessible(true);

    $session = (object) ['last_activity' => now()->subMinutes(16)->getTimestamp()];
    expect($method->invoke($handler, $session))->toBeTrue();

    $session->last_activity = now()->subMinutes(14)->getTimestamp();
    expect($method->invoke($handler, $session))->toBeFalse();
});
