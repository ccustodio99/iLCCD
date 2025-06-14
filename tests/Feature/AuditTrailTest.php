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

it('filters logs by user and action', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $this->actingAs($admin);

    AuditTrail::factory()->for($userA)->create(['action' => 'login']);
    AuditTrail::factory()->for($userB)->create(['action' => 'logout']);

    $response = $this->get('/audit-trails?user_id='.$userA->id.'&action=login');
    $response->assertSee($userA->name);
    expect($response->viewData('logs')->count())->toBe(1);
});

it('filters logs by date range', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    AuditTrail::factory()->for($admin)->create([
        'action' => 'ancient',
        'created_at' => now()->subDays(10),
    ]);
    AuditTrail::factory()->for($admin)->create([
        'action' => 'recent',
        'created_at' => now()->subDays(1),
    ]);

    $from = now()->subDays(5)->format('Y-m-d');
    $to = now()->format('Y-m-d');

    $response = $this->get('/audit-trails?from='.$from.'&to='.$to);
    $response->assertSee('recent');
    expect($response->viewData('logs')->count())->toBe(1);
});
