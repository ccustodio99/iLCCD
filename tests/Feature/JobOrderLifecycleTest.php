<?php

use App\Models\JobOrder;
use App\Models\User;

it('allows assignee to start and finish job order', function () {
    $assignee = User::factory()->create();
    $order = JobOrder::factory()->create([
        'status' => JobOrder::STATUS_ASSIGNED,
        'assigned_to_id' => $assignee->id,
    ]);

    $this->actingAs($assignee);
    $this->put("/job-orders/{$order->id}/start", ['notes' => 'begin work'])
        ->assertRedirect('/job-orders/assigned');

    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_IN_PROGRESS);
    expect($order->started_at)->not->toBeNull();
    expect($order->start_notes)->toBe('begin work');

    $this->put("/job-orders/{$order->id}/finish", ['notes' => 'all done'])
        ->assertRedirect('/job-orders/assigned');

    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_COMPLETED);
    expect($order->completed_at)->not->toBeNull();
    expect($order->completion_notes)->toBe('all done');
});

it('prevents others from starting or finishing', function () {
    $assignee = User::factory()->create();
    $other = User::factory()->create();
    $order = JobOrder::factory()->create([
        'status' => JobOrder::STATUS_ASSIGNED,
        'assigned_to_id' => $assignee->id,
    ]);

    $this->actingAs($other);
    $this->put("/job-orders/{$order->id}/start")->assertForbidden();
    $this->put("/job-orders/{$order->id}/finish")->assertForbidden();
});
