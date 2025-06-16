<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\DocumentCategorySeeder;
use Database\Seeders\TicketCategorySeeder;
use Database\Seeders\InventoryCategorySeeder;
use Database\Seeders\JobOrderTypeSeeder;
use Database\Seeders\SettingSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TicketCategorySeeder::class,
            InventoryCategorySeeder::class,
            JobOrderTypeSeeder::class,
            DocumentCategorySeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
        ]);
        // Populate demo data
        $this->call(DemoSeeder::class);
    }
}
