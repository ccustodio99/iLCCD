<?php

namespace Database\Factories;

use App\Models\JobOrder;
use App\Models\JobOrderType;
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
            // Use a seeded job order type if available
            'job_order_type_id' => JobOrderType::inRandomOrder()->value('id'),
            'description' => fake()->paragraph(),
            'attachment_path' => null,
            'status' => JobOrder::STATUS_PENDING_HEAD,
            'assigned_to_id' => null,
            'approved_at' => null,
            'started_at' => null,
            'start_notes' => null,
            'completed_at' => null,
            'completion_notes' => null,
            'closed_at' => null,
        ];
    }
}
