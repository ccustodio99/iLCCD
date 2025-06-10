<?php

use App\Models\KpiLog;
use App\Models\Ticket;
use App\Models\User;

it('escalates overdue tickets', function () {
    $ticket = Ticket::factory()->create([
        'due_at' => now()->subDay(),
        'status' => 'open',
    ]);

    $this->artisan('tickets:check-sla');

    expect($ticket->fresh()->status)->toBe('escalated');
});

it('logs KPI entries for ticket events', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post('/tickets', [
        'category' => 'IT',
        'subject' => 'Printer',
        'description' => 'Broken printer',
    ]);

    $ticket = Ticket::first();
    expect(KpiLog::where('type', 'ticket_created')->where('ticket_id', $ticket->id)->exists())->toBeTrue();

    $this->put("/tickets/{$ticket->id}", [
        'category' => $ticket->category,
        'subject' => $ticket->subject,
        'description' => $ticket->description,
        'status' => 'closed',
    ]);

    expect(KpiLog::where('type', 'ticket_closed')->where('ticket_id', $ticket->id)->exists())->toBeTrue();
});
