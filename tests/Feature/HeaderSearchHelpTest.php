<?php

use App\Models\User;

it('shows new header elements on authenticated pages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/dashboard');
    $response->assertDontSee('breadcrumb-toggle');
    $response->assertSee('global-search', false);
    $response->assertSee(route('help'), false);
    $response->assertSee('notificationsModal', false);
});

it('search and help routes respond successfully', function () {
    $this->get(route('search.index'))->assertStatus(200);
    $this->get(route('help'))->assertStatus(200);
});
