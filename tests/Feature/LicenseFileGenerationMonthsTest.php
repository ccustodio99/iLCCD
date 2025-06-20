<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\artisan;

it('saves license file using months option', function () {
    Storage::fake('local');
    Carbon::setTestNow('2025-01-01 00:00:00');

    artisan('license:generate --days=0 --months=1')->assertExitCode(0);

    $filename = '20250101-20250201.lic';
    Storage::disk('local')->assertExists('licenses/'.$filename);
});
