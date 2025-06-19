<?php

use App\Models\InventoryCategory;
use App\Models\User;

it('rejects self referencing parent', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = InventoryCategory::factory()->create();
    $this->actingAs($admin);

    $response = $this->from("/settings/inventory-categories/{$category->id}/edit")
        ->put("/settings/inventory-categories/{$category->id}", [
            'name' => $category->name,
            'parent_id' => $category->id,
            'is_active' => true,
        ]);

    $response->assertSessionHasErrors('parent_id');
});

it('rejects parent that causes cycle', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $grand = InventoryCategory::factory()->create(['name' => 'Grand']);
    $parent = InventoryCategory::factory()->create(['parent_id' => $grand->id, 'name' => 'Parent']);
    $child = InventoryCategory::factory()->create(['parent_id' => $parent->id, 'name' => 'Child']);
    $this->actingAs($admin);

    $response = $this->from("/settings/inventory-categories/{$grand->id}/edit")
        ->put("/settings/inventory-categories/{$grand->id}", [
            'name' => $grand->name,
            'parent_id' => $child->id,
            'is_active' => true,
        ]);

    $response->assertSessionHasErrors('parent_id');
});
