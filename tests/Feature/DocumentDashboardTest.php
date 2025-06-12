<?php

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('logs document actions', function () {
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

    expect(DocumentLog::where('action', 'upload')->where('user_id', $user->id)->exists())->toBeTrue();
});

it('shows metrics on dashboard', function () {
    $user = User::factory()->create();
    $doc = Document::factory()->for($user)->create();
    DocumentLog::factory()->for($user)->for($doc)->create(['action' => 'upload']);
    $this->actingAs($user);

    $response = $this->get('/documents-dashboard');
    $response->assertStatus(200);
    $response->assertSee('Document KPI');
});
