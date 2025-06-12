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

it('shows all purchase orders to admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $a = User::factory()->create();
    $b = User::factory()->create();
    $orderA = PurchaseOrder::factory()->for($a)->create(['item' => 'ItemA']);
    $orderB = PurchaseOrder::factory()->for($b)->create(['item' => 'ItemB']);

    $this->actingAs($admin);
    $response = $this->get('/purchase-orders');
    $response->assertStatus(200);
    $response->assertSee('ItemA');
    $response->assertSee('ItemB');
});

it('prevents editing others purchase orders', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $order = PurchaseOrder::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/purchase-orders/{$order->id}/edit");
    $response->assertForbidden();
});

it('allows admin to edit any purchase order', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $owner = User::factory()->create();
    $order = PurchaseOrder::factory()->for($owner)->create();

    $this->actingAs($admin);
    $this->get("/purchase-orders/{$order->id}/edit")->assertOk();
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
        'status' => PurchaseOrder::STATUS_PENDING_APPROVAL,
    ])->assertRedirect('/purchase-orders');

    $order->refresh();
    expect($order->status)->toBe(PurchaseOrder::STATUS_PENDING_APPROVAL);

    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => PurchaseOrder::STATUS_APPROVED,
    ]);

    $order->refresh();
    expect($order->status)->toBe(PurchaseOrder::STATUS_APPROVED);

    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => PurchaseOrder::STATUS_ORDERED,
    ]);

    $order->refresh();
    expect($order->status)->toBe(PurchaseOrder::STATUS_ORDERED);
    expect($order->ordered_at)->not->toBeNull();

    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => PurchaseOrder::STATUS_RECEIVED,
    ]);

    $order->refresh();
    expect($order->status)->toBe(PurchaseOrder::STATUS_RECEIVED);
    expect($order->received_at)->not->toBeNull();

    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => PurchaseOrder::STATUS_CLOSED,
    ]);

    $order->refresh();
    expect($order->status)->toBe(PurchaseOrder::STATUS_CLOSED);

    $this->put("/purchase-orders/{$order->id}", [
        'requisition_id' => $order->requisition_id,
        'inventory_item_id' => $order->inventory_item_id,
        'supplier' => $order->supplier,
        'item' => $order->item,
        'quantity' => $order->quantity,
        'status' => PurchaseOrder::STATUS_CANCELLED,
    ]);

    $order->refresh();
    expect($order->status)->toBe(PurchaseOrder::STATUS_CANCELLED);
});
