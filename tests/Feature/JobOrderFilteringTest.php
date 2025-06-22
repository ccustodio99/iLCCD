<?php

use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\User;

it('filters job orders by status', function () {
    $user = User::factory()->create();
    $type = JobOrderType::factory()->create(['name' => 'Repair']);
    JobOrder::factory()->for($user)->create([
        'job_order_type_id' => $type->id,
        'status' => JobOrder::STATUS_PENDING_HEAD,
        'description' => 'pending',
    ]);
    JobOrder::factory()->for($user)->create([
        'job_order_type_id' => $type->id,
        'status' => JobOrder::STATUS_APPROVED,
        'description' => 'approved',
    ]);

    $this->actingAs($user);
    $response = $this->get('/job-orders?status='.JobOrder::STATUS_APPROVED);
    $response->assertSee('approved');
    $response->assertDontSee('<td>pending</td>', false);
});

it('filters job orders by type', function () {
    $user = User::factory()->create();
    $parentA = JobOrderType::factory()->create(['name' => 'Maintenance']);
    $childA = JobOrderType::factory()->create(['parent_id' => $parentA->id, 'name' => 'Install']);
    $parentB = JobOrderType::factory()->create(['name' => 'Repair']);
    $childB = JobOrderType::factory()->create(['parent_id' => $parentB->id, 'name' => 'Replace']);

    JobOrder::factory()->for($user)->create(['job_order_type_id' => $childA->id, 'description' => 'A']);
    JobOrder::factory()->for($user)->create(['job_order_type_id' => $childB->id, 'description' => 'B']);

    $this->actingAs($user);
    $response = $this->get('/job-orders?type_parent='.$parentA->id);
    $response->assertSee('A');
    $response->assertDontSee('<td>B</td>', false);
});

it('filters job orders by parent when no children exist', function () {
    $user = User::factory()->create();
    $parentA = JobOrderType::factory()->create(['name' => 'General']);
    JobOrder::factory()->for($user)->create(['job_order_type_id' => $parentA->id, 'description' => 'A']);

    $parentB = JobOrderType::factory()->create(['name' => 'Other']);
    $childB = JobOrderType::factory()->create(['parent_id' => $parentB->id, 'name' => 'OtherChild']);
    JobOrder::factory()->for($user)->create(['job_order_type_id' => $childB->id, 'description' => 'B']);

    $this->actingAs($user);
    $response = $this->get('/job-orders?type_parent='.$parentA->id);
    $response->assertSee('A');
    $response->assertDontSee('<td>B</td>', false);
});

it('filters job orders by assignee', function () {
    $requester = User::factory()->create();
    $assigneeA = User::factory()->create();
    $assigneeB = User::factory()->create();
    $type = JobOrderType::factory()->create();

    JobOrder::factory()->for($requester)->create([
        'job_order_type_id' => $type->id,
        'assigned_to_id' => $assigneeA->id,
        'description' => 'A',
        'status' => JobOrder::STATUS_ASSIGNED,
    ]);
    JobOrder::factory()->for($requester)->create([
        'job_order_type_id' => $type->id,
        'assigned_to_id' => $assigneeB->id,
        'description' => 'B',
        'status' => JobOrder::STATUS_ASSIGNED,
    ]);

    $this->actingAs($requester);
    $response = $this->get('/job-orders?assigned_to_id='.$assigneeA->id);
    $response->assertSee('A');
    $response->assertDontSee('<td>B</td>', false);
});

it('searches job order descriptions', function () {
    $user = User::factory()->create();
    $type = JobOrderType::factory()->create();
    JobOrder::factory()->for($user)->create(['job_order_type_id' => $type->id, 'description' => 'Fix laptop screen']);
    JobOrder::factory()->for($user)->create(['job_order_type_id' => $type->id, 'description' => 'Replace bulb']);

    $this->actingAs($user);
    $response = $this->get('/job-orders?search=laptop');
    $response->assertSee('laptop');
    $response->assertDontSee('bulb');
});
