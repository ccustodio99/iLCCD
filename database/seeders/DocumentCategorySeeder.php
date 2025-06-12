<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Policies & Procedures',
            'Forms & Templates',
            'Course Materials',
            'Student Records',
            'Financial & Accounting',
            'Research & Publications',
            'Marketing & Communications',
            'Meeting Minutes & Reports',
            'Archives & Historical',
            'Miscellaneous',
        ];
        foreach ($categories as $name) {
            DocumentCategory::firstOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }
    }
}
