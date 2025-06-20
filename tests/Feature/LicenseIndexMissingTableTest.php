<?php

use Illuminate\Support\Facades\Schema;

it('shows message when license table is missing', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }

    $response = $this->get('/license');

    $response->assertStatus(200);
    $response->assertSee('License table missing');
});
