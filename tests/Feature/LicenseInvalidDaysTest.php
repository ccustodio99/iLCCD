<?php

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

it('fails when days option is less than one', function () {
    Storage::fake('local');

    artisan('license:generate --days=0')
        ->expectsOutput('The number of days must be at least 1.')
        ->assertExitCode(1);
});
