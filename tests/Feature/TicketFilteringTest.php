<?php

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;

it('filters tickets by category', function () {
    $user = User::factory()->create();
    $parent = TicketCategory::factory()->create();
    $subA = TicketCategory::factory()->create(['parent_id' => $parent->id]);
    $subB = TicketCategory::factory()->create(['parent_id' => $parent->id]);

    Ticket::factory()->for($user)->create(['ticket_category_id' => $subA->id, 'subject' => 'Sub A']);
    Ticket::factory()->for($user)->create(['ticket_category_id' => $subB->id, 'subject' => 'Sub B']);

    $this->actingAs($user);

    $response = $this->get('/tickets?ticket_category_id=' . $subA->id);
    $response->assertSee('Sub A');
    $response->assertDontSee('Sub B');
});

it('filters tickets by assigned user', function () {
    $user = User::factory()->create();
    $assigneeA = User::factory()->create();
    $assigneeB = User::factory()->create();
    $cat = TicketCategory::factory()->create();

    Ticket::factory()->for($user)->for($cat)->create(['assigned_to_id' => $assigneeA->id, 'subject' => 'Ticket A']);
    Ticket::factory()->for($user)->for($cat)->create(['assigned_to_id' => $assigneeB->id, 'subject' => 'Ticket B']);

    $this->actingAs($user);

    $response = $this->get('/tickets?assigned_to_id=' . $assigneeA->id);
    $response->assertSee('Ticket A');
    $response->assertDontSee('Ticket B');
});

it('includes archived tickets when requested', function () {
    $user = User::factory()->create();
    $cat = TicketCategory::factory()->create();

    $archived = Ticket::factory()->for($user)->for($cat)->create(['subject' => 'Old']);
    $archived->delete();
    Ticket::factory()->for($user)->for($cat)->create(['subject' => 'Active']);

    $this->actingAs($user);
    $this->get('/tickets')->assertDontSee('Old');

    $this->get('/tickets?archived=1')->assertSee('Old');
});

it('filters archived tickets by status', function () {
    $user = User::factory()->create();
    $cat = TicketCategory::factory()->create();

    Ticket::factory()->for($user)->for($cat)->create(['status' => 'open', 'subject' => 'Open']);
    $closed = Ticket::factory()->for($user)->for($cat)->create(['status' => 'closed', 'subject' => 'Closed']);
    $closed->delete();

    $this->actingAs($user);
    $this->get('/tickets?status=closed')->assertDontSee('Closed');
    $this->get('/tickets?status=closed&archived=1')->assertSee('Closed');
});

it('searches subject and description', function () {
    $user = User::factory()->create();
    $cat = TicketCategory::factory()->create();

    Ticket::factory()->for($user)->for($cat)->create(['subject' => 'Laptop', 'description' => 'Broken']);
    Ticket::factory()->for($user)->for($cat)->create(['subject' => 'Printer', 'description' => 'Laptop error']);

    $this->actingAs($user);
    $response = $this->get('/tickets?search=Laptop');
    $response->assertSee('Laptop');
    $response->assertSee('Printer');
});
