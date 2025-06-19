<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

it('hides soft deleted documents and allows restore', function () {
    Notification::fake();
    $user = User::factory()->create();
    $category = DocumentCategory::factory()->create();

    $document = Document::factory()->for($user)->for($category)->create();
    DocumentVersion::factory()->for($document)->for($user, 'uploader')->create();
    DocumentLog::factory()->for($document)->for($user)->create();

    $document->delete();

    expect(Document::find($document->id))->toBeNull();
    expect(DocumentVersion::count())->toBe(0);
    expect(DocumentLog::count())->toBe(0);

    $document->restore();

    expect(Document::find($document->id))->not->toBeNull();
    expect(DocumentVersion::count())->toBe(1);
    expect(DocumentLog::count())->toBe(1);
});

it('hides soft deleted categories and allows restore', function () {
    Notification::fake();
    $category = DocumentCategory::factory()->create();

    $category->delete();
    expect(DocumentCategory::find($category->id))->toBeNull();

    $category->restore();
    expect(DocumentCategory::find($category->id))->not->toBeNull();
});
