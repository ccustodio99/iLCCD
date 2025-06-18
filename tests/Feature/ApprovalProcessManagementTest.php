<?php

use App\Models\ApprovalProcess;
use App\Models\User;

it('allows admin to create approval process', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/approval-processes', [
        'module' => 'requisitions',
        'department' => 'ITRC',
        'stages' => [
            ['name' => 'Initial', 'position' => 1],
        ],
    ]);

    $response->assertRedirect('/settings/approval-processes');
    expect(ApprovalProcess::where('department', 'ITRC')->where('module', 'requisitions')->exists())->toBeTrue();
});

it('allows admin to update approval process', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $process = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'ITRC',
    ]);
    $process->stages()->create(['name' => 'Initial', 'position' => 1]);
    $this->actingAs($admin);

    $response = $this->put("/settings/approval-processes/{$process->id}", [
        'module' => 'requisitions',
        'department' => 'Nursing',
    ]);

    $response->assertRedirect("/settings/approval-processes/{$process->id}/edit");
    expect($process->fresh()->department)->toBe('Nursing');
});

it('allows admin to delete approval process', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $process = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'ITRC',
    ]);
    $process->stages()->create(['name' => 'Initial', 'position' => 1]);
    $this->actingAs($admin);

    $response = $this->delete("/settings/approval-processes/{$process->id}");

    $response->assertRedirect('/settings/approval-processes');
    expect(ApprovalProcess::where('id', $process->id)->exists())->toBeFalse();
});

it('rejects creating process without stages', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/approval-processes', [
        'module' => 'requisitions',
        'department' => 'IT',
    ]);

    $response->assertSessionHasErrors('stages');
});

it('rejects updating process without stages', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $process = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'IT',
    ]);
    $this->actingAs($admin);

    $response = $this->put("/settings/approval-processes/{$process->id}", [
        'module' => 'requisitions',
        'department' => 'IT',
    ]);

    $response->assertSessionHasErrors('stages');
});
