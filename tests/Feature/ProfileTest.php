<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('allows user to view profile page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/profile');
    $response->assertStatus(200);
    $response->assertSee('My Profile');
});

it('allows user to update profile', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->put('/profile', [
        'name' => 'Updated User',
        'email' => 'updated@example.com',
        'password' => 'Newpassword1!',
        'password_confirmation' => 'Newpassword1!',
    ]);
    $response->assertRedirect('/profile');
    $user->refresh();
    expect($user->name)->toBe('Updated User')
        ->and(Hash::check('Newpassword1!', $user->password))->toBeTrue();
});
