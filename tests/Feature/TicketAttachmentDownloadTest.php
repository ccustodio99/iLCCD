<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows authorized users to download ticket attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $assignee = User::factory()->create();
    $watcher = User::factory()->create();

    $path = UploadedFile::fake()->create('file.txt', 1)->store('ticket_attachments', 'public');

    $ticket = Ticket::factory()->for($owner)->create([
        'assigned_to_id' => $assignee->id,
        'attachment_path' => $path,
    ]);
    $ticket->watchers()->sync([$watcher->id]);

    $this->actingAs($owner);
    $this->get("/tickets/{$ticket->id}/attachment")->assertOk()->assertDownload(basename($path));

    $this->actingAs($assignee);
    $this->get("/tickets/{$ticket->id}/attachment")->assertOk()->assertDownload(basename($path));

    $this->actingAs($watcher);
    $this->get("/tickets/{$ticket->id}/attachment")->assertOk()->assertDownload(basename($path));
});

it('prevents unauthorized users from downloading attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $other = User::factory()->create();

    $path = UploadedFile::fake()->create('file.txt', 1)->store('ticket_attachments', 'public');
    $ticket = Ticket::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($other);
    $this->get("/tickets/{$ticket->id}/attachment")->assertForbidden();
});
