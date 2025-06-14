<?php

use App\Models\User;

it('returns placeholder url for profile photos', function () {
    $user = User::factory()->create();
    expect($user->profile_photo_url)->toBe('https://via.placeholder.com/150');
});
