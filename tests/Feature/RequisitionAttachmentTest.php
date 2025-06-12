<?php

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores attachment when creating requisition', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post('/requisitions', [
        'item' => ['Paper'],
        'quantity' => [1],
        'specification' => ['A4'],
        'purpose' => 'Office',
        'attachment' => UploadedFile::fake()->create('file.txt', 10),
    ])->assertRedirect('/requisitions');

    $req = Requisition::first();
    Storage::disk('public')->assertExists($req->attachment_path);
});

it('rejects invalid attachment file', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/requisitions', [
        'item' => ['Paper'],
        'quantity' => [1],
        'specification' => ['A4'],
        'purpose' => 'Office',
        'attachment' => 'not-a-file',
    ]);

    $response->assertSessionHasErrors('attachment');
});
