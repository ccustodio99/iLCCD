<?php

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

it('fails when no positive duration is provided', function () {
    Storage::fake('local');

    artisan('license:generate --days=0')
        ->expectsOutput('Specify a positive value for days, months, or years.')
        ->assertExitCode(1);
});
