<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

it('logs document deletion without foreign key errors', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $this->actingAs($user);

    $category = DocumentCategory::factory()->create();
    $document = Document::factory()->for($user)->for($category)->create();
    DocumentVersion::factory()->for($document)->for($user, 'uploader')->create(['path' => 'documents/test.pdf']);

    $queries = [];
    DB::listen(function ($query) use (&$queries) {
        if (str_contains($query->sql, 'insert into "document_logs"')) {
            $queries[] = $query->sql;
        }
    });

    $response = $this->delete("/documents/{$document->id}");

    $response->assertRedirect('/documents');

    expect($queries)->not->toBeEmpty();
});
