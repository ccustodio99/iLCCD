<?php

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows authenticated user to upload document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/documents', [
        'title' => 'Policy',
        'description' => 'Important',
        'category' => 'policy',
        'file' => UploadedFile::fake()->create('policy.pdf', 100),
    ]);

    $response->assertRedirect('/documents');
    expect(Document::where('title', 'Policy')->exists())->toBeTrue();
    $version = DocumentVersion::first();
    Storage::disk('local')->assertExists($version->path);
});

it('shows user documents', function () {
    $user = User::factory()->create();
    $doc = Document::factory()->for($user)->create(['title' => 'Handbook']);
    $this->actingAs($user);

    $response = $this->get('/documents');
    $response->assertStatus(200);
    $response->assertSee('Handbook');
});

it('prevents editing others documents', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $doc = Document::factory()->for($other)->create();
    $this->actingAs($user);

    $response = $this->get("/documents/{$doc->id}/edit");
    $response->assertForbidden();
});
