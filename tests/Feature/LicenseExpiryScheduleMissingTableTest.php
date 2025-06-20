<?php

use Illuminate\Support\Facades\Schema;

use function Pest\Laravel\artisan;

it('runs schedule without error when license table missing', function () {
    if (Schema::hasTable('licenses')) {
        Schema::drop('licenses');
    }

    artisan('schedule:run')->assertExitCode(0);
});
