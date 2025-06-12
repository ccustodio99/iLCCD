<?php

use App\Models\Requisition;
use App\Models\User;

it('routes requisition through approval workflow with remarks visible', function () {
    $requester = User::factory()->create(['role' => 'staff']);
    $head = User::factory()->create(['role' => 'head']);
    $president = User::factory()->create(['role' => 'president']);
    $finance = User::factory()->create(['role' => 'finance']);

    // requester creates requisition with remarks
    $this->actingAs($requester);
    $this->post('/requisitions', [
        'item' => ['Printer'],
        'quantity' => [1],
        'specification' => [''],
        'purpose' => 'Office',
        'remarks' => 'Urgent replacement',
    ])->assertRedirect('/requisitions');
    $requisition = Requisition::first();

    expect($requisition->status)->toBe(Requisition::STATUS_PENDING_HEAD);
    expect($requisition->remarks)->toBe('Urgent replacement');

    // remarks visible to requester
    $this->get('/requisitions')->assertSee('Urgent replacement');

    // head approval - others forbidden
    $this->actingAs($president);
    $this->put("/requisitions/{$requisition->id}/approve")->assertForbidden();
    $this->actingAs($finance);
    $this->put("/requisitions/{$requisition->id}/approve")->assertForbidden();
    $this->actingAs($head);
    $this->get('/requisitions/approvals')->assertSee('Urgent replacement');
    $this->put("/requisitions/{$requisition->id}/approve")->assertRedirect('/requisitions/approvals');
    $requisition->refresh();
    expect($requisition->status)->toBe(Requisition::STATUS_PENDING_PRESIDENT);

    // president approval - others forbidden
    $this->actingAs($head);
    $this->put("/requisitions/{$requisition->id}/approve")->assertForbidden();
    $this->actingAs($finance);
    $this->put("/requisitions/{$requisition->id}/approve")->assertForbidden();
    $this->actingAs($president);
    $this->put("/requisitions/{$requisition->id}/approve");
    $requisition->refresh();
    expect($requisition->status)->toBe(Requisition::STATUS_PENDING_FINANCE);

    // finance approval - others forbidden
    $this->actingAs($head);
    $this->put("/requisitions/{$requisition->id}/approve")->assertForbidden();
    $this->actingAs($president);
    $this->put("/requisitions/{$requisition->id}/approve")->assertForbidden();
    $this->actingAs($finance);
    $this->put("/requisitions/{$requisition->id}/approve");
    $requisition->refresh();
    expect($requisition->status)->toBe(Requisition::STATUS_APPROVED);
    expect($requisition->approved_at)->not->toBeNull();
});

