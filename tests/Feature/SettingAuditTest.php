<?php
use App\Models\AuditTrail;
use App\Models\Setting;
use App\Models\User;

it('records audit trail when theme setting is updated', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Setting::set('color_primary', '#1B2660');
    $this->actingAs($admin);

    $this->put('/settings/theme', [
        'color_primary' => '#000000',
        'color_accent' => '#ffffff',
        'font_primary' => 'Poppins',
        'font_secondary' => 'Roboto',
        'home_heading' => 'Hello',
        'home_tagline' => 'Tagline here',
    ])->assertRedirect('/settings/theme');

    expect(
        AuditTrail::where('auditable_type', Setting::class)
            ->where('action', 'updated')
            ->exists()
    )->toBeTrue();
});
