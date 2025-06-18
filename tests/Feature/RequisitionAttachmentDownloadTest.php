<?php

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows owner to download requisition attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $path = UploadedFile::fake()->create('file.txt', 1)->store('requisition_attachments', 'public');
    $req = Requisition::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($owner);
    $this->get("/requisitions/{$req->id}/attachment")->assertOk()->assertDownload(basename($path));
});

it('prevents others from downloading requisition attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $other = User::factory()->create();
    $path = UploadedFile::fake()->create('file.txt', 1)->store('requisition_attachments', 'public');
    $req = Requisition::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->actingAs($other);
    $this->get("/requisitions/{$req->id}/attachment")->assertForbidden();
});

it('redirects guest to login when downloading requisition attachment', function () {
    Storage::fake('public');

    $owner = User::factory()->create();
    $path = UploadedFile::fake()->create('file.txt', 1)->store('requisition_attachments', 'public');
    $req = Requisition::factory()->for($owner)->create(['attachment_path' => $path]);

    $this->get("/requisitions/{$req->id}/attachment")->assertRedirect('/login');
});
