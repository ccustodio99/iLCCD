<?php
use App\Models\InventoryCategory;
use App\Models\User;

it('allows admin to create inventory category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/inventory-categories', [
        'name' => 'Tools',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/inventory-categories');
    expect(InventoryCategory::where('name', 'Tools')->exists())->toBeTrue();
});

it('allows admin to update inventory category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = InventoryCategory::factory()->create(['name' => 'Tools']);
    $this->actingAs($admin);

    $response = $this->put("/settings/inventory-categories/{$category->id}", [
        'name' => 'Updated Tools',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/inventory-categories');
    expect($category->fresh()->name)->toBe('Updated Tools');
});

it('allows admin to disable inventory category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = InventoryCategory::factory()->create(['is_active' => true]);
    $this->actingAs($admin);

    $response = $this->put("/settings/inventory-categories/{$category->id}/disable");

    $response->assertRedirect('/settings/inventory-categories');
    expect($category->fresh()->is_active)->toBeFalse();
});

it('allows admin to delete inventory category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = InventoryCategory::factory()->create();
    $this->actingAs($admin);

    $response = $this->delete("/settings/inventory-categories/{$category->id}");

    $response->assertRedirect('/settings/inventory-categories');
    expect(InventoryCategory::where('id', $category->id)->exists())->toBeFalse();
});
