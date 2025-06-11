<?php

use App\Models\Ticket;
use App\Models\User;

it('allows authenticated user to create ticket', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/tickets', [
        'category' => 'IT',
        'subject' => 'Broken PC',
        'description' => 'My computer is not working',
        'due_at' => now()->addDay()->format('Y-m-d'),
    ]);

    $response->assertRedirect('/tickets');
    expect(Ticket::where('subject', 'Broken PC')->exists())->toBeTrue();
});

it('shows user tickets', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create(['subject' => 'Network issue']);
    $this->actingAs($user);

    $response = $this->get('/tickets');
    $response->assertStatus(200);
    $response->assertSee('Network issue');
});

it('prevents editing others tickets', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $ticket = Ticket::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/tickets/{$ticket->id}/edit");
    $response->assertForbidden();
});

it('converts ticket to job order', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $ticket = Ticket::factory()->for($user)->create([
        'category' => 'Facilities',
        'description' => 'Fix door',
    ]);

    $response = $this->post("/tickets/{$ticket->id}/convert");

    $response->assertRedirect('/job-orders');

    expect(App\Models\JobOrder::where('description', 'Fix door')->exists())->toBeTrue();
    $ticket->refresh();
    expect($ticket->status)->toBe('converted');
});

it('converts ticket to requisition', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $ticket = Ticket::factory()->for($user)->create([
        'category' => 'Supplies',
        'subject' => 'Projector Bulb',
        'description' => 'Need replacement bulb',
    ]);

    $response = $this->post("/tickets/{$ticket->id}/requisition");

    $response->assertRedirect('/requisitions');

    expect(App\Models\Requisition::whereHas('items', fn($q) => $q->where('item', 'Projector Bulb'))->exists())->toBeTrue();
    $ticket->refresh();
    expect($ticket->status)->toBe('converted');
});

it('adds watchers on ticket creation', function () {
    $user = User::factory()->create(['department' => 'CCS']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'CCS']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user);

    $this->post('/tickets', [
        'category' => 'IT',
        'subject' => 'Printer',
        'description' => 'Broken',
    ]);

    $ticket = Ticket::where('subject', 'Printer')->first();
    $expected = collect([$admin->id, $head->id])->sort()->values()->all();
    expect($ticket->watchers->pluck('id')->sort()->values()->all())
        ->toBe($expected);
});
