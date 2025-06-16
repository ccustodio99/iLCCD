<?php

namespace Database\Factories;

use App\Models\InventoryCategory;
use Database\Seeders\InventoryCategorySeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryCategory>
 */
class InventoryCategoryFactory extends Factory
{
    protected $model = InventoryCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(
                InventoryCategorySeeder::allNames()
            ),
            'is_active' => true,
        ];
    }
}
