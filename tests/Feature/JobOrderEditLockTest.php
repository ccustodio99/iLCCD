<?php

use App\Models\ApprovalProcess;
use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\User;

it('locks requester editing after head approval until returned', function () {
    $process = ApprovalProcess::create([
        'module' => 'job_orders',
        'department' => 'Nursing',
    ]);
    $process->stages()->createMany([
        ['name' => JobOrder::STATUS_PENDING_HEAD, 'position' => 1],
        ['name' => JobOrder::STATUS_PENDING_PRESIDENT, 'position' => 2],
        ['name' => JobOrder::STATUS_PENDING_FINANCE, 'position' => 3],
    ]);
    $requester = User::factory()->create(['role' => 'staff', 'department' => 'Nursing']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'Nursing']);
    $parent = JobOrderType::factory()->create();
    $type = JobOrderType::factory()->create(['parent_id' => $parent->id]);

    $this->actingAs($requester);
    $order = JobOrder::factory()->for($requester)->create(['job_type' => $type->name]);

    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/approve");

    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_PRESIDENT);

    $this->actingAs($requester);
    $this->put("/job-orders/{$order->id}", [
        'type_parent' => $parent->id,
        'job_type' => $type->name,
        'description' => $order->description,
    ])->assertForbidden();

    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/return", ['remarks' => 'revise']);
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_HEAD);

    $this->actingAs($requester);
    $this->put("/job-orders/{$order->id}", [
        'type_parent' => $parent->id,
        'job_type' => $type->name,
        'description' => $order->description,
    ])->assertRedirect('/job-orders');
});
