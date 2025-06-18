<?php

use App\Models\ApprovalProcess;
use App\Models\User;

it('allows admin to view approval process details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $process = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'ITRC',
    ]);
    $process->stages()->create([
        'name' => 'pending_head',
        'position' => 1,
    ]);

    $this->actingAs($admin);

    $response = $this->get("/settings/approval-processes/{$process->id}");

    $response->assertOk();
    $response->assertSee('pending_head');
});
