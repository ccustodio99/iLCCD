<?php

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Requisition;
use App\Models\InventoryItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores attachment when creating purchase order', function () {
    Storage::fake('public');
    $user = User::factory()->create(['role' => 'finance']);
    $this->actingAs($user);

    $req = Requisition::factory()->for($user)->create();
    $item = InventoryItem::factory()->for($user)->create();

    $this->post('/purchase-orders', [
        'requisition_id' => $req->id,
        'inventory_item_id' => $item->id,
        'item' => 'Paper',
        'quantity' => 5,
        'attachment' => UploadedFile::fake()->create('file.txt', 10),
    ])->assertRedirect('/purchase-orders');

    $order = PurchaseOrder::first();
    Storage::disk('public')->assertExists($order->attachment_path);
});

it('allows owner to download purchase order attachment', function () {
    Storage::fake('public');
    $user = User::factory()->create(['role' => 'finance']);
    $path = UploadedFile::fake()->create('file.txt', 1)->store('purchase_order_attachments', 'public');
    $order = PurchaseOrder::factory()->for($user)->create(['attachment_path' => $path]);

    $this->actingAs($user);
    $this->get("/purchase-orders/{$order->id}/attachment")
        ->assertOk()
        ->assertDownload(basename($path));
});

it('prevents others from downloading purchase order attachment', function () {
    Storage::fake('public');
    $owner = User::factory()->create(['role' => 'finance']);
    $other = User::factory()->create();
    $path = UploadedFile::fake()->create('file.txt', 1)->store('purchase_order_attachments', 'public');
    $order = PurchaseOrder::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($other);
    $this->get("/purchase-orders/{$order->id}/attachment")->assertForbidden();
});

it('allows admin to download any purchase order attachment', function () {
    Storage::fake('public');
    $owner = User::factory()->create(['role' => 'finance']);
    $admin = User::factory()->create(['role' => 'admin']);
    $path = UploadedFile::fake()->create('file.txt', 1)->store('purchase_order_attachments', 'public');
    $order = PurchaseOrder::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($admin);
    $this->get("/purchase-orders/{$order->id}/attachment")
        ->assertOk()
        ->assertDownload(basename($path));
});

