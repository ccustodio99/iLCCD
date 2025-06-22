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
            'department_id' => null,
            'purpose' => $this->faker->sentence(),
            'attachment_path' => null,
            'status' => Requisition::STATUS_PENDING_HEAD,
            'remarks' => null,
            'approved_by_id' => null,
            'approved_at' => null,
        ];
    }

    public function configure(): static
    {
        return $this
            ->afterMaking(function (Requisition $requisition) {
                if ($requisition->user && ! $requisition->department_id) {
                    $requisition->department_id = $requisition->user->department_id;
                }
            })
            ->afterCreating(function (Requisition $requisition) {
                \App\Models\RequisitionItem::factory()
                    ->count(1)
                    ->create(['requisition_id' => $requisition->id]);
            });
    }
}
