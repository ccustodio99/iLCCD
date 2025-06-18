<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('rolls back when log creation fails on store', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();

    DocumentLog::creating(function () {
        throw new Exception('fail');
    });

    try {
        $this->post('/documents', [
            'title' => 'Policy',
            'description' => 'Important',
            'document_category_id' => $category->id,
            'file' => UploadedFile::fake()->create('policy.pdf', 100),
        ]);
    } catch (Exception $e) {
        // Ignored
    }

    expect(Document::count())->toBe(0);
    expect(DocumentVersion::count())->toBe(0);
    expect(DocumentLog::count())->toBe(0);

    DocumentLog::flushEventListeners();
    DocumentLog::boot();
});

it('rolls back when log creation fails on update', function () {
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

    $document = Document::first();
    $originalVersionCount = DocumentVersion::count();
    $originalLogCount = DocumentLog::count();
    $originalTitle = $document->title;

    DocumentLog::creating(function () {
        throw new Exception('fail');
    });

    try {
        $this->put("/documents/{$document->id}", [
            'title' => 'New Title',
            'description' => 'Updated',
            'document_category_id' => $category->id,
            'file' => UploadedFile::fake()->create('policy2.pdf', 100),
        ]);
    } catch (Exception $e) {
        // Ignored
    }

    $document->refresh();

    expect($document->title)->toBe($originalTitle);
    expect(DocumentVersion::count())->toBe($originalVersionCount);
    expect(DocumentLog::count())->toBe($originalLogCount);

    DocumentLog::flushEventListeners();
    DocumentLog::boot();
});
