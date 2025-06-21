<?php

use App\Models\JobOrderType;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;

it('allows ticket owner to open conversion forms', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create();
    $ticket = Ticket::factory()->for($user)->create([
        'ticket_category_id' => $category->id,
    ]);

    $this->actingAs($user);

    $this->get("/tickets/{$ticket->id}/modal/convert-job-order")
        ->assertOk()
        ->assertSee('Convert to Job Order');

    $this->get("/tickets/{$ticket->id}/modal/convert-requisition")
        ->assertOk()
        ->assertSee('Convert to Requisition');
});

it('forbids others from accessing conversion forms', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $category = TicketCategory::factory()->create();
    $ticket = Ticket::factory()->for($owner)->create([
        'ticket_category_id' => $category->id,
    ]);

    $this->actingAs($other);

    $this->get("/tickets/{$ticket->id}/modal/convert-job-order")->assertForbidden();
    $this->get("/tickets/{$ticket->id}/modal/convert-requisition")->assertForbidden();
});

it('forbids others from converting tickets', function () {
    $owner = User::factory()->create(['department' => 'IT']);
    $other = User::factory()->create(['department' => 'IT']);
    $category = TicketCategory::factory()->create(['name' => 'Supplies']);
    $ticket = Ticket::factory()->for($owner)->create([
        'ticket_category_id' => $category->id,
        'description' => 'Need cables',
    ]);

    $parentType = JobOrderType::factory()->create();
    $childType = JobOrderType::factory()->create(['parent_id' => $parentType->id]);

    $this->actingAs($other);

    $this->post("/tickets/{$ticket->id}/convert", [
        'type_parent' => $parentType->id,
        'job_type' => $childType->name,
        'description' => 'Need cables',
    ])->assertForbidden();

    $this->post("/tickets/{$ticket->id}/requisition", [
        'item' => ['Cable'],
        'quantity' => [1],
        'purpose' => 'Need cables',
    ])->assertForbidden();
});

it('allows ticket owner to convert ticket', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $category = TicketCategory::factory()->create(['name' => 'Supplies']);
    $ticket = Ticket::factory()->for($user)->create([
        'ticket_category_id' => $category->id,
        'description' => 'Projector issue',
    ]);

    $parentType = JobOrderType::factory()->create();
    $childType = JobOrderType::factory()->create(['parent_id' => $parentType->id]);

    $this->actingAs($user);

    $this->post("/tickets/{$ticket->id}/convert", [
        'type_parent' => $parentType->id,
        'job_type' => $childType->name,
        'description' => 'Projector issue',
    ])->assertRedirect('/job-orders');

    $this->post("/tickets/{$ticket->id}/requisition", [
        'item' => ['Cable'],
        'quantity' => [1],
        'purpose' => 'Projector issue',
    ])->assertRedirect('/requisitions');
});
