<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;

it('redirects back when activating without license table', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    $response = $this->post('/license/activate', ['license' => 'foo']);

    $response->assertRedirect();
    $response->assertSessionHasErrors('license');
});

it('redirects back when renewing without license table', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    $response = $this->post('/license/renew', ['license' => 'foo']);

    $response->assertRedirect();
    $response->assertSessionHasErrors('license');
});

it('redirects back when destroying without license table', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    $response = $this->delete('/license');

    $response->assertRedirect();
    $response->assertSessionHasErrors('license');
});

it('redirects back when managing without license table', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->get('/admin/licenses');

    $response->assertRedirect();
    $response->assertSessionHasErrors('license');
});
