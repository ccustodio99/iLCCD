<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

it('records log entry when deleting a document', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();
    $document = Document::factory()->for($user)->for($category)->create();

    $path = UploadedFile::fake()->create('file.pdf', 10)->store('documents');
    DocumentVersion::create([
        'document_id' => $document->id,
        'version' => 1,
        'path' => $path,
        'uploaded_by' => $user->id,
    ]);

    Event::fake();

    $response = $this->delete("/documents/{$document->id}");

    $response->assertRedirect('/documents');

    Event::assertDispatched('eloquent.created: '.DocumentLog::class);

    expect(Document::count())->toBe(0);
    expect(DocumentLog::count())->toBe(0);
});
