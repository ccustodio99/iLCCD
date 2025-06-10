<?php

test('users can register and log in', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();

    $this->post('/logout');
    $this->assertGuest();

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticated();
});


test('forgot password page is accessible', function () {
    $response = $this->get('/forgot-password');
    $response->assertStatus(200);
});
