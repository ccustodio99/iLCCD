<?php

namespace Database\Factories;

use App\Models\Requisition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Requisition>
 */
class RequisitionFactory extends Factory
{
    protected $model = Requisition::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department' => fake()->randomElement(['IT', 'HR', 'Admin']),
            'item' => fake()->words(2, true),
            'quantity' => fake()->numberBetween(1, 10),
            'specification' => fake()->sentence(),
            'purpose' => fake()->sentence(),
            'status' => 'pending_head',
            'remarks' => null,
            'approved_by_id' => null,
            'approved_at' => null,
        ];
    }
}
