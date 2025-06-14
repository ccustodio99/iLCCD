<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;
use App\Models\JobOrderType;

it('allows authenticated user to create ticket', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    $this->actingAs($user);

    $response = $this->post('/tickets', [
        'ticket_category_id' => $category->id,
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
        'ticket_category_id' => $category->id,
        'subject' => 'Broken PC',
        'description' => 'My computer is not working',
    ]);

    $response->assertSessionHasErrors('ticket_category_id');
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
        'ticket_category_id' => $cat->id,
        'description' => 'Fix door',
    ]);

    $parentType = JobOrderType::factory()->create();
    $childType = JobOrderType::factory()->create(['parent_id' => $parentType->id]);

    $response = $this->post("/tickets/{$ticket->id}/convert", [
        'type_parent' => $parentType->id,
        'job_type' => $childType->name,
        'description' => 'Fix door',
    ]);

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
        'ticket_category_id' => $catSup->id,
        'subject' => 'Projector Bulb',
        'description' => 'Need replacement bulb',
    ]);

    $response = $this->post("/tickets/{$ticket->id}/requisition", [
        'item' => ['Projector Bulb'],
        'quantity' => [1],
        'purpose' => 'Need replacement bulb',
    ]);

    $response->assertRedirect('/requisitions');

    $req = App\Models\Requisition::whereHas('items', fn($q) => $q->where('item', 'Projector Bulb'))->first();
    expect($req)->not->toBeNull();
    expect($req->ticket_id)->toBe($ticket->id);
    $ticket->refresh();
    expect($ticket->status)->toBe('converted');
    expect($ticket->requisitions->first()->id)->toBe($req->id);
});

it('uses ticket owners department when converted by assignee', function () {
    $owner = User::factory()->create(['department' => 'IT']);
    $assignee = User::factory()->create(['department' => 'HR']);
    $this->actingAs($assignee);

    $catSup = TicketCategory::factory()->create(['name' => 'Supplies']);
    $ticket = Ticket::factory()->for($owner)->create([
        'ticket_category_id' => $catSup->id,
        'assigned_to_id' => $assignee->id,
        'subject' => 'Whiteboard Marker',
        'description' => 'Need new marker',
    ]);

    $this->post("/tickets/{$ticket->id}/requisition", [
        'item' => ['Whiteboard Marker'],
        'quantity' => [1],
        'purpose' => 'Need new marker',
    ])
        ->assertRedirect('/requisitions');

    $req = App\Models\Requisition::where('ticket_id', $ticket->id)->first();
    expect($req)->not->toBeNull();
    expect($req->department)->toBe($owner->department);
});

it('adds watchers on ticket creation', function () {
    $user = User::factory()->create(['department' => 'CCS']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'CCS']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user);

    $catIt = TicketCategory::factory()->create(['name' => 'IT']);
    $this->post('/tickets', [
        'ticket_category_id' => $catIt->id,
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
        'ticket_category_id' => $catFac->id,
        'description' => 'Door repair',
    ]);

    $parent = JobOrderType::factory()->create();
    $child = JobOrderType::factory()->create(['parent_id' => $parent->id]);
    $this->post("/tickets/{$ticket->id}/convert", [
        'type_parent' => $parent->id,
        'job_type' => $child->name,
        'description' => 'Door repair',
    ]);

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
        'ticket_category_id' => $catSup2->id,
        'subject' => 'Laptop Charger',
        'description' => 'Need extra charger',
    ]);

    $this->post("/tickets/{$ticket->id}/requisition", [
        'item' => ['Laptop Charger'],
        'quantity' => [1],
        'purpose' => 'Need extra charger',
    ]);

    $reqId = $ticket->fresh()->requisitions->first()->id;

    $response = $this->get('/tickets');

    $response->assertSee('Requisitions');
    $response->assertSee((string) $reqId);
});

it('archives ticket and marks it closed', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->for($user)->create(['status' => 'open']);
    $this->actingAs($user);

    $this->delete("/tickets/{$ticket->id}")->assertRedirect('/tickets');

    $archived = Ticket::withTrashed()->find($ticket->id);
    expect($archived->archived_at)->not->toBeNull();
    expect($archived->status)->toBe('closed');
    expect($archived->resolved_at)->not->toBeNull();
});

it('filters tickets by status', function () {
    $user = User::factory()->create();
    Ticket::factory()->for($user)->create(['subject' => 'Subject A', 'status' => 'open']);
    Ticket::factory()->for($user)->create(['subject' => 'Subject B', 'status' => 'closed']);
    $this->actingAs($user);

    $response = $this->get('/tickets?status=closed');
    $response->assertStatus(200);
    $response->assertSee('Subject B');
    $response->assertDontSee('Subject A');
});

it('searches tickets by subject', function () {
    $user = User::factory()->create();
    Ticket::factory()->for($user)->create(['subject' => 'Laptop problem']);
    Ticket::factory()->for($user)->create(['subject' => 'Printer issue']);
    $this->actingAs($user);

    $response = $this->get('/tickets?search=Laptop');
    $response->assertStatus(200);
    $response->assertSee('Laptop problem');
    $response->assertDontSee('Printer issue');
});
