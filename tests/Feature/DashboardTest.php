<?php

use App\Models\User;

it('redirects to login if unauthenticated', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

it('shows dashboard for authenticated users', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Pending Tickets');
});
