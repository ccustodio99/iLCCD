<?php

use App\Models\Document;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\Ticket;
use App\Models\User;

it('returns matching records across modules', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Ticket::factory()->create(['subject' => 'UniqueTicket']);
    JobOrder::factory()->create(['description' => 'UniqueJobOrder']);
    Requisition::factory()->create(['purpose' => 'UniqueRequisition']);
    Document::factory()->create(['title' => 'UniqueDocument']);

    $response = $this->get('/search?query=Unique');

    $response->assertStatus(200);
    $response->assertSee('UniqueTicket');
    $response->assertSee('UniqueJobOrder');
    $response->assertSee('UniqueRequisition');
    $response->assertSee('UniqueDocument');
});
