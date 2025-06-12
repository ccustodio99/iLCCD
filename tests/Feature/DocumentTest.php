<?php

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows authenticated user to upload document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();

    $response = $this->post('/documents', [
        'title' => 'Policy',
        'description' => 'Important',
        'document_category_id' => $category->id,
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

it('shows document details with versions', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();
    $this->post('/documents', [
        'title' => 'Handbook',
        'description' => 'Desc',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('file.pdf', 10),
    ]);

    $document = Document::first();
    $response = $this->get("/documents/{$document->id}");
    $response->assertStatus(200);
    $response->assertSee('Versions');
});

it('prevents viewing documents from other departments', function () {
    $user = User::factory()->create(['department' => 'CCS']);
    $other = User::factory()->create(['department' => 'HR']);
    $doc = Document::factory()->for($other)->create(['department' => 'HR']);

    $this->actingAs($user);
    $this->get("/documents/{$doc->id}")->assertForbidden();
});

it('allows downloading document versions', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();
    $this->post('/documents', [
        'title' => 'Policy',
        'description' => 'Important',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('policy.pdf', 100),
    ]);

    $version = DocumentVersion::first();
    $response = $this->get("/documents/{$version->document_id}/versions/{$version->id}/download");
    $response->assertStatus(200);
});
