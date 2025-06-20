<?php
use App\Models\Setting;
use App\Models\User;

it('allows admin to update SLA settings', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->put('/settings/sla', [
        'sla_enabled' => '1',
        'sla_interval' => 5,
    ]);

    $response->assertRedirect('/settings/sla');
    $response->assertSessionHas('success', 'Escalation settings updated');
    expect(Setting::get('sla_enabled'))->toBeTrue();
    expect(Setting::get('sla_interval'))->toBe(5);
});
