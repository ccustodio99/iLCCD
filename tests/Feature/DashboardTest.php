<?php

use App\Models\User;
use App\Models\PurchaseOrder;

it('redirects to login if unauthenticated', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

it('shows dashboard for authenticated users', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Pending Tickets');
});

it('omits completed purchase orders in dashboard data', function () {
    $user = User::factory()->create();
    PurchaseOrder::factory()->for($user)->create(['item' => 'Draft', 'status' => PurchaseOrder::STATUS_DRAFT]);
    PurchaseOrder::factory()->for($user)->create(['item' => 'Received', 'status' => PurchaseOrder::STATUS_RECEIVED]);
    PurchaseOrder::factory()->for($user)->create(['item' => 'Closed', 'status' => PurchaseOrder::STATUS_CLOSED]);
    PurchaseOrder::factory()->for($user)->create(['item' => 'Cancelled', 'status' => PurchaseOrder::STATUS_CANCELLED]);

    $this->actingAs($user);

    $response = $this->getJson('/dashboard/data');
    $items = array_column($response->json('purchaseOrders.data'), 'item');

    expect($items)->toContain('Draft');
    expect($items)->not->toContain('Received');
    expect($items)->not->toContain('Closed');
    expect($items)->not->toContain('Cancelled');
});

it('filters purchase orders by status in dashboard data', function () {
    $user = User::factory()->create();
    PurchaseOrder::factory()->for($user)->create(['item' => 'Draft Only', 'status' => PurchaseOrder::STATUS_DRAFT]);
    PurchaseOrder::factory()->for($user)->create(['item' => 'Ordered Item', 'status' => PurchaseOrder::STATUS_ORDERED]);

    $this->actingAs($user);

    $response = $this->getJson('/dashboard/data?po_status=' . PurchaseOrder::STATUS_ORDERED);
    $items = array_column($response->json('purchaseOrders.data'), 'item');

    expect($items)->toContain('Ordered Item');
    expect($items)->not->toContain('Draft Only');
});

it('returns dashboard data as json', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->getJson('/dashboard/data');
    $response->assertStatus(200);
    $response->assertJsonStructure(['tickets','jobOrders','requisitions']);
});
