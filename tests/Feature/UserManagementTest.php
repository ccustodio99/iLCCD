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
        'role' => 'admin',
        'department' => 'ITRC',
        'is_active' => true,
    ]);
    $response->assertRedirect('/users');
    expect($user->fresh()->name)->toBe('Updated');
});

it('prevents login for inactive users', function () {
    $user = User::factory()->create(['is_active' => false]);
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});
