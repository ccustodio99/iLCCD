<?php

use App\Models\JobOrder;
use App\Models\User;

it('enforces role restrictions during approval workflow', function () {
    $requester = User::factory()->create(['role' => 'staff', 'department' => 'Nursing']);
    $order = JobOrder::factory()->for($requester)->create([
        'status' => JobOrder::STATUS_PENDING_HEAD,
    ]);

    $head = User::factory()->create(['role' => 'head', 'department' => 'Nursing']);
    $president = User::factory()->create(['role' => 'head', 'department' => 'President Department']);
    $finance = User::factory()->create(['role' => 'head', 'department' => 'Finance Office']);

    $this->actingAs($president);
    $this->put("/job-orders/{$order->id}/approve")->assertForbidden();

    $this->actingAs($finance);
    $this->put("/job-orders/{$order->id}/approve")->assertForbidden();

    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/approve")->assertRedirect('/job-orders');

    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_PRESIDENT);

    // head cannot approve again
    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/approve")->assertForbidden();

    $this->actingAs($president);
    $this->put("/job-orders/{$order->id}/approve");
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_FINANCE);

    $this->actingAs($finance);
    $this->put("/job-orders/{$order->id}/approve");
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_APPROVED);
});
