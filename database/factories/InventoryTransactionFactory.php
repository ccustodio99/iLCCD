<?php

namespace Database\Factories;

use App\Models\InventoryTransaction;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    protected $model = InventoryTransaction::class;

    public function definition(): array
    {
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'user_id' => User::factory(),
            'requisition_id' => null,
            'job_order_id' => null,
            'action' => $this->faker->randomElement(['issue', 'return']),
            'quantity' => $this->faker->numberBetween(1, 5),
            'purpose' => $this->faker->sentence(),
        ];
    }
}
