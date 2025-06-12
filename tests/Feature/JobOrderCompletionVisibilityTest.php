<?php

use App\Models\JobOrder;
use App\Models\User;

it('hides completion button from non owners', function () {
    $owner = User::factory()->create();
    $assignee = User::factory()->create();

    $order = JobOrder::factory()->for($owner)->create([
        'assigned_to_id' => $assignee->id,
        'status' => JobOrder::STATUS_ASSIGNED,
    ]);

    $this->actingAs($assignee);

    $response = $this->get('/job-orders');

    $response->assertOk();
    $response->assertDontSee('Job Complete');
});
