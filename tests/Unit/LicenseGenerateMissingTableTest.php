<?php

use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\artisan;

it('fails when license table is missing', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    artisan('license:generate --days=1')
        ->expectsOutput('Run migrations first')
        ->assertExitCode(1);
});
