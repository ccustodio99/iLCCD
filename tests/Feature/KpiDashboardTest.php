<?php

use App\Models\User;
use App\Models\Ticket;
use App\Models\JobOrder;
use App\Models\Requisition;
use App\Models\AuditTrail;

it('allows authenticated users to view the KPI dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/kpi-dashboard');
    $response->assertStatus(200);
    $response->assertSee('System KPI & Audit Dashboard', false);
});

it('displays metrics and audit logs', function () {
    $user = User::factory()->create();
    Ticket::factory()->create();
    JobOrder::factory()->create();
    Requisition::factory()->create();
    AuditTrail::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/kpi-dashboard');
    $response->assertStatus(200);
    $response->assertSee('Tickets');
    $response->assertSee('Job Orders');
    $response->assertSee('Requisitions');
    $response->assertSee('created');
});
