<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentVersion;
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

it('rejects inactive document categories', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create(['is_active' => false]);

    $response = $this->from('/documents/create')->post('/documents', [
        'title' => 'Policy',
        'description' => 'Important',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('policy.pdf', 100),
    ]);

    $response->assertSessionHasErrors('document_category_id');
    expect(Document::count())->toBe(0);
});

it('rejects unsupported file types when creating document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();

    $response = $this->post('/documents', [
        'title' => 'Bad File',
        'description' => 'Important',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('virus.exe', 10),
    ]);

    $response->assertSessionHasErrors('file');
    expect(Document::count())->toBe(0);
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

it('rejects unsupported file types when updating document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();
    $this->post('/documents', [
        'title' => 'Policy',
        'description' => 'Important',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('policy.pdf', 10),
    ]);

    $document = Document::first();

    $response = $this->put("/documents/{$document->id}", [
        'title' => 'Policy',
        'description' => 'Updated',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('malware.exe', 10),
    ]);

    $response->assertSessionHasErrors('file');
});

it('filters documents by category', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $catA = DocumentCategory::factory()->create();
    $catB = DocumentCategory::factory()->create();

    Document::factory()->for($user)->for($catA)->create(['title' => 'Cat A']);
    Document::factory()->for($user)->for($catB)->create(['title' => 'Cat B']);

    $response = $this->get('/documents?category='.$catA->id);
    $response->assertSee('Cat A');
    $response->assertDontSee('Cat B');
});

it('searches documents by title and description', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $cat = DocumentCategory::factory()->create();
    Document::factory()->for($user)->for($cat)->create(['title' => 'Handbook']);
    Document::factory()->for($user)->for($cat)->create([
        'title' => 'Policy',
        'description' => 'Handbook guidelines',
    ]);

    $response = $this->get('/documents?search=Handbook');
    $response->assertSee('Handbook');
    $response->assertSee('Policy');
});

it('filters documents by date range', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $cat = DocumentCategory::factory()->create();
    Document::factory()->for($user)->for($cat)->create([
        'title' => 'Old Doc',
        'created_at' => now()->subDays(10),
    ]);
    Document::factory()->for($user)->for($cat)->create([
        'title' => 'New Doc',
        'created_at' => now(),
    ]);

    $from = now()->subDays(5)->format('Y-m-d');
    $to = now()->format('Y-m-d');

    $response = $this->get('/documents?from='.$from.'&to='.$to);
    $response->assertSee('New Doc');
    $response->assertDontSee('Old Doc');
});

it('rejects unsupported file types when uploading document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();

    $response = $this->from('/documents/create')->post('/documents', [
        'title' => 'Invalid',
        'description' => 'Bad',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('file.txt', 10),
    ]);

    $response->assertSessionHasErrors('file');
    expect(Document::count())->toBe(0);
});

it('rejects unsupported file types when updating document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();
    $this->post('/documents', [
        'title' => 'Valid',
        'description' => 'Ok',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('valid.pdf', 10),
    ]);

    $document = Document::first();

    $response = $this->from("/documents/{$document->id}/edit")->put("/documents/{$document->id}", [
        'title' => 'Valid',
        'description' => 'Ok',
        'document_category_id' => $category->id,
        'file' => UploadedFile::fake()->create('bad.txt', 10),
    ]);

    $response->assertSessionHasErrors('file');
});
