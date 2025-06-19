<?php

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;

it('prevents deactivating category in use', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = TicketCategory::factory()->create();
    Ticket::factory()->for($admin)->for($category)->create();
    $this->actingAs($admin);

    $response = $this->from("/settings/ticket-categories/{$category->id}/edit")
        ->put("/settings/ticket-categories/{$category->id}", [
            'name' => $category->name,
            'parent_id' => null,
            'is_active' => false,
        ]);

    $response->assertRedirect("/settings/ticket-categories/{$category->id}/edit");
    $response->assertSessionHas('error');
    expect($category->fresh()->is_active)->toBeTrue();
});

it('prevents archiving category in use', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = TicketCategory::factory()->create();
    Ticket::factory()->for($admin)->for($category)->create();
    $this->actingAs($admin);

    $response = $this->delete("/settings/ticket-categories/{$category->id}");

    $response->assertRedirect('/settings/ticket-categories');
    $response->assertSessionHas('error');
    expect(TicketCategory::withTrashed()->find($category->id))->not->toBeNull();
});
