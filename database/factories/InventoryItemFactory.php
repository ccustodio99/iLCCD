<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['IT', 'Office', 'Facility']),
            'department' => fake()->randomElement(['IT', 'HR', 'Admin']),
            'location' => fake()->city(),
            'supplier' => fake()->company(),
            'purchase_date' => now()->subDays(random_int(1, 365)),
            'quantity' => fake()->numberBetween(1, 50),
            'minimum_stock' => fake()->numberBetween(1, 5),
            'status' => 'available',
        ];
    }
}
