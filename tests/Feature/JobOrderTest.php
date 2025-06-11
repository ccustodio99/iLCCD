<?php

use App\Models\JobOrder;
use App\Models\User;
use App\Models\InventoryItem;
use App\Models\Requisition;

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

it('deducts inventory if materials available', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $order = JobOrder::factory()->for($user)->create();
    $item = InventoryItem::factory()->for($user)->create([
        'name' => 'Cable',
        'quantity' => 5,
    ]);

    $response = $this->post("/job-orders/{$order->id}/materials", [
        'item' => 'Cable',
        'quantity' => 3,
        'purpose' => 'Setup network',
    ]);

    $response->assertRedirect('/job-orders');
    $item->refresh();
    expect($item->quantity)->toBe(2);
});

it('creates requisition when materials not in stock', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $order = JobOrder::factory()->for($user)->create();

    $response = $this->post("/job-orders/{$order->id}/materials", [
        'item' => 'Switch',
        'quantity' => 1,
        'purpose' => 'New network install',
    ]);

    $response->assertRedirect('/job-orders');

    expect(Requisition::where('job_order_id', $order->id)
        ->whereHas('items', fn($q) => $q->where('item', 'Switch'))
        ->exists())->toBeTrue();
});

it('marks job order complete', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $order = JobOrder::factory()->for($user)->create();

    $response = $this->put("/job-orders/{$order->id}/complete");

    $response->assertRedirect('/job-orders');
    $order->refresh();
    expect($order->status)->toBe('completed');
    expect($order->completed_at)->not->toBeNull();
});
