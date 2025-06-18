<?php

use App\Models\ApprovalProcess;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\User;

it('updates job order when linked requisition is approved', function () {
    $procReq = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'IT',
    ]);
    $procReq->stages()->createMany([
        ['name' => Requisition::STATUS_PENDING_HEAD, 'position' => 1],
        ['name' => Requisition::STATUS_PENDING_PRESIDENT, 'position' => 2],
        ['name' => Requisition::STATUS_PENDING_FINANCE, 'position' => 3],
    ]);

    $procJob = ApprovalProcess::create([
        'module' => 'job_orders',
        'department' => 'IT',
    ]);
    $procJob->stages()->createMany([
        ['name' => JobOrder::STATUS_PENDING_HEAD, 'position' => 1],
        ['name' => JobOrder::STATUS_PENDING_PRESIDENT, 'position' => 2],
        ['name' => JobOrder::STATUS_PENDING_FINANCE, 'position' => 3],
    ]);
    $user = User::factory()->create(['department' => 'IT']);
    $jobOrder = JobOrder::factory()->for($user)->create([
        'status' => JobOrder::STATUS_PENDING_FINANCE,
    ]);
    $requisition = Requisition::factory()->for($user)->create([
        'job_order_id' => $jobOrder->id,
    ]);
    $item = $requisition->items->first();

    $this->actingAs($user);
    $this->put("/requisitions/{$requisition->id}", [
        'item' => [$item->item],
        'quantity' => [$item->quantity],
        'specification' => [$item->specification],
        'purpose' => $requisition->purpose,
        'status' => Requisition::STATUS_APPROVED,
    ])->assertRedirect('/requisitions');

    $jobOrder->refresh();
    expect($jobOrder->status)->toBe(JobOrder::STATUS_APPROVED);
    expect($jobOrder->approved_at)->not->toBeNull();
});
