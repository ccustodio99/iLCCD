<?php

use App\Models\Ticket;

it('escalates overdue tickets when command runs', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
        'due_at' => now()->subDay(),
    ]);

    $this->artisan('tickets:check-sla')->assertExitCode(0);

    $ticket->refresh();
    expect($ticket->status)->toBe('escalated');
    expect($ticket->escalated_at)->not->toBeNull();
});
