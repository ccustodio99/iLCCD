<?php

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class FailingUploadedFile extends UploadedFile
{
    public function store($path = '', $options = [])
    {
        throw new Exception('store failed');
    }
}

it('rolls back when document upload fails', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $this->actingAs($user);
    $category = DocumentCategory::factory()->create();

    $temp = tempnam(sys_get_temp_dir(), 'fail');
    file_put_contents($temp, 'content');
    $file = new FailingUploadedFile($temp, 'fail.pdf', null, null, true);

    $response = $this->post('/documents', [
        'title' => 'Policy',
        'description' => 'Important',
        'document_category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertStatus(500);
    expect(Document::count())->toBe(0);
    expect(DocumentVersion::count())->toBe(0);
    expect(DocumentLog::count())->toBe(0);
});

it('rolls back when document update fails', function () {
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

    $temp = tempnam(sys_get_temp_dir(), 'fail');
    file_put_contents($temp, 'content');
    $file = new FailingUploadedFile($temp, 'fail.pdf', null, null, true);

    $response = $this->put("/documents/{$document->id}", [
        'title' => 'New Policy',
        'description' => 'Updated',
        'document_category_id' => $category->id,
        'file' => $file,
    ]);

    $response->assertStatus(500);

    $document->refresh();
    expect($document->title)->toBe('Policy');
    expect(DocumentVersion::count())->toBe(1);
    expect(DocumentLog::count())->toBe(1);

});
