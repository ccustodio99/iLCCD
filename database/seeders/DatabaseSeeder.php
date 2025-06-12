<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\DocumentCategorySeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DocumentCategorySeeder::class);
        // Populate demo data
        $this->call(DemoSeeder::class);
    }
}
