<?php

namespace Database\Factories;

use App\Models\JobOrderType;
use Database\Seeders\JobOrderTypeSeeder;
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
            'name' => $this->faker->unique()->randomElement(
                JobOrderTypeSeeder::allNames()
            ),
            'is_active' => true,
        ];
    }
}
