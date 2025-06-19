<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows admin to create document category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/settings/document-categories', [
        'name' => 'Policies',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/document-categories');
    expect(DocumentCategory::where('name', 'Policies')->exists())->toBeTrue();
});

it('allows admin to update document category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = DocumentCategory::factory()->create(['name' => 'Policies']);
    $this->actingAs($admin);

    $response = $this->put("/settings/document-categories/{$category->id}", [
        'name' => 'Procedures',
        'is_active' => true,
    ]);

    $response->assertRedirect('/settings/document-categories');
    expect($category->fresh()->name)->toBe('Procedures');
});

it('allows admin to delete document category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = DocumentCategory::factory()->create();
    $this->actingAs($admin);

    $response = $this->delete("/settings/document-categories/{$category->id}");

    $response->assertRedirect('/settings/document-categories');
    expect(DocumentCategory::where('id', $category->id)->exists())->toBeFalse();
});

it('rejects duplicate document category names', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    DocumentCategory::factory()->create(['name' => 'Policies']);
    $this->actingAs($admin);

    $response = $this->from('/settings/document-categories/create')
        ->post('/settings/document-categories', [
            'name' => 'Policies',
            'is_active' => true,
        ]);

    $response->assertSessionHasErrors('name');
});

it('prevents deleting category with documents', function () {
    Storage::fake('local');
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $category = DocumentCategory::factory()->create();
    $document = Document::factory()->for($admin)->for($category)->create();

    $path = UploadedFile::fake()->create('file.pdf', 10)->store('documents');
    DocumentVersion::create([
        'document_id' => $document->id,
        'version' => 1,
        'path' => $path,
        'uploaded_by' => $admin->id,
    ]);

    $this->from('/settings/document-categories')
        ->delete("/settings/document-categories/{$category->id}")
        ->assertRedirect('/settings/document-categories')
        ->assertSessionHas('error');

    Storage::disk('local')->assertExists($path);
    expect(Document::count())->toBe(1);
    expect(DocumentCategory::find($category->id))->not->toBeNull();
});
