<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use Illuminate\Database\Seeder;

class InventoryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Electronics', 'Supplies', 'Furniture'];
        foreach ($categories as $name) {
            InventoryCategory::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }
    }
}
