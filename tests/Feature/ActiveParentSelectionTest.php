<?php

use App\Models\InventoryCategory;
use App\Models\JobOrderType;
use App\Models\TicketCategory;
use App\Models\User;

it('shows only active inventory category parents', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $active = InventoryCategory::factory()->create(['name' => 'Active']);
    $inactive = InventoryCategory::factory()->create(['name' => 'Inactive', 'is_active' => false]);

    $this->actingAs($admin);
    $response = $this->get('/settings/inventory-categories/create');

    $response->assertOk();
    $parents = $response->viewData('parents');

    expect($parents->pluck('id')->all())->toContain($active->id);
    expect($parents->pluck('id')->all())->not->toContain($inactive->id);
});

it('shows only active job order type parents', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $active = JobOrderType::factory()->create(['name' => 'Active']);
    $inactive = JobOrderType::factory()->create(['name' => 'Inactive', 'is_active' => false]);

    $this->actingAs($admin);
    $response = $this->get('/settings/job-order-types/create');

    $response->assertOk();
    $parents = $response->viewData('parents');

    expect($parents->pluck('id')->all())->toContain($active->id);
    expect($parents->pluck('id')->all())->not->toContain($inactive->id);
});

it('shows only active ticket category parents', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $active = TicketCategory::factory()->create(['name' => 'Active']);
    $inactive = TicketCategory::factory()->create(['name' => 'Inactive', 'is_active' => false]);

    $this->actingAs($admin);
    $response = $this->get('/settings/ticket-categories/create');

    $response->assertOk();
    $parents = $response->viewData('parents');

    expect($parents->pluck('id')->all())->toContain($active->id);
    expect($parents->pluck('id')->all())->not->toContain($inactive->id);
});
