<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper(Str::random(8)),
            'user_id' => User::factory(),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'inventory_category_id' => InventoryCategory::inRandomOrder()->value('id')
                ?? InventoryCategory::factory(),
            'department_id' => Department::factory(),
            'location' => fake()->city(),
            'supplier' => fake()->company(),
            'purchase_date' => now()->subDays(random_int(1, 365)),
            'quantity' => fake()->numberBetween(1, 50),
            'minimum_stock' => fake()->numberBetween(1, 5),
            'status' => InventoryItem::STATUS_AVAILABLE,
        ];
    }
}
