<?php

use App\Models\JobOrder;
use App\Models\User;

it('enforces role restrictions during approval workflow', function () {
    $order = JobOrder::factory()->create([
        'status' => JobOrder::STATUS_PENDING_HEAD,
    ]);

    $president = User::factory()->create(['role' => 'president']);
    $finance = User::factory()->create(['role' => 'finance']);
    $head = User::factory()->create(['role' => 'head']);

    $this->actingAs($president);
    $this->put("/job-orders/{$order->id}/approve")->assertForbidden();

    $this->actingAs($finance);
    $this->put("/job-orders/{$order->id}/approve")->assertForbidden();

    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/approve")->assertRedirect('/job-orders/approvals');

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

