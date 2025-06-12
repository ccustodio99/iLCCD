<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['IT', 'Facilities', 'Documents'];
        foreach ($categories as $name) {
            TicketCategory::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }
    }
}
