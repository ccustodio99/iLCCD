<?php
use App\Models\JobOrderType;
use App\Models\User;

it('allows admin to create job order type', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/job-order-types', [
        'name' => 'Repair',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/job-order-types');
    expect(JobOrderType::where('name', 'Repair')->exists())->toBeTrue();
});

it('allows admin to update job order type', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $type = JobOrderType::factory()->create(['name' => 'Repair']);
    $this->actingAs($admin);

    $response = $this->put("/settings/job-order-types/{$type->id}", [
        'name' => 'Install',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/job-order-types');
    expect($type->fresh()->name)->toBe('Install');
});

it('allows admin to disable job order type', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $type = JobOrderType::factory()->create(['is_active' => true]);
    $this->actingAs($admin);

    $response = $this->put("/settings/job-order-types/{$type->id}/disable");

    $response->assertRedirect('/settings/job-order-types');
    expect($type->fresh()->is_active)->toBeFalse();
});

it('allows admin to delete job order type', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $type = JobOrderType::factory()->create();
    $this->actingAs($admin);

    $response = $this->delete("/settings/job-order-types/{$type->id}");

    $response->assertRedirect('/settings/job-order-types');
    expect(JobOrderType::where('id', $type->id)->exists())->toBeFalse();
});
