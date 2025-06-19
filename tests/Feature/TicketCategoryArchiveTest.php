<?php

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;

it('displays ticket index when category archived', function () {
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    Ticket::factory()->for($user)->for($category)->create(['subject' => 'Archived Cat']);
    $category->delete();

    $this->actingAs($user);
    $response = $this->get('/tickets');
    $response->assertOk()->assertSee('Archived Cat');
});

it('shows approvals page when ticket category archived', function () {
    $requester = User::factory()->create(['role' => 'staff', 'department' => 'IT']);
    $head = User::factory()->create(['role' => 'head', 'department' => 'IT']);
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    Ticket::factory()->for($requester)->for($category)->create();
    $category->delete();

    $this->actingAs($head);
    $this->get('/tickets/approvals')->assertOk();
});
