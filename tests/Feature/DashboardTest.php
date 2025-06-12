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

it('returns dashboard data as json', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->getJson('/dashboard/data');
    $response->assertStatus(200);
    $response->assertJsonStructure(['tickets','jobOrders','requisitions']);
});
