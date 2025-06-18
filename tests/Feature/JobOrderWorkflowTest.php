<?php

use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\User;
use App\Notifications\JobOrderStatusNotification;
use Illuminate\Support\Facades\Notification;

it('routes job order through approval and assignment workflow', function () {
    Notification::fake();
    \App\Models\Setting::set('notify_job_order_status', true);
    \App\Models\Setting::set('template_job_order_status', '{{ message }}');

    $requester = User::factory()->create(['role' => 'staff', 'department' => 'Nursing']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'Nursing']);
    $president = User::factory()->create(['role' => 'head', 'department' => 'President Department']);
    $finance = User::factory()->create(['role' => 'head', 'department' => 'Finance Office']);
    $assigner = User::factory()->create(['role' => 'itrc']);
    $technician = User::factory()->create(['role' => 'staff']);
    $parent = JobOrderType::factory()->create(['name' => 'Maintenance']);
    $type = JobOrderType::factory()->create(['name' => 'Repair', 'parent_id' => $parent->id]);

    // requester creates job order
    $this->actingAs($requester);
    $this->post('/job-orders', [
        'type_parent' => $parent->id,
        'job_type' => $type->name,
        'description' => 'Fix PC',
    ])->assertRedirect('/job-orders');
    $order = JobOrder::first();
    expect($order->status)->toBe(JobOrder::STATUS_PENDING_HEAD);

    // head approval
    $this->actingAs($head);
    $this->put("/job-orders/{$order->id}/approve")->assertRedirect('/job-orders');
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
