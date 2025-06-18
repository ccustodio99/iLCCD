<?php

use App\Models\ApprovalProcess;
use App\Models\User;

it('allows admin to create approval process', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/approval-processes', [
        'module' => 'requisitions',
        'department' => 'ITRC',
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
    $this->actingAs($admin);

    $response = $this->delete("/settings/approval-processes/{$process->id}");

    $response->assertRedirect('/settings/approval-processes');
    expect(ApprovalProcess::where('id', $process->id)->exists())->toBeFalse();
});
