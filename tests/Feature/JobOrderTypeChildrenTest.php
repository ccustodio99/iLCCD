<?php
use App\Models\JobOrderType;
use App\Models\User;

it('returns active children for parent', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $parent = JobOrderType::factory()->create(['name' => 'Maintenance']);
    $child1 = JobOrderType::factory()->create(['parent_id' => $parent->id, 'name' => 'Repair']);
    $child2 = JobOrderType::factory()->create(['parent_id' => $parent->id, 'name' => 'Install', 'is_active' => false]);

    $response = $this->get("/job-order-types/{$parent->id}/children");

    $response->assertOk();
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['name' => $child1->name]);
    $response->assertJsonMissing(['name' => $child2->name]);
});

it('validates subtype belongs to parent', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $parentA = JobOrderType::factory()->create(['name' => 'A']);
    $parentB = JobOrderType::factory()->create(['name' => 'B']);
    $childB = JobOrderType::factory()->create(['parent_id' => $parentB->id, 'name' => 'B1']);

    $response = $this->from('/job-orders/create')->post('/job-orders', [
        'type_parent' => $parentA->id,
        'job_type' => $childB->name,
        'description' => 'test',
    ]);

    $response->assertSessionHasErrors('job_type');
});
