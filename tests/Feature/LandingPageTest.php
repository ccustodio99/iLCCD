<?php

test('landing page contains link to login', function () {
    $response = $this->get('/');
    $response->assertSee(route('login'));
});
