<?php

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use App\Notifications\TicketStatusNotification;
use Illuminate\Support\Facades\Notification;

it('dispatches status notifications on ticket events', function () {
    Notification::fake();
    config(['license.enabled' => false]);
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

    // approve
    $this->actingAs($assignee);
    $this->put("/tickets/{$ticket->id}/approve");

    // update
    $this->actingAs($assignee);
    $this->put("/tickets/{$ticket->id}", [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Really broken',
        'status' => Ticket::STATUS_OPEN,
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

    Notification::assertSentTimes(TicketStatusNotification::class, 12);
    Notification::assertSentTo([$owner, $assignee, $watcher], TicketStatusNotification::class, function ($notification, $channels) {
        return in_array('database', $channels) && in_array('mail', $channels);
    });
});

it('stores notifications without email when disabled', function () {
    Notification::fake();
    config(['license.enabled' => false]);
    \App\Models\Setting::set('notify_ticket_updates', false);
    \App\Models\Setting::set('template_ticket_updates', '{{ message }}');

    $owner = User::factory()->create(['role' => 'staff']);
    $watcher = User::factory()->create();
    $category = TicketCategory::factory()->create();

    $this->actingAs($owner);
    $this->post('/tickets', [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Broken',
        'watchers' => [$watcher->id],
    ]);

    Notification::assertSentTimes(TicketStatusNotification::class, 2);
    Notification::assertSentTo([$owner, $watcher], TicketStatusNotification::class, function ($notification, $channels) {
        return $channels === ['database'];
    });
});
