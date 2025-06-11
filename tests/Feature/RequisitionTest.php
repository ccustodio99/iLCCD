<?php

use App\Models\Requisition;
use App\Models\User;

it('allows authenticated user to create requisition', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/requisitions', [
        'item' => 'Laptop',
        'quantity' => 2,
        'specification' => 'Dell',
        'purpose' => 'Office work',
    ]);

    $response->assertRedirect('/requisitions');
    expect(Requisition::where('item', 'Laptop')->exists())->toBeTrue();
});

it('shows user requisitions', function () {
    $user = User::factory()->create();
    $req = Requisition::factory()->for($user)->create(['item' => 'Printer']);
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

    $req = Requisition::factory()->for($user)->create([
        'item' => 'Projector',
        'quantity' => 1,
    ]);

    $response = $this->put("/requisitions/{$req->id}", [
        'item' => 'Projector',
        'quantity' => 1,
        'specification' => $req->specification,
        'purpose' => $req->purpose,
        'status' => 'approved',
    ]);

    $response->assertRedirect('/requisitions');

    expect(App\Models\PurchaseOrder::where('requisition_id', $req->id)->exists())->toBeTrue();
});
