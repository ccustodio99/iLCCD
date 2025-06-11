<?php

use App\Models\AuditTrail;
use App\Models\User;

it('records audit trail on ticket creation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post('/tickets', [
        'category' => 'IT',
        'subject' => 'Printer',
        'description' => 'Broken',
    ])->assertRedirect('/tickets');

    expect(AuditTrail::where('auditable_type', App\Models\Ticket::class)->where('action', 'created')->exists())->toBeTrue();
});

it('stores changed fields on update', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ticket = App\Models\Ticket::factory()->for($user)->create(['subject' => 'Old']);

    $this->put("/tickets/{$ticket->id}", [
        'category' => $ticket->category,
        'subject' => 'New',
        'description' => $ticket->description,
        'status' => $ticket->status,
    ])->assertRedirect('/tickets');

    $log = AuditTrail::where('auditable_id', $ticket->id)
        ->where('auditable_type', App\Models\Ticket::class)
        ->where('action', 'updated')
        ->latest()
        ->first();

    expect($log->changes['subject']['old'])->toBe('Old');
    expect($log->changes['subject']['new'])->toBe('New');
});
