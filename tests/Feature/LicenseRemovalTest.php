<?php

use App\Models\License;
use Illuminate\Support\Carbon;

it('deletes the current license', function () {
    $license = License::create([
        'key' => 'test',
        'signature' => 'sig',
        'expires_at' => Carbon::now()->addDay(),
        'active' => true,
    ]);

    $response = $this->delete('/license');

    $response->assertSessionHas('status', 'License removed');
    expect(License::where('id', $license->id)->exists())->toBeFalse();
});
