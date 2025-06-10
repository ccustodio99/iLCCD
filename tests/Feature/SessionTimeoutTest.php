<?php

test('session timeout is configured', function () {
    expect(config('session.lifetime'))->toBe(30);
});
