<?php
use App\Models\Setting;
use App\Models\User;

it('allows admin to update theme settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $data = [
        'color_primary' => '#000000',
        'color_accent' => '#ffffff',
        'font_primary' => 'Poppins',
        'font_secondary' => 'Roboto',
        'home_heading' => 'Hello',
        'home_tagline' => 'Tagline here',
    ];

    $response = $this->put('/settings/theme', $data);

    $response->assertRedirect('/settings/theme');
    $response->assertSessionHas('success', 'Theme updated');
    foreach ($data as $key => $value) {
        expect(Setting::get($key))->toBe($value);
    }
});
