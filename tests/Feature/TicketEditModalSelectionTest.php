<?php

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;

it('preselects category and sub-category in edit modal', function () {
    $user = User::factory()->create();
    $parent = TicketCategory::factory()->create(['name' => 'Hardware']);
    $child = TicketCategory::factory()->create([
        'parent_id' => $parent->id,
        'name' => 'Laptop',
    ]);

    $ticket = Ticket::factory()->for($user)->create([
        'ticket_category_id' => $child->id,
    ]);

    $parent->delete();

    $this->actingAs($user);
    $response = $this->get("/tickets/{$ticket->id}/modal/edit");

    $response->assertOk();
    $response->assertSee("option value=\"{$parent->id}\" selected", false);
    $response->assertSee("data-selected=\"{$child->id}\"", false);
});
