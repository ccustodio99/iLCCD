<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;

it('allows adding watchers on ticket creation', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    $watcher = User::factory()->create();
    $this->actingAs($user);

    $this->post('/tickets', [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Broken',
        'watchers' => [$watcher->id],
    ])->assertRedirect('/tickets');

    $ticket = Ticket::where('subject', 'Printer')->first();
    expect($ticket->watchers->pluck('id'))->toContain($watcher->id);
});

it('allows updating watchers on ticket edit', function () {
    $user = User::factory()->create();
    $oldWatcher = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create();
    $ticket->watchers()->sync([$oldWatcher->id]);

    $newWatcher = User::factory()->create();
    $this->actingAs($user);

    $this->put("/tickets/{$ticket->id}", [
        'ticket_category_id' => $ticket->ticket_category_id,
        'subject' => $ticket->subject,
        'description' => $ticket->description,
        'status' => $ticket->status,
        'watchers' => [$newWatcher->id],
    ])->assertRedirect('/tickets');

    $ticket->refresh();
    expect($ticket->watchers->pluck('id'))->toContain($newWatcher->id)
        ->not->toContain($oldWatcher->id);
});
