<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'requisition_id' => Requisition::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'item' => $this->faker->words(2, true),
            'quantity' => $this->faker->numberBetween(1, 5),
            'status' => 'draft',
            'ordered_at' => null,
            'received_at' => null,
        ];
    }
}
