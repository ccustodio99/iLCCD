<?php

namespace Database\Factories;

use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketCategory>
 */
class TicketCategoryFactory extends Factory
{
    protected $model = TicketCategory::class;

    public function definition(): array
    {
        return [
            'parent_id' => null,
            'name' => $this->faker->word(),
            'is_active' => true,
        ];
    }
}
