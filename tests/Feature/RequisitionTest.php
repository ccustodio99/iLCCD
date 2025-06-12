<?php

use App\Models\Requisition;
use App\Models\User;

it('allows authenticated user to create requisition', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/requisitions', [
        'item' => ['Laptop', 'Mouse'],
        'quantity' => [2, 1],
        'specification' => ['Dell', 'Logitech'],
        'purpose' => 'Office work',
    ]);

    $response->assertRedirect('/requisitions');
    expect(Requisition::whereHas('items', fn($q) => $q->where('item', 'Laptop'))->exists())->toBeTrue();
});

it('shows user requisitions', function () {
    $user = User::factory()->create();
    $req = Requisition::factory()->for($user)->create();
    $req->items()->update(['item' => 'Printer']);
    $this->actingAs($user);

    $response = $this->get('/requisitions');
    $response->assertStatus(200);
    $response->assertSee('Printer');
});

it('prevents editing others requisitions', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $req = Requisition::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/requisitions/{$req->id}/edit");
    $response->assertForbidden();
});

it('creates purchase order when approved and item missing', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $req = Requisition::factory()->for($user)->create();
    $req->items()->update([
        'item' => 'Projector',
        'quantity' => 1,
    ]);

    $response = $this->put("/requisitions/{$req->id}", [
        'item' => ['Projector'],
        'quantity' => [1],
        'specification' => [$req->items->first()->specification],
        'purpose' => $req->purpose,
        'status' => Requisition::STATUS_APPROVED,
    ]);

    $response->assertRedirect('/requisitions');

    expect(App\Models\PurchaseOrder::where('requisition_id', $req->id)->exists())->toBeTrue();
});

it('shows ticket reference on requisition list', function () {
    $user = User::factory()->create(['department' => 'IT']);
    $this->actingAs($user);

    $ticket = App\Models\Ticket::factory()->for($user)->create([
        'category' => 'Supplies',
        'subject' => 'Keyboard',
        'description' => 'Need new keyboard',
    ]);

    $this->post("/tickets/{$ticket->id}/requisition");

    $response = $this->get('/requisitions');

    $response->assertSee((string) $ticket->id);
});
