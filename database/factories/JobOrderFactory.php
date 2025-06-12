<?php

namespace Database\Factories;

use App\Models\JobOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOrder>
 */
class JobOrderFactory extends Factory
{
    protected $model = JobOrder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_type' => fake()->randomElement(['Repair', 'Installation', 'Setup']),
            'description' => fake()->paragraph(),
            'attachment_path' => null,
            'status' => JobOrder::STATUS_PENDING_HEAD,
            'assigned_to_id' => null,
            'approved_at' => null,
            'started_at' => null,
            'completed_at' => null,
        ];
    }
}
