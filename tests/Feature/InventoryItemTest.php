<?php

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use App\Models\InventoryCategory;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;

it('allows authorized user to create inventory item', function () {
    $user = User::factory()->create(['role' => 'admin']);
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
        'status' => InventoryItem::STATUS_AVAILABLE,
    ]);

    $response->assertRedirect('/inventory');
    expect(InventoryItem::where('name', 'Laptop')->exists())->toBeTrue();
});

it('shows user inventory items', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $item = InventoryItem::factory()->for($user)->create(['name' => 'Projector']);
    $this->actingAs($user);

    $response = $this->get('/inventory');
    $response->assertStatus(200);
    $response->assertSee('Projector');
});

it('prevents editing others inventory items', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $other = User::factory()->create();
    $item = InventoryItem::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/inventory/{$item->id}/edit");
    $response->assertForbidden();
});

it('updates quantity and records transaction when item is issued', function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    $user = User::factory()->create(['role' => 'admin']);
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
    $user = User::factory()->create(['role' => 'admin']);
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
    $user = User::factory()->create(['role' => 'admin']);
    InventoryItem::factory()->for($user)->create([
        'quantity' => 1,
        'minimum_stock' => 5,
    ]);
    $this->actingAs($user);

    $response = $this->get('/inventory');

    $response->assertSee('table-warning', false);
});

it('highlights out of stock items on index', function () {
    $user = User::factory()->create(['role' => 'admin']);
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
    \App\Models\Setting::set('notify_low_stock', true);
    \App\Models\Setting::set('template_low_stock', '{{ message }}');

    $user = User::factory()->create(['role' => 'admin', 'department' => 'IT']);
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

it('blocks unauthorized roles from inventory actions', function () {
    $staff = User::factory()->create(['role' => 'staff']);
    $this->actingAs($staff);

    $this->get('/inventory')->assertForbidden();
});

it('filters items by category', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $catA = InventoryCategory::factory()->create();
    $catB = InventoryCategory::factory()->create();
    InventoryItem::factory()->for($user)->for($catA)->create(['name' => 'Cat A']);
    InventoryItem::factory()->for($user)->for($catB)->create(['name' => 'Cat B']);
    $this->actingAs($user);

    $response = $this->get('/inventory?category=' . $catA->id);
    $response->assertStatus(200);
    $response->assertSee('Cat A');
    $response->assertDontSee('Cat B');
});

it('filters items by status', function () {
    $user = User::factory()->create(['role' => 'admin']);
    InventoryItem::factory()->for($user)->create(['name' => 'Avail', 'status' => 'available']);
    InventoryItem::factory()->for($user)->create(['name' => 'Hidden Item', 'status' => 'reserved']);
    $this->actingAs($user);

    $response = $this->get('/inventory?status=available');
    $response->assertSee('Avail');
    $response->assertDontSee('Hidden Item');
});

it('searches items by name', function () {
    $user = User::factory()->create(['role' => 'admin']);
    InventoryItem::factory()->for($user)->create(['name' => 'Projector']);
    InventoryItem::factory()->for($user)->create(['name' => 'Laptop']);
    $this->actingAs($user);

    $response = $this->get('/inventory?search=Lap');
    $response->assertSee('Laptop');
    $response->assertDontSee('Projector');
});

it('filters items by parent category', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $parent = InventoryCategory::factory()->create();
    $child = InventoryCategory::factory()->create(['parent_id' => $parent->id]);
    InventoryItem::factory()->for($user)->for($child)->create(['name' => 'Child Item']);
    $this->actingAs($user);

    $response = $this->get('/inventory?category=' . $parent->id);
    $response->assertDontSee('Child Item');
});

it('searches items by description', function () {
    $user = User::factory()->create(['role' => 'admin']);
    InventoryItem::factory()->for($user)->create(['name' => 'Desk', 'description' => 'Wood Laptop Desk']);
    InventoryItem::factory()->for($user)->create(['name' => 'Chair', 'description' => 'Comfort']);
    $this->actingAs($user);

    $response = $this->get('/inventory?search=Laptop');
    $response->assertSee('Desk');
    $response->assertDontSee('Chair');
});
