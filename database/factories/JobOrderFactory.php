<?php

namespace Database\Factories;

use App\Models\JobOrder;
use App\Models\User;
use App\Models\JobOrderType;
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
            'job_type' => JobOrderType::factory()->create()->name,
            'description' => fake()->paragraph(),
            'attachment_path' => null,
            'status' => JobOrder::STATUS_PENDING_HEAD,
            'assigned_to_id' => null,
            'approved_at' => null,
            'started_at' => null,
            'start_notes' => null,
            'completed_at' => null,
            'completion_notes' => null,
        ];
    }
}
