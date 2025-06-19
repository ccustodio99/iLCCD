<?php

use App\Models\TicketCategory;
use App\Models\User;

it('orders ticket categories by name and respects per_page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    TicketCategory::factory()->create(['name' => 'Echo']);
    TicketCategory::factory()->create(['name' => 'Alpha']);
    TicketCategory::factory()->create(['name' => 'Charlie']);
    TicketCategory::factory()->create(['name' => 'Delta']);
    TicketCategory::factory()->create(['name' => 'Bravo']);
    TicketCategory::factory()->create(['name' => 'Foxtrot']);

    $this->actingAs($admin);

    $response = $this->get('/settings/ticket-categories?per_page=5');
    $response->assertOk();

    $categories = $response->viewData('categories');
    expect($categories->count())->toBe(5);
    expect($categories->items()[0]->name)->toBe('Alpha');
    expect($categories->items()[1]->name)->toBe('Bravo');
    expect($categories->items()[2]->name)->toBe('Charlie');
    expect($categories->items()[3]->name)->toBe('Delta');
    expect($categories->items()[4]->name)->toBe('Echo');
});
