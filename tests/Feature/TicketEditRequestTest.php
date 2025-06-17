<?php

use App\Models\Ticket;
use App\Models\User;

it('allows staff to request edit but not change status directly', function () {
    $user = User::factory()->create(['role' => 'staff']);
    $ticket = Ticket::factory()->for($user)->create(['status' => 'closed']);

    $this->actingAs($user);

    $this->put("/tickets/{$ticket->id}", [
        'ticket_category_id' => $ticket->ticket_category_id,
        'subject' => $ticket->subject,
        'description' => $ticket->description,
        'status' => 'open',
    ])->assertForbidden();

    $this->post("/tickets/{$ticket->id}/request-edit", ['reason' => 'update info'])
        ->assertRedirect();

    $ticket->refresh();
    expect($ticket->status)->toBe('open');
    expect($ticket->edit_request_reason)->toBe('update info');
    expect($ticket->edit_requested_at)->not->toBeNull();
    expect($ticket->edit_requested_by)->toBe($user->id);
});
