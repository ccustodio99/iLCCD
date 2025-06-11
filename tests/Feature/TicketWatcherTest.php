<?php

use App\Models\Ticket;
use App\Models\User;

it('allows adding watchers on ticket creation', function () {
    $user = User::factory()->create();
    $watcher = User::factory()->create();
    $this->actingAs($user);

    $this->post('/tickets', [
        'category' => 'IT',
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
        'category' => $ticket->category,
        'subject' => $ticket->subject,
        'description' => $ticket->description,
        'status' => $ticket->status,
        'watchers' => [$newWatcher->id],
    ])->assertRedirect('/tickets');

    $ticket->refresh();
    expect($ticket->watchers->pluck('id'))->toContain($newWatcher->id)
        ->not->toContain($oldWatcher->id);
});
