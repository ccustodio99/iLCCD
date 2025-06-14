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

it('filters dashboard data by query parameters', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $catA = DocumentCategory::factory()->create();
    $catB = DocumentCategory::factory()->create();

    $userA = User::factory()->create(['department' => 'CCS']);
    $userB = User::factory()->create(['department' => 'HR']);

    $doc1 = Document::factory()->for($userA)->for($catA)->create(['department' => 'CCS']);
    $doc2 = Document::factory()->for($userB)->for($catB)->create(['department' => 'HR']);

    DocumentLog::factory()->for($userA)->for($doc1)->create(['action' => 'upload']);
    DocumentLog::factory()->for($userB)->for($doc2)->create(['action' => 'upload']);

    $this->actingAs($admin);

    $response = $this->get('/documents-dashboard?user_id='.$userA->id.'&department=CCS&document_category_id='.$catA->id);

    expect($response->viewData('totalUploads'))->toBe(1);
    expect($response->viewData('recentLogs')->count())->toBe(1);
});
