<?php

namespace Database\Factories;

use App\Models\Requisition;
use App\Models\RequisitionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RequisitionItem>
 */
class RequisitionItemFactory extends Factory
{
    protected $model = RequisitionItem::class;

    public function definition(): array
    {
        return [
            'requisition_id' => Requisition::factory(),
            'item' => $this->faker->words(2, true),
            'sku' => null,
            'quantity' => $this->faker->numberBetween(1, 5),
            'specification' => $this->faker->sentence(),
        ];
    }
}
