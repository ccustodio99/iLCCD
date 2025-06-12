<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;

it('allows authenticated user to create ticket', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    $this->actingAs($user);

    $response = $this->post('/tickets', [
        'category' => $category->name,
        'subject' => 'Broken PC',
        'description' => 'My computer is not working',
        'due_at' => now()->addDay()->format('Y-m-d'),
    ]);

    $response->assertRedirect('/tickets');
    expect(Ticket::where('subject', 'Broken PC')->exists())->toBeTrue();
});

it('rejects inactive ticket categories', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['is_active' => false]);
    $this->actingAs($user);

    $response = $this->from('/tickets/create')->post('/tickets', [
        'category' => $category->name,
        'subject' => 'Broken PC',
        'description' => 'My computer is not working',
    ]);

    $response->assertSessionHasErrors('category');
    expect(Ticket::count())->toBe(0);
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
    $cat = TicketCategory::factory()->create(['name' => 'Facilities']);
    $this->actingAs($user);

    $ticket = Ticket::factory()->for($user)->create([
        'category' => $cat->name,
        'description' => 'Fix door',
    ]);

    $response = $this->post("/tickets/{$ticket->id}/convert");

    $response->assertRedirect('/job-orders');

    $jobOrder = App\Models\JobOrder::where('description', 'Fix door')->first();
    expect($jobOrder)->not->toBeNull();
    expect($jobOrder->ticket_id)->toBe($ticket->id);
    $ticket->refresh();
    expect($ticket->status)->toBe('converted');
    expect($ticket->jobOrder->id)->toBe($jobOrder->id);
});

it('converts ticket to requisition', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $catSup = TicketCategory::factory()->create(['name' => 'Supplies']);
    $ticket = Ticket::factory()->for($user)->create([
        'category' => $catSup->name,
        'subject' => 'Projector Bulb',
        'description' => 'Need replacement bulb',
    ]);

    $response = $this->post("/tickets/{$ticket->id}/requisition");

    $response->assertRedirect('/requisitions');

    $req = App\Models\Requisition::whereHas('items', fn($q) => $q->where('item', 'Projector Bulb'))->first();
    expect($req)->not->toBeNull();
    expect($req->ticket_id)->toBe($ticket->id);
    $ticket->refresh();
    expect($ticket->status)->toBe('converted');
    expect($ticket->requisitions->first()->id)->toBe($req->id);
});

it('adds watchers on ticket creation', function () {
    $user = User::factory()->create(['department' => 'CCS']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'CCS']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user);

    $catIt = TicketCategory::factory()->create(['name' => 'IT']);
    $this->post('/tickets', [
        'category' => $catIt->name,
        'subject' => 'Printer',
        'description' => 'Broken',
    ]);

    $ticket = Ticket::where('subject', 'Printer')->first();
    $expected = collect([$admin->id, $head->id])->sort()->values()->all();
    expect($ticket->watchers->pluck('id')->sort()->values()->all())
        ->toBe($expected);
});

it('shows job order on ticket details after conversion', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $catFac = TicketCategory::factory()->create(['name' => 'Facilities']);
    $ticket = Ticket::factory()->for($user)->create([
        'category' => $catFac->name,
        'description' => 'Door repair',
    ]);

    $this->post("/tickets/{$ticket->id}/convert");

    $jobOrderId = $ticket->fresh()->jobOrder->id;

    $response = $this->get('/tickets');

    $response->assertSee('Job Order ID');
    $response->assertSee((string) $jobOrderId);
});

it('shows requisition on ticket details after conversion', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $catSup2 = TicketCategory::factory()->create(['name' => 'Supplies']);
    $ticket = Ticket::factory()->for($user)->create([
        'category' => $catSup2->name,
        'subject' => 'Laptop Charger',
        'description' => 'Need extra charger',
    ]);

    $this->post("/tickets/{$ticket->id}/requisition");

    $reqId = $ticket->fresh()->requisitions->first()->id;

    $response = $this->get('/tickets');

    $response->assertSee('Requisitions');
    $response->assertSee((string) $reqId);
});
