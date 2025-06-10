<?php

use App\Models\User;

it('allows admin to view user list', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);
    $response = $this->get('/users');
    $response->assertStatus(200);
});

it('allows admin to edit a user', function () {
    $admin = User::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($admin);
    $response = $this->put("/users/{$user->id}", [
        'name' => 'Updated',
        'email' => 'updated@example.com',
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);
    $response->assertRedirect('/users');
    expect($user->fresh()->name)->toBe('Updated');
});
