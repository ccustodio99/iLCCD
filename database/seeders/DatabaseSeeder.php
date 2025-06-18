<?php

namespace Database\Seeders;

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
            ApprovalProcessSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
        ]);
        // Populate demo data
        $this->call(DemoSeeder::class);
    }
}
