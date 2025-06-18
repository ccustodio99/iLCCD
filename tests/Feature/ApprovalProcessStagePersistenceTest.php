<?php
use App\Models\ApprovalProcess;
use App\Models\User;

it('keeps stages after updating process', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $process = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'IT',
    ]);

    $process->stages()->createMany([
        ['name' => 'Initial', 'position' => 1],
        ['name' => 'Final', 'position' => 2],
    ]);

    $response = $this->put("/settings/approval-processes/{$process->id}", [
        'module' => 'requisitions',
        'department' => 'Finance',
    ]);

    $response->assertRedirect("/settings/approval-processes/{$process->id}/edit");
    expect($process->fresh()->stages()->count())->toBe(2);
});
