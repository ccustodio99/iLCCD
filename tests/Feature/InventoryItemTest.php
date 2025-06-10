<?php

use App\Models\InventoryItem;
use App\Models\User;

it('allows authenticated user to create inventory item', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/inventory', [
        'name' => 'Laptop',
        'description' => 'Dell',
        'category' => 'IT',
        'department' => 'IT',
        'location' => 'Office',
        'supplier' => 'Supplier',
        'purchase_date' => now()->format('Y-m-d'),
        'quantity' => 5,
        'minimum_stock' => 1,
        'status' => 'available',
    ]);

    $response->assertRedirect('/inventory');
    expect(InventoryItem::where('name', 'Laptop')->exists())->toBeTrue();
});

it('shows user inventory items', function () {
    $user = User::factory()->create();
    $item = InventoryItem::factory()->for($user)->create(['name' => 'Projector']);
    $this->actingAs($user);

    $response = $this->get('/inventory');
    $response->assertStatus(200);
    $response->assertSee('Projector');
});

it('prevents editing others inventory items', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $item = InventoryItem::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/inventory/{$item->id}/edit");
    $response->assertForbidden();
});
