<?php

use App\Models\JobOrder;
use App\Models\User;
use App\Models\AuditTrail;
use App\Models\JobOrderType;

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

it('logs start and finish actions', function () {
    $assignee = User::factory()->create();
    $order = JobOrder::factory()->create([
        'status' => JobOrder::STATUS_ASSIGNED,
        'assigned_to_id' => $assignee->id,
    ]);

    $this->actingAs($assignee);
    $this->put("/job-orders/{$order->id}/start");

    expect(AuditTrail::where('auditable_id', $order->id)
        ->where('auditable_type', JobOrder::class)
        ->where('action', 'updated')->exists())->toBeTrue();

    $this->put("/job-orders/{$order->id}/finish");

    expect(AuditTrail::where('auditable_id', $order->id)
        ->where('auditable_type', JobOrder::class)
        ->where('action', 'updated')->count())->toBe(2);
});

it('shows assigned orders to assignee only', function () {
    $assignee = User::factory()->create();
    $other = User::factory()->create();
    $install = JobOrderType::factory()->create(['name' => 'Install']);
    $repair = JobOrderType::factory()->create(['name' => 'Repair']);
    $order = JobOrder::factory()->create([
        'job_type' => $install->name,
        'status' => JobOrder::STATUS_ASSIGNED,
        'assigned_to_id' => $assignee->id,
    ]);
    $otherOrder = JobOrder::factory()->create([
        'job_type' => $repair->name,
        'status' => JobOrder::STATUS_ASSIGNED,
        'assigned_to_id' => $other->id,
    ]);

    $this->actingAs($assignee);
    $response = $this->get('/job-orders/assigned');
    $response->assertSee('Install');
    $response->assertDontSee('Repair');
});

it('allows requester to close completed job order', function () {
    $requester = User::factory()->create();
    $order = JobOrder::factory()->for($requester)->create([
        'status' => JobOrder::STATUS_COMPLETED,
    ]);

    $this->actingAs($requester);
    $this->put("/job-orders/{$order->id}/close")->assertRedirect('/job-orders');

    $order->refresh();
    expect($order->status)->toBe(JobOrder::STATUS_CLOSED);
    expect($order->closed_at)->not->toBeNull();
});

it('prevents others from closing job order', function () {
    $requester = User::factory()->create();
    $other = User::factory()->create();
    $order = JobOrder::factory()->for($requester)->create([
        'status' => JobOrder::STATUS_COMPLETED,
    ]);

    $this->actingAs($other);
    $this->put("/job-orders/{$order->id}/close")->assertForbidden();
});

it('rejects starting when not assigned', function () {
    $assignee = User::factory()->create();
    $order = JobOrder::factory()->create([
        'status' => JobOrder::STATUS_IN_PROGRESS,
        'assigned_to_id' => $assignee->id,
    ]);

    $this->actingAs($assignee);
    $this->put("/job-orders/{$order->id}/start")->assertForbidden();
});

it('rejects finishing when not in progress', function () {
    $assignee = User::factory()->create();
    $order = JobOrder::factory()->create([
        'status' => JobOrder::STATUS_ASSIGNED,
        'assigned_to_id' => $assignee->id,
    ]);

    $this->actingAs($assignee);
    $this->put("/job-orders/{$order->id}/finish")->assertForbidden();
});


