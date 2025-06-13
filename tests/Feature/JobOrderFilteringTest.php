<?php

use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\User;

it('filters job orders by status', function () {
    $user = User::factory()->create();
    $type = JobOrderType::factory()->create(['name' => 'Repair']);
    JobOrder::factory()->for($user)->create([
        'job_type' => $type->name,
        'status' => JobOrder::STATUS_PENDING_HEAD,
        'description' => 'pending',
    ]);
    JobOrder::factory()->for($user)->create([
        'job_type' => $type->name,
        'status' => JobOrder::STATUS_APPROVED,
        'description' => 'approved',
    ]);

    $this->actingAs($user);
    $response = $this->get('/job-orders?status=' . JobOrder::STATUS_APPROVED);
    $response->assertSee('approved');
    $response->assertDontSee('pending');
});

it('filters job orders by type', function () {
    $user = User::factory()->create();
    $parentA = JobOrderType::factory()->create(['name' => 'Maintenance']);
    $childA = JobOrderType::factory()->create(['parent_id' => $parentA->id, 'name' => 'Install']);
    $parentB = JobOrderType::factory()->create(['name' => 'Repair']);
    $childB = JobOrderType::factory()->create(['parent_id' => $parentB->id, 'name' => 'Replace']);

    JobOrder::factory()->for($user)->create(['job_type' => $childA->name, 'description' => 'A']);
    JobOrder::factory()->for($user)->create(['job_type' => $childB->name, 'description' => 'B']);

    $this->actingAs($user);
    $response = $this->get('/job-orders?type_parent=' . $parentA->id);
    $response->assertSee('A');
    $response->assertDontSee('B');
});

it('filters job orders by assignee', function () {
    $requester = User::factory()->create();
    $assigneeA = User::factory()->create();
    $assigneeB = User::factory()->create();
    $type = JobOrderType::factory()->create();

    JobOrder::factory()->for($requester)->create([
        'job_type' => $type->name,
        'assigned_to_id' => $assigneeA->id,
        'description' => 'A',
        'status' => JobOrder::STATUS_ASSIGNED,
    ]);
    JobOrder::factory()->for($requester)->create([
        'job_type' => $type->name,
        'assigned_to_id' => $assigneeB->id,
        'description' => 'B',
        'status' => JobOrder::STATUS_ASSIGNED,
    ]);

    $this->actingAs($requester);
    $response = $this->get('/job-orders?assigned_to_id=' . $assigneeA->id);
    $response->assertSee('A');
    $response->assertDontSee('B');
});

it('searches job order descriptions', function () {
    $user = User::factory()->create();
    $type = JobOrderType::factory()->create();
    JobOrder::factory()->for($user)->create(['job_type' => $type->name, 'description' => 'Fix laptop screen']);
    JobOrder::factory()->for($user)->create(['job_type' => $type->name, 'description' => 'Replace bulb']);

    $this->actingAs($user);
    $response = $this->get('/job-orders?search=laptop');
    $response->assertSee('laptop');
    $response->assertDontSee('bulb');
});
