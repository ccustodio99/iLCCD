<?php

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Requisition;
use App\Models\InventoryItem;

it('restricts purchase order creation to finance or admin roles', function () {
    $finance = User::factory()->create(['role' => 'finance']);
    $staff = User::factory()->create(['role' => 'staff']);
    $req = Requisition::factory()->for($finance)->create();
    $item = InventoryItem::factory()->for($finance)->create();

    $this->actingAs($staff);
    $this->post('/purchase-orders', [
        'requisition_id' => $req->id,
        'inventory_item_id' => $item->id,
        'item' => 'Printer',
        'quantity' => 2,
    ])->assertForbidden();

    $this->actingAs($finance);
    $this->post('/purchase-orders', [
        'requisition_id' => $req->id,
        'inventory_item_id' => $item->id,
        'item' => 'Printer',
        'quantity' => 2,
    ])->assertRedirect('/purchase-orders');

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

it('tracks status transitions when updated by finance', function () {
    $finance = User::factory()->create(['role' => 'finance']);
    $order = PurchaseOrder::factory()->for($finance)->create();

    $this->actingAs($finance);
    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => 'ordered',
    ])->assertRedirect('/purchase-orders');

    $order->refresh();
    expect($order->status)->toBe('ordered');
    expect($order->ordered_at)->not->toBeNull();

    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => 'received',
    ]);

    $order->refresh();
    expect($order->status)->toBe('received');
    expect($order->received_at)->not->toBeNull();
});
