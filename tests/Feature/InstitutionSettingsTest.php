<?php
use App\Models\Setting;
use App\Models\User;

it('allows admin to update institution settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $data = [
        'institution_address' => 'New Address',
        'institution_phone' => '1234',
        'helpdesk_email' => 'new@example.com',
        'header_text' => 'Header',
        'footer_text' => 'Footer',
    ];

    $response = $this->put('/settings/institution', $data);

    $response->assertRedirect('/settings/institution');
    $response->assertSessionHas('success', 'Institution settings updated');
    foreach ($data as $key => $value) {
        expect(Setting::get($key))->toBe($value);
    }
});
