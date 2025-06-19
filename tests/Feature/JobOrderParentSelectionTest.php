<?php

use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\User;

it('allows selecting parent when no child types exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $parent = JobOrderType::factory()->create(['name' => 'General']);

    $response = $this->post('/job-orders', [
        'type_parent' => $parent->id,
        'job_type' => $parent->name,
        'description' => 'Parent only',
    ]);

    $response->assertRedirect('/job-orders');
    expect(JobOrder::where('job_type', $parent->name)->exists())->toBeTrue();
});

it('allows updating using parent when no child types exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $parent = JobOrderType::factory()->create(['name' => 'General']);
    $order = JobOrder::factory()->for($user)->create([
        'job_type' => $parent->name,
        'description' => 'Old',
    ]);

    $response = $this->put("/job-orders/{$order->id}", [
        'type_parent' => $parent->id,
        'job_type' => $parent->name,
        'description' => 'Updated',
    ]);

    $response->assertRedirect('/job-orders');
    expect($order->fresh()->description)->toBe('Updated');
});
