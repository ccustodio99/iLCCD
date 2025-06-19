<?php

use App\Providers\AppServiceProvider;

it('falls back when default profile photo is missing', function () {
    config(['app.default_profile_photo' => '/missing-image.png']);
    app()->register(AppServiceProvider::class, true);

    expect(config('app.default_profile_photo'))
        ->toBe('/assets/images/default-avatar.png');
});
