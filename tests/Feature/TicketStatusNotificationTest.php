<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketStatusNotification;

it('dispatches status notifications on ticket events', function () {
    Notification::fake();
    \App\Models\Setting::set('notify_ticket_updates', true);
    \App\Models\Setting::set('template_ticket_updates', '{{ message }}');

    $owner = User::factory()->create(['role' => 'staff']);
    $assignee = User::factory()->create(['role' => 'head']);
    $watcher = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);

    // create with assignment and watcher
    $this->actingAs($owner);
    $this->post('/tickets', [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Broken',
        'assigned_to_id' => $assignee->id,
        'watchers' => [$watcher->id],
    ])->assertRedirect('/tickets');
    $ticket = Ticket::first();

    // update
    $this->put("/tickets/{$ticket->id}", [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Really broken',
        'status' => 'open',
        'assigned_to_id' => $assignee->id,
        'watchers' => [$watcher->id],
    ])->assertRedirect('/tickets');

    // escalate
    $this->actingAs($assignee);
    $this->put("/tickets/{$ticket->id}", [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Really broken',
        'status' => 'escalated',
        'assigned_to_id' => $assignee->id,
        'watchers' => [$watcher->id],
    ])->assertRedirect('/tickets');

    // comment
    $this->actingAs($watcher);
    $this->post("/tickets/{$ticket->id}/comments", [
        'comment' => 'Please fix',
    ])->assertRedirect();

    Notification::assertSentTimes(TicketStatusNotification::class, 15);
});
