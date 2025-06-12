<?php

use App\Models\AuditTrail;
use App\Models\User;
use App\Models\TicketCategory;

it('records audit trail on ticket creation', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    $this->actingAs($user);

    $this->post('/tickets', [
        'ticket_category_id' => $category->id,
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
        'ticket_category_id' => $ticket->ticket_category_id,
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

it('logs login and logout actions', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/dashboard');

    expect(AuditTrail::where('user_id', $user->id)
        ->where('action', 'login')->exists())->toBeTrue();

    $this->post('/logout');

    expect(AuditTrail::where('user_id', $user->id)
        ->where('action', 'logout')->exists())->toBeTrue();
});

it('logs failed login attempts', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    expect(AuditTrail::where('user_id', $user->id)
        ->where('action', 'login_failed')->exists())->toBeTrue();
});

it('logs watchers update and assignment actions', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    $assignee = User::factory()->create();
    $watcher = User::factory()->create();
    $this->actingAs($user);

    $this->post('/tickets', [
        'ticket_category_id' => $category->id,
        'subject' => 'Printer',
        'description' => 'Broken',
        'watchers' => [$watcher->id],
    ])->assertRedirect('/tickets');

    $ticket = App\Models\Ticket::where('subject', 'Printer')->first();

    expect(AuditTrail::where('auditable_id', $ticket->id)
        ->where('action', 'watchers_updated')->exists())->toBeTrue();

    $this->put("/tickets/{$ticket->id}", [
        'ticket_category_id' => $ticket->ticket_category_id,
        'subject' => $ticket->subject,
        'description' => $ticket->description,
        'status' => $ticket->status,
        'assigned_to_id' => $assignee->id,
    ])->assertRedirect('/tickets');

    expect(AuditTrail::where('auditable_id', $ticket->id)
        ->where('action', 'assigned')->exists())->toBeTrue();
});
