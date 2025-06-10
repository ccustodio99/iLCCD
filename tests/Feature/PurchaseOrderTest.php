<?php

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Requisition;
use App\Models\InventoryItem;

it('allows authenticated user to create purchase order', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $req = Requisition::factory()->for($user)->create();
    $item = InventoryItem::factory()->for($user)->create();

    $response = $this->post('/purchase-orders', [
        'requisition_id' => $req->id,
        'inventory_item_id' => $item->id,
        'item' => 'Printer',
        'quantity' => 2,
    ]);

    $response->assertRedirect('/purchase-orders');
    expect(PurchaseOrder::where('item', 'Printer')->exists())->toBeTrue();
});

it('shows user purchase orders', function () {
    $user = User::factory()->create();
    $order = PurchaseOrder::factory()->for($user)->create(['item' => 'Laptop']);
    $this->actingAs($user);

    $response = $this->get('/purchase-orders');
    $response->assertStatus(200);
    $response->assertSee('Laptop');
});

it('prevents editing others purchase orders', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $order = PurchaseOrder::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/purchase-orders/{$order->id}/edit");
    $response->assertForbidden();
});
