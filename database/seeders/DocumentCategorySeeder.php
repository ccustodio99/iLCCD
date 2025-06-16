<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (DocumentCategory::DEFAULT_CATEGORIES as $name) {
            DocumentCategory::updateOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }
    }
}
