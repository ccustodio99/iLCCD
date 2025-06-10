<?php

use App\Models\JobOrder;
use App\Models\User;

it('allows authenticated user to create job order', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/job-orders', [
        'job_type' => 'Repair',
        'description' => 'Fix projector',
    ]);

    $response->assertRedirect('/job-orders');
    expect(JobOrder::where('description', 'Fix projector')->exists())->toBeTrue();
});

it('shows user job orders', function () {
    $user = User::factory()->create();
    $order = JobOrder::factory()->for($user)->create(['job_type' => 'Setup']);
    $this->actingAs($user);

    $response = $this->get('/job-orders');
    $response->assertStatus(200);
    $response->assertSee('Setup');
});

it('prevents editing others job orders', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $order = JobOrder::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/job-orders/{$order->id}/edit");
    $response->assertForbidden();
});
