<?php

use App\Models\User;
use App\Models\AuditTrail;
use Carbon\Carbon;

test('users can register and log in', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password1!',
        'password_confirmation' => 'Password1!',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();

    $this->post('/logout');
    $this->assertGuest();

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'Password1!',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
});


test('forgot password page is accessible', function () {
    $response = $this->get('/forgot-password');
    $response->assertStatus(200);
});

test('login route redirects to home', function () {
    $response = $this->get('/login');
    $response->assertRedirect('/');
});

test('user locked out after five failed login attempts', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    $user->refresh();

    expect($user->failed_login_attempts)->toBe(5);
    expect($user->lockout_until)->not->toBeNull();
    expect(AuditTrail::where('user_id', $user->id)->where('action', 'account_locked')->exists())->toBeTrue();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');
});

test('user can log in after lockout expires', function () {
    $user = User::factory()->create([
        'failed_login_attempts' => 5,
        'lockout_until' => now()->addMinutes(15),
    ]);

    Carbon::setTestNow(now()->addMinutes(16));

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    $user->refresh();

    expect($user->failed_login_attempts)->toBe(0);
    expect($user->lockout_until)->toBeNull();

    Carbon::setTestNow();
});

