<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketComment;

it('allows watchers to comment on ticket', function () {
    $user = User::factory()->create();
    $watcher = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();
    $ticket->watchers()->sync([$watcher->id]);

    $this->actingAs($watcher);

    $this->post("/tickets/{$ticket->id}/comments", [
        'comment' => 'Watching',
    ])->assertRedirect();

    expect(TicketComment::where('ticket_id', $ticket->id)->where('user_id', $watcher->id)->exists())->toBeTrue();
});

it('prevents non-watchers from commenting', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();

    $this->actingAs($other);

    $this->post("/tickets/{$ticket->id}/comments", [
        'comment' => 'Hello',
    ])->assertForbidden();
});
