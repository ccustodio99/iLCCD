<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Policy', 'Syllabus', 'Report'];
        foreach ($categories as $name) {
            DocumentCategory::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }
    }
}
