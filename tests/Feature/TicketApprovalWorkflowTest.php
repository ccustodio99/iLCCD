<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;

it('routes ticket through head approval', function () {
    $category = TicketCategory::factory()->create();
    $requester = User::factory()->create(['role' => 'staff', 'department' => 'IT']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'IT']);

    $this->actingAs($requester);
    $this->post('/tickets', [
        'ticket_category_id' => $category->id,
        'subject' => 'Broken PC',
        'description' => 'Does not boot',
    ])->assertRedirect('/tickets');

    $ticket = Ticket::first();
    expect($ticket->status)->toBe(Ticket::STATUS_PENDING_HEAD);

    $this->actingAs($head);
    $this->get('/tickets/approvals')->assertSee('Broken PC');
    $this->put("/tickets/{$ticket->id}/approve")->assertRedirect('/tickets/approvals');

    $ticket->refresh();
    expect($ticket->status)->toBe(Ticket::STATUS_OPEN);
    expect($ticket->approved_by_id)->toBe($head->id);
    expect($ticket->approved_at)->not->toBeNull();
});
