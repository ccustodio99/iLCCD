<?php

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\User;

it('prevents deleting inventory category in use', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = InventoryCategory::factory()->create();
    InventoryItem::factory()->for($admin)->for($category)->create();
    $this->actingAs($admin);

    $response = $this->delete("/settings/inventory-categories/{$category->id}");

    $response->assertRedirect('/settings/inventory-categories');
    $response->assertSessionHas('error');
    expect(InventoryCategory::find($category->id))->not->toBeNull();
});
