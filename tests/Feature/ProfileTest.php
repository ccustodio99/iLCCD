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
        'contact_info' => '09171234567',
        'password' => 'Newpassword1!',
        'password_confirmation' => 'Newpassword1!',
    ]);
    $response->assertRedirect('/profile');
    $response->assertSessionHas('success', 'Profile updated successfully.');
    $user->refresh();
    expect($user->name)->toBe('Updated User')
        ->and($user->contact_info)->toBe('09171234567')
        ->and(Hash::check('Newpassword1!', $user->password))->toBeTrue();
});
