<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'IT' => ['Hardware', 'Software'],
            'Facilities' => ['Electrical', 'Plumbing'],
            'Documents' => ['Request', 'Record'],
            'Supplies' => ['Electronics', 'Furniture'],
            'Finance' => ['Budget', 'Purchasing'],
            'HR' => ['Payroll', 'Recruitment'],
            'Registrar' => ['Enrollment', 'Records'],
            'Clinic' => ['Medical', 'Dental'],
            'Security' => ['Safety', 'Incident'],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = TicketCategory::create([
                'name' => $parent,
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                TicketCategory::create([
                    'name' => $child,
                    'parent_id' => $parentCategory->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
