<?php

use App\Models\Requisition;
use App\Models\User;

it('enforces role restrictions during requisition approval workflow', function () {
    $req = Requisition::factory()->create([
        'status' => Requisition::STATUS_PENDING_HEAD,
    ]);

    $president = User::factory()->create(['role' => 'president']);
    $finance = User::factory()->create(['role' => 'finance']);
    $head = User::factory()->create(['role' => 'head']);

    $this->actingAs($president);
    $this->put("/requisitions/{$req->id}/approve")->assertForbidden();

    $this->actingAs($finance);
    $this->put("/requisitions/{$req->id}/approve")->assertForbidden();

    $this->actingAs($head);
    $this->put("/requisitions/{$req->id}/approve")->assertRedirect('/requisitions/approvals');

    $req->refresh();
    expect($req->status)->toBe(Requisition::STATUS_PENDING_PRESIDENT);

    // head cannot approve again
    $this->actingAs($head);
    $this->put("/requisitions/{$req->id}/approve")->assertForbidden();

    $this->actingAs($president);
    $this->put("/requisitions/{$req->id}/approve");
    $req->refresh();
    expect($req->status)->toBe(Requisition::STATUS_PENDING_FINANCE);

    $this->actingAs($finance);
    $this->put("/requisitions/{$req->id}/approve");
    $req->refresh();
    expect($req->status)->toBe(Requisition::STATUS_APPROVED);
});
