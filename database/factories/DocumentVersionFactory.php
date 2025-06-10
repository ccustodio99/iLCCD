<?php

namespace Database\Factories;

use App\Models\DocumentVersion;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentVersion>
 */
class DocumentVersionFactory extends Factory
{
    protected $model = DocumentVersion::class;

    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'version' => 1,
            'path' => 'documents/sample.pdf',
            'uploaded_by' => User::factory(),
        ];
    }
}
