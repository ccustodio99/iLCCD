<?php

use App\Models\Requisition;
use App\Models\User;

it('locks requester editing after head approval until returned', function () {
    $requester = User::factory()->create(['role' => 'staff', 'department' => 'Nursing']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'Nursing']);

    $this->actingAs($requester);
    $requisition = Requisition::factory()->for($requester)->create([
        'department' => 'Nursing',
    ]);

    $this->actingAs($head);
    $this->put("/requisitions/{$requisition->id}/approve");

    $requisition->refresh();
    expect($requisition->status)->toBe(Requisition::STATUS_PENDING_PRESIDENT);

    $this->actingAs($requester);
    $item = $requisition->items->first();
    $this->put("/requisitions/{$requisition->id}", [
        'item' => [$item->item],
        'quantity' => [$item->quantity],
        'specification' => [$item->specification],
        'purpose' => $requisition->purpose,
        'status' => $requisition->status,
    ])->assertForbidden();

    $this->actingAs($head);
    $this->put("/requisitions/{$requisition->id}/return", ['remarks' => 'revise']);

    $requisition->refresh();
    expect($requisition->status)->toBe(Requisition::STATUS_PENDING_HEAD);

    $this->actingAs($requester);
    $this->put("/requisitions/{$requisition->id}", [
        'item' => [$item->item],
        'quantity' => [$item->quantity],
        'specification' => [$item->specification],
        'purpose' => $requisition->purpose,
        'status' => $requisition->status,
    ])->assertRedirect('/requisitions');
});
