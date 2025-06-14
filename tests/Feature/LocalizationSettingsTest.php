<?php
use App\Models\Setting;
use App\Models\User;

it('allows admin to update localization settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/localization', [
        'timezone' => 'Asia/Manila',
        'date_format' => 'd/m/Y',
    ]);

    $response->assertRedirect('/settings/localization');
    expect(Setting::get('timezone'))->toBe('Asia/Manila');
    expect(Setting::get('date_format'))->toBe('d/m/Y');
});
