<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores attachment when creating ticket', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $category = TicketCategory::factory()->create(['name' => 'IT']);
    $this->actingAs($user);

    $response = $this->post('/tickets', [
        'ticket_category_id' => $category->id,
        'subject' => 'Broken',
        'description' => 'desc',
        'attachment' => UploadedFile::fake()->create('test.txt', 10),
    ]);

    $response->assertRedirect('/tickets');
    $ticket = Ticket::first();
    Storage::disk('public')->assertExists($ticket->attachment_path);
});
