<?php

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use App\Models\InventoryCategory;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;

it('allows authenticated user to create inventory item', function () {
    $user = User::factory()->create();
    $category = InventoryCategory::factory()->create(['name' => 'IT']);
    $this->actingAs($user);

    $response = $this->post('/inventory', [
        'name' => 'Laptop',
        'description' => 'Dell',
        'inventory_category_id' => $category->id,
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

it('updates quantity and records transaction when item is issued', function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    $user = User::factory()->create();
    $item = InventoryItem::factory()->for($user)->create(['quantity' => 5]);
    $this->actingAs($user);

    $request = Illuminate\Http\Request::create('/', 'POST', ['quantity' => 2, 'purpose' => 'Maintenance']);
    $request->setUserResolver(fn () => $user);
    $controller = new App\Http\Controllers\InventoryItemController();
    $response = $controller->issue($request, $item);

    expect($response->status())->toBe(302);
    $item->refresh();
    expect($item->quantity)->toBe(3);
    expect(InventoryTransaction::where('inventory_item_id', $item->id)
        ->where('action', 'issue')
        ->where('purpose', 'Maintenance')
        ->exists())->toBeTrue();
});

it('updates quantity and records transaction when item is returned', function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    $user = User::factory()->create();
    $item = InventoryItem::factory()->for($user)->create(['quantity' => 5]);
    $this->actingAs($user);

    $request = Illuminate\Http\Request::create('/', 'POST', ['quantity' => 2, 'purpose' => 'Return']);
    $request->setUserResolver(fn () => $user);
    $controller = new App\Http\Controllers\InventoryItemController();
    $response = $controller->return($request, $item);

    expect($response->status())->toBe(302);
    $item->refresh();
    expect($item->quantity)->toBe(7);
    expect(InventoryTransaction::where('inventory_item_id', $item->id)
        ->where('action', 'return')
        ->where('purpose', 'Return')
        ->exists())->toBeTrue();
});

it('highlights low stock items on index', function () {
    $user = User::factory()->create();
    InventoryItem::factory()->for($user)->create([
        'quantity' => 1,
        'minimum_stock' => 5,
    ]);
    $this->actingAs($user);

    $response = $this->get('/inventory');

    $response->assertSee('table-warning', false);
});

it('highlights out of stock items on index', function () {
    $user = User::factory()->create();
    InventoryItem::factory()->for($user)->create([
        'quantity' => 0,
        'minimum_stock' => 5,
    ]);
    $this->actingAs($user);

    $response = $this->get('/inventory');

    $response->assertSee('table-danger', false);
});

it('dispatches low stock notification when issuing causes quantity to drop below minimum', function () {
    Notification::fake();

    $user = User::factory()->create(['department' => 'IT']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'IT']);
    $custodian = User::factory()->create(['department' => 'IT']);

    $item = InventoryItem::factory()->for($user)->create([
        'department' => 'IT',
        'quantity' => 5,
        'minimum_stock' => 5,
    ]);

    $this->actingAs($user);

    $request = Illuminate\Http\Request::create('/', 'POST', ['quantity' => 1]);
    $request->setUserResolver(fn () => $user);
    $controller = new App\Http\Controllers\InventoryItemController();
    $controller->issue($request, $item);

    Notification::assertSentTimes(LowStockNotification::class, 2);
    Notification::assertSentTo([$head, $custodian], LowStockNotification::class);
});
