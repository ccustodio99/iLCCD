<?php

namespace Database\Seeders;

use App\Models\JobOrderType;
use Illuminate\Database\Seeder;

class JobOrderTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Repair', 'Installation', 'Setup'];
        foreach ($types as $name) {
            JobOrderType::create([
                'name' => $name,
                'is_active' => true,
            ]);
        }
    }
}
