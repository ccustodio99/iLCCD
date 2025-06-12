<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'document_category_id' => DocumentCategory::factory(),
            'department' => 'CCS',
            'current_version' => 1,
        ];
    }
}
