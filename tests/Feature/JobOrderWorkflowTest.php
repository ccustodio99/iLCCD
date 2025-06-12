<?php

use App\Models\JobOrder;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobOrderStatusNotification;

it('routes job order through approval and assignment workflow', function () {
    Notification::fake();

    $requester = User::factory()->create(['role' => 'staff']);
    $head = User::factory()->create(['role' => 'head']);
    $president = User::factory()->create(['role' => 'president']);
    $finance = User::factory()->create(['role' => 'finance']);
    $assigner = User::factory()->create(['role' => 'itrc']);
    $technician = User::factory()->create(['role' => 'staff']);

    // requester creates job order
    $this->actingAs($requester);
    $this->post('/job-orders', [
        'job_type' => 'Repair',
        'description' => 'Fix PC',
    ])->assertRedirect('/job-orders');
    $order = JobOrder::first();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_HEAD);

    // head approval
    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/approve")->assertRedirect('/job-orders/approvals');
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_PRESIDENT);

    // president approval
    $this->actingAs($president);
    $this->put("/job-orders/{$order->id}/approve");
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_FINANCE);

    // finance approval
    $this->actingAs($finance);
    $this->put("/job-orders/{$order->id}/approve");
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_APPROVED);
    expect($order->approved_at)->not->toBeNull();

    // assignment
    $this->actingAs($assigner);
    $this->put("/job-orders/{$order->id}/assign", [
        'assigned_to_id' => $technician->id,
    ])->assertRedirect('/job-orders/assignments');
    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_ASSIGNED);
    expect($order->assigned_to_id)->toBe($technician->id);

    Notification::assertSentTimes(JobOrderStatusNotification::class, 7);
});

