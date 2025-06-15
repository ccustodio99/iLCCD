<?php
use App\Models\Setting;
use App\Models\User;

it('allows admin to update institution settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $data = [
        'header_text' => "Header\nLine2",

        'footer_text' => "Footer\n© {year} Line2",

        'show_footer' => false,
    ];

    $response = $this->put('/settings/institution', $data);

    $response->assertRedirect('/settings/institution');
    $response->assertSessionHas('success', 'Institution settings updated');
    foreach ($data as $key => $value) {
        expect(Setting::get($key))->toBe($value);
    }
});
