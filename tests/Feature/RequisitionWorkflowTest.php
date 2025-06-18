<?php

use App\Models\ApprovalProcess;
use App\Models\Requisition;
use App\Models\User;

it('enforces approval chain and displays remarks', function () {
    $process = ApprovalProcess::create([
        'module' => 'requisitions',
        'department' => 'Nursing',
    ]);
    $process->stages()->createMany([
        ['name' => Requisition::STATUS_PENDING_HEAD, 'position' => 1],
        ['name' => Requisition::STATUS_PENDING_PRESIDENT, 'position' => 2],
        ['name' => Requisition::STATUS_PENDING_FINANCE, 'position' => 3],
    ]);
    $requester = User::factory()->create(['role' => 'staff', 'department' => 'Nursing']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'Nursing']);
    $president = User::factory()->create(['role' => 'head', 'department' => 'President Department']);
    $finance = User::factory()->create(['role' => 'head', 'department' => 'Finance Office']);

    $this->actingAs($requester);
    $this->post('/requisitions', [
        'item' => ['Paper'],
        'quantity' => [1],
        'specification' => ['A4'],
        'purpose' => 'Office',
        'remarks' => 'For approval chain',
    ])->assertRedirect('/requisitions');

    $req = Requisition::first();
    expect($req->status)->toBe(Requisition::STATUS_PENDING_HEAD);
    expect($req->remarks)->toBe('For approval chain');

    $this->get('/requisitions')->assertSee('For approval chain');

    $this->actingAs($head);
    $this->get('/requisitions/approvals')->assertSee('For approval chain');
    $this->put("/requisitions/{$req->id}/approve")
        ->assertRedirect('/requisitions/approvals');

    $req->refresh();
    expect($req->status)->toBe(Requisition::STATUS_PENDING_PRESIDENT);

    $this->actingAs($head);
    $this->put("/requisitions/{$req->id}/approve")
        ->assertForbidden();

    $this->actingAs($president);
    $this->put("/requisitions/{$req->id}/approve");

    $req->refresh();
    expect($req->status)->toBe(Requisition::STATUS_PENDING_FINANCE);

    $this->actingAs($finance);
    $this->put("/requisitions/{$req->id}/approve");

    $req->refresh();
    expect($req->status)->toBe(Requisition::STATUS_APPROVED);
    expect($req->approved_by_id)->toBe($finance->id);
});
