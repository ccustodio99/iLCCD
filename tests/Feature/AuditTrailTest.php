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
