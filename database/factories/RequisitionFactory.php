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
            'department' => null,
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
                if ($requisition->user && ! $requisition->department) {
                    $requisition->department = $requisition->user->department;
                }
            })
            ->afterCreating(function (Requisition $requisition) {
                \App\Models\RequisitionItem::factory()
                    ->count(1)
                    ->create(['requisition_id' => $requisition->id]);
            });
    }
}
