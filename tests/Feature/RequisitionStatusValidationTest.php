<?php

use App\Models\Requisition;
use App\Models\User;

it('rejects invalid status during update', function () {
    $user = User::factory()->create();
    $req = Requisition::factory()->for($user)->create();
    $item = $req->items->first();

    $this->actingAs($user);

    $response = $this->from("/requisitions/{$req->id}/edit")->put("/requisitions/{$req->id}", [
        'item' => [$item->item],
        'quantity' => [$item->quantity],
        'specification' => [$item->specification],
        'purpose' => $req->purpose,
        'status' => 'invalid',
    ]);

    $response->assertSessionHasErrors('status');
});
