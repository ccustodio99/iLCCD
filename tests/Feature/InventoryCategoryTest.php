<?php

use App\Models\InventoryCategory;
use App\Models\User;
use Database\Seeders\InventoryCategorySeeder;
use Database\Seeders\UserSeeder;

it('allows admin to create inventory category', function () {
    $this->seed([UserSeeder::class, InventoryCategorySeeder::class]);
    $admin = User::firstWhere('role', 'admin');
    $this->actingAs($admin);

    $response = $this->post('/settings/inventory-categories', [
        'name' => 'Tools',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/inventory-categories');
    expect(InventoryCategory::where('name', 'Tools')->exists())->toBeTrue();
});

it('allows admin to update inventory category', function () {
    $this->seed([UserSeeder::class, InventoryCategorySeeder::class]);
    $admin = User::firstWhere('role', 'admin');
    $category = InventoryCategory::firstWhere('name', 'Electronics');
    $this->actingAs($admin);

    $response = $this->put("/settings/inventory-categories/{$category->id}", [
        'name' => 'Updated Tools',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/inventory-categories');
    expect($category->fresh()->name)->toBe('Updated Tools');
});

it('allows admin to disable inventory category', function () {
    $this->seed([UserSeeder::class, InventoryCategorySeeder::class]);
    $admin = User::firstWhere('role', 'admin');
    $category = InventoryCategory::firstWhere('name', 'Electronics');
    $this->actingAs($admin);

    $response = $this->put("/settings/inventory-categories/{$category->id}/disable");

    $response->assertRedirect('/settings/inventory-categories');
    expect($category->fresh()->is_active)->toBeFalse();
});

it('allows admin to delete inventory category', function () {
    $this->seed([UserSeeder::class, InventoryCategorySeeder::class]);
    $admin = User::firstWhere('role', 'admin');
    $category = InventoryCategory::firstWhere('name', 'Electronics');
    $this->actingAs($admin);

    $response = $this->delete("/settings/inventory-categories/{$category->id}");

    $response->assertRedirect('/settings/inventory-categories');
    $response->assertSessionHas('success');
    expect(InventoryCategory::where('id', $category->id)->exists())->toBeFalse();
});

it('rejects duplicate inventory category names on create', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    InventoryCategory::factory()->create(['name' => 'Tools']);
    $this->actingAs($admin);

    $response = $this->from('/settings/inventory-categories/create')
        ->post('/settings/inventory-categories', [
            'name' => 'Tools',
            'is_active' => true,
        ]);

    $response->assertSessionHasErrors('name');
});

it('rejects duplicate inventory category names on update', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $existing = InventoryCategory::factory()->create(['name' => 'Existing']);
    $category = InventoryCategory::factory()->create(['name' => 'Other']);
    $this->actingAs($admin);

    $response = $this->from("/settings/inventory-categories/{$category->id}/edit")
        ->put("/settings/inventory-categories/{$category->id}", [
            'name' => 'Existing',
            'is_active' => true,
        ]);

    $response->assertSessionHasErrors('name');
});
