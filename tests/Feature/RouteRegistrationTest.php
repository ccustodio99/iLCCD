<?php

use App\Models\User;

it('resolves job orders index route for authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Route helper should not throw RouteNotFoundException
    $url = route('job-orders.index');

    $response = $this->get($url);

    $response->assertStatus(200);
});

