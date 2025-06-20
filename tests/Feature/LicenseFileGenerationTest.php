<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\artisan;

it('saves generated license to dated file', function () {
    Storage::fake('local');
    Carbon::setTestNow('2025-01-01 00:00:00');

    artisan('license:generate --days=10')->assertExitCode(0);

    $filename = '20250101-20250111.lic';
    Storage::disk('local')->assertExists('licenses/'.$filename);
});
