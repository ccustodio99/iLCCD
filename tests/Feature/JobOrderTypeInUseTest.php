<?php

use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\User;

it('prevents deleting job order type in use', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $type = JobOrderType::factory()->create();
    JobOrder::factory()->for($admin)->create(['job_type' => $type->name]);
    $this->actingAs($admin);

    $response = $this->delete("/settings/job-order-types/{$type->id}");

    $response->assertRedirect('/settings/job-order-types');
    $response->assertSessionHas('error');
    expect(JobOrderType::find($type->id))->not->toBeNull();
});
