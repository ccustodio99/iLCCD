<?php

use App\Models\Requisition;
use App\Models\User;

it('persists remarks on store and update', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post('/requisitions', [
        'item' => ['Laptop'],
        'quantity' => [1],
        'specification' => [''],
        'purpose' => 'Office',
        'remarks' => 'Need asap',
    ])->assertRedirect('/requisitions');

    $req = Requisition::first();
    expect($req->remarks)->toBe('Need asap');

    $this->put("/requisitions/{$req->id}", [
        'item' => ['Laptop'],
        'quantity' => [1],
        'specification' => [''],
        'purpose' => 'Office',
        'remarks' => 'Updated remarks',
        'status' => $req->status,
    ])->assertRedirect('/requisitions');

    $req->refresh();
    expect($req->remarks)->toBe('Updated remarks');
});
