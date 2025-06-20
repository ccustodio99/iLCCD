<?php

use Illuminate\Support\Facades\Schema;

it('shows message when license table is missing', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    $response = $this->get('/license');

    $response->assertStatus(200);
    $response->assertSee('License table missing');
});
