<?php

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('logs document actions', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->post('/documents', [
        'title' => 'Handbook',
        'description' => 'Desc',
        'category' => 'policy',
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
