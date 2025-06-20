<?php

use App\Models\License;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

it('saves generated license to dated file and outputs details', function () {
    Storage::fake('local');
    Carbon::setTestNow('2025-01-01 00:00:00');

    $exitCode = Artisan::call('license:generate --days=10');
    expect($exitCode)->toBe(0);

    $filename = '20250101-20250111.lic';
    Storage::disk('local')->assertExists('licenses/'.$filename);

    $license = License::latest()->first();
    $expectedString = base64_encode($license->key.'|'.$license->expires_at->timestamp.'|'.$license->signature);
    $expectedPath = Storage::disk('local')->path('licenses/'.$filename);
    $output = Artisan::output();

    expect($output)->toContain($expectedPath)
        ->toContain($expectedString);
});
