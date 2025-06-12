<?php

namespace Database\Factories;

use App\Models\JobOrderType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOrderType>
 */
class JobOrderTypeFactory extends Factory
{
    protected $model = JobOrderType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'is_active' => true,
        ];
    }
}
