<?php

use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\artisan;

it('runs schedule without error when license table missing', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }
    license_table_cache_clear();

    artisan('schedule:run')->assertExitCode(0);
});
