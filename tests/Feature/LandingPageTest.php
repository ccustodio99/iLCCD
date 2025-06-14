<?php

test('landing page displays login form', function () {
    $response = $this->get('/');
    $response->assertSee('Login');
    $response->assertSee('action="' . route('login') . '"', false);
});
