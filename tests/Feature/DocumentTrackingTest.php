<?php

use App\Models\User;

it('shows document tracking pages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $routes = [
        '/document-tracking/incoming',
        '/document-tracking/outgoing',
        '/document-tracking/for-approval',
        '/document-tracking/tracking',
        '/document-tracking/reports',
    ];

    foreach ($routes as $route) {
        $this->get($route)->assertStatus(200);
    }
});
